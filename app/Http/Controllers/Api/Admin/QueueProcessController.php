<?php


namespace App\Http\Controllers\Api\Admin;

use App\Enums\ListDataEnum;
use App\Enums\NotificationTypeEnum;
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
    public function getTodayQueue($hospital_id, $poli_id)
    {
        $queues = QueueProcess::hospital()
            ->selectedColumn()
            ->where('doctor.hospital_id', $hospital_id)
            ->where('doctor.poli_id', $poli_id)
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
            'current_status' => ['required', 'integer', new IsMoreThanOneRequest($request->queue_id)],
            'queue_id' => ['required', 'string', 'exists:queue_process,id']
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

        SendNotification::dispatch($queue->user->device_token, Helper::setMessageNotification($type, $title))
            ->delay(now()->addSeconds(15))
            ->onQueue('send-notification');
    }

}
