<?php


namespace App\Http\Controllers\Api\Admin;

use App\Enums\ListDataEnum;
use App\Enums\NotificationTypeEnum;
use App\Enums\NotificationCategoryEnum;
use App\Enums\QueueEnum;
use App\Enums\QueueNameEnum;
use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\Controller;
use App\Jobs\QueueProcessLog;
use App\Jobs\QueueProcessLog as QueueProcessLogJob;
use App\Jobs\QueueEstimationTime as QueueEstimationTimeJob;
use App\Jobs\SendNotification;
use App\Models\QueueProcess;
use App\Rules\IsMoreThanOneRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Libs\Helper;
use Illuminate\Support\Facades\Validator;

class QueueProcessController extends Controller
{
    /**
    @OA\Get(
    path="/api/v1/admin/queue/index/{hospital_id}/{poli_id}",
    tags={"Admin"},
    summary="get list queue for admin",
    operationId="profile",

    @OA\Response(response="default", description="successful operation")
    )

     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getTodayQueue(Request $request, $hospital_id, $poli_id)
    {
        $queues = QueueProcess::hospital()
            ->selectedColumn()
            ->where('doctor.hospital_id', $hospital_id)
            ->where('doctor.poli_id', $poli_id)
            ->when('doctor_schedule_id', function ($query) use ($request) {
                if (isset($request->schedule)) {
                    $query->where('doctor_schedule_id', $request->schedule);
                }
            })
            ->when('process_status', function ($query) use ($request) {
                if (isset($request->state)) {
                    $query->where('process_status', $request->state);
                }
            })
            ->orderBy('submit_time', 'asc')
            ->paginate(ListDataEnum::TotalItemPerRequest);

        return response()->json($queues, ResponseCodeEnum::Success);
    }

    /**
    @OA\Post(
    path="/api/v1/admin/queue/update-status",
    tags={"Admin"},
    summary="
    Update Status Queue
    const waiting = 0;
    const checkIn = 1;
    const checkOut = 2;
    const skipped = 3;",
    operationId="storequeue",
    security={ {"bearerAuth": {}}, },
    @OA\RequestBody(
    description="form",
    required=true,
    @OA\MediaType(
    mediaType="multipart/form-data",
    @OA\Schema (
    @OA\Property(property="patient_id", type="string"),
    @OA\Property(property="current_status", type="integer"),
    )
    )
    ),
    @OA\Response(response="default", description="successful operation")
    )
     */
    public function updateCurrentQueueStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'queue_id' => ['required', 'string', 'exists:queue_process,id'],
            'current_status' => ['required', 'integer', new IsMoreThanOneRequest($request->queue_id)],
        ]);

        if ($validator->fails()) {
            return response()
                ->json([
                    'success' => false,
                    'messages' => $validator->getMessageBag()->first()
                ], ResponseCodeEnum::InvalidRequest);
        }

        try {
            DB::beginTransaction();

            $status = $request->current_status;
            $queue_id = $request->queue_id;

            QueueProcess::where('id', $queue_id)
                ->update([
                    'process_status'    => $status,
                    'is_valid'          => Helper::isQueueValid($status)
                ]);

            $queue = QueueProcess::find($queue_id);

            QueueProcessLog::dispatch($queue_id, $status)
                ->delay(now()->addSeconds(15))
                ->onQueue(QueueNameEnum::QUEUE_PROCESS_LOG);

            QueueEstimationTimeJob::dispatch($queue->doctor_schedule_id, $queue->submit_time)
                ->delay(now()->addSeconds(30))
                ->onQueue(QueueNameEnum::QUEUE_ESTIMATION_TIME);

            $this->collectDeviceToken($queue->doctor_schedule_id, $status);

            DB::commit();
        } catch (\Error $error) {
            DB::rollBack();

            return response()->json([
                'message' => $error->getMessage()
            ], ResponseCodeEnum::Error);
        }

        return response()->json([
            "success" => true,
            "message" => "Status Change to " . $request->current_status
            ], ResponseCodeEnum::Success);
    }

    /**
     * this process used to log any changes in queue process
     * @model QueueProcessLog
     * @return void
     */
    private function logQueue($queue_id, $status)
    {
        $queue = QueueProcess::where('id', $queue_id)->first();

        QueueProcessLogJob::dispatch($queue_id, $status)
            ->delay(now()->addSeconds(15))
            ->onQueue('logging-process-queue');
    }

    /**
     * TODO: REMOVE THIS METHOD & ROUTER SOON
     * @param $doctor_schedule_id
     */
    public function calculateEstimation($doctor_schedule_id)
    {
        QueueEstimationTimeJob::dispatchNow($doctor_schedule_id);
    }

    public function sendNotification($queue_id)
    {
        $queue = QueueProcess::where('id', $queue_id)->first();

        $type = NotificationTypeEnum::normal;
        $title = "Giliran anda kurang Lagi do 456:)";
        $category = NotificationCategoryEnum::update_queue;

        SendNotification::dispatch($queue->user->device_token, Helper::setMessageNotification($type, $title, $category))
            ->delay(now()->addSeconds(15))
            ->onQueue('send-notification');
    }

    /**
     * send notification to incoming patient
     * @param $doctor_schedule_id
     * @param $status
     * @return void
     */
    private function collectDeviceToken($doctor_schedule_id, $status)
    {
        $queue_process = QueueProcess::where('doctor_schedule_id', $doctor_schedule_id)
            ->where('process_status', QueueEnum::waiting)
            ->orderBy('submit_time', 'asc')
            ->first();

        if ($queue_process) {
            $type = NotificationTypeEnum::normal;
            $title = "Gilian anda sekarang, silahkan masuk";

            if ($status == QueueEnum::checkIn) {
                $title = "Gilian anda sebentar lagi";
            }

            $category = NotificationCategoryEnum::update_queue;

            $message = Helper::setMessageNotification($type, $title, $category);

            SendNotification::dispatch($queue_process->user->device_token, $message)
                ->delay(now()->addSeconds(40))
                ->onQueue(QueueNameEnum::SEND_NOTIFICATION);
        }

    }

}
