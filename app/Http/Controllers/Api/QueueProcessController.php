<?php

namespace App\Http\Controllers\Api;

use App\Enums\QueueEnum;
use App\Enums\ResponseCodeEnum;
use App\Enums\TimeConfigEnum;
use App\Libs\Helper;
use App\Models\QueueEstimationTime as QueueEstimationTimeModel;
use App\Models\QueueProcess;
use App\Rules\CheckIfQueueExists;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class QueueProcessController extends Controller
{
    /**
    @OA\Post(
    path="/api/v1/queue",
    tags={"Queue Process"},
    summary="Store Queue",
    operationId="storequeue",
    security={ {"bearerAuth": {}}, },
    @OA\RequestBody(
    description="form",
    required=true,
    @OA\MediaType(
    mediaType="multipart/form-data",
    @OA\Schema (
    @OA\Property(property="patient_id", type="string"),
    @OA\Property(property="doctor_schedule", type="string"),
    @OA\Property(property="insurance_id", type="string"),
    )
    )
    ),
    @OA\Response(response="default", description="successful operation")
    )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'patient_id' => ['required', 'string', new CheckIfQueueExists()],
            'insurance_id' => ['nullable', 'string'],
            'doctor_schedule_id' => ['required', 'string']
        ]);

        $current_queue = QueueProcess::where([
                'user_id' => Auth::user()->id,
                'is_valid' => true
            ])
            ->get()
            ->count();

        if ( ($fails = $validator->fails()) || ($current_queue > 0) ) {
            return response()->json([
                "success" => false,
                "message" => QueueEnum::failedStoringQueue], ResponseCodeEnum::InvalidRequest);
        }

        QueueProcess::create([
            'user_id' => Auth::user()->id,
            'patient_id' => $request->patient_id,
            'insurance_id' => $request->insurance_id ?? null,
            'doctor_schedule_id' => $request->doctor_schedule_id,
            'is_valid' => QueueEnum::Valid,
            'submit_time' => Carbon::now()->timeZone(TimeConfigEnum::zone),
            'process_status' => QueueEnum::waiting,
        ]);

        return response()->json([
            "success" => true,
            "message" => QueueEnum::successStoringQueue
        ], ResponseCodeEnum::Success);
    }

    /**
    @OA\Get(
    path="/api/v1/queue/index",
    tags={"Queue Process"},
    summary="get list histories of queue per patient",
    operationId="profile",

    @OA\Response(response="default", description="successful operation")
    )

     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $queues = Auth::user()
            ->queue()
            ->selectedColumn()
            ->hospital()
            ->get();

        return response()->json($queues, ResponseCodeEnum::Success);
    }

    /**
    @OA\Get(
    path="/api/v1/queue/current",
    tags={"Queue Process"},
    summary="get current queue patient",
    operationId="profile",

    @OA\Response(response="default", description="successful operation")
    )

     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCurrentQueue()
    {
        try {
            $my_queue = Auth::user()
                ->queue()
                ->selectedColumn()
                ->hospital()
                ->where('is_valid', QueueEnum::Valid)
                ->orderBy('submit_time', 'desc')
                ->first();

            if (!$my_queue) {
                return response()->json([
                    "success" => false,
                    "message" => QueueEnum::currentQueueEmpty,
                    "data" => null
                ], ResponseCodeEnum::Success);
            }

            $queue = $my_queue->toArray();

            $queue["queue_remaining"] = Helper::getQueueRemaining($my_queue->doctor_schedule_id, $my_queue->submit_time);

            return response()->json([
                "success" => true,
                "message" => QueueEnum::currentQueueExists,
                "data" => $queue
            ], ResponseCodeEnum::Success);

        } catch (\Exception $exception) {
            return response()->json($exception, ResponseCodeEnum::Error);
        }
    }

    /**
    @OA\Get(
    path="/api/v1/queue/estimation/{doctor_schedule_id}",
    tags={"Queue Process"},
    summary="get current queue estimation time",
    operationId="profile",

    @OA\Response(response="default", description="successful operation")
    )

     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getQueueEstimationTime()
    {
        try {
            DB::beginTransaction();

            $queue = Auth::user()
                ->queue()
                ->orderBy('submit_time', 'desc')
                ->first();

            if (!$queue)
            {
                return response()->json([
                    'success' => false,
                    'message' => 'You haven\'t any queue number',
                ], ResponseCodeEnum::Success);
            }

            $estimation = QueueEstimationTimeModel::where('doctor_schedule_id', $queue->doctor_schedule_id)
                ->first([
                    'estimation',
                    'time'
                ]);

            if (!$estimation)
            {
                return response()->json([
                    'success' => false,
                    'message' => 'Estimation doesnt exists',
                ], ResponseCodeEnum::Success);
            }

            $queue_remaining = Helper::getQueueRemaining($queue->doctor_schedule_id, $queue->submit_time);

            DB::commit();
        } catch (\Error $error) {
            Log::error($error);
            DB::rollBack();
        }

        return response()->json([
            'success' => true,
            'message' => 'Success get estimation time',
            'data' => [
                'estimation' => $estimation->estimation,
                'time' => $estimation->time,
                'queue_remaining' => $queue_remaining
            ]
        ], ResponseCodeEnum::Success);
    }

}
