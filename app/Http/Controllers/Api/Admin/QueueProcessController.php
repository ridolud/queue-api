<?php


namespace App\Http\Controllers\Api\Admin;


use App\Enums\ListDataEnum;
use App\Enums\QueueEnum;
use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\Api\TestPushNotifController;
use App\Http\Controllers\Controller;
use App\Models\QueueProcess;
use Edujugon\PushNotification\PushNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            ->select([
                'queue_process.*',
                'doctor.poli_id as poli_id',
                'doctor.hospital_id as hospital_id'
            ])
            ->where('hospital_id', $hospital_id)
            ->where('poli_id', $poli_id)
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
    public function updateCurrentQueueStatus(Request $request, $deviceToken)
    {
        try {
            DB::beginTransaction();

            QueueProcess::where('patient_id', $request->patient_id)
                ->update([
                    'process_status' => $request->current_status
                ]);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();

            return response()->json([
                'message' => $exception
            ], ResponseCodeEnum::Error);
        }

        return response()->json([
            "success" => true,
            "message" => "Status Change to " . $request->current_status
            ], ResponseCodeEnum::Success);
    }

}
