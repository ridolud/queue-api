<?php

namespace App\Http\Controllers\Api;

use App\Enums\QueueEnum;
use App\Enums\ResponseCodeEnum;
use App\Models\QueueProcess;
use App\Rules\CheckIfQueueExists;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class QueueProcessController extends Controller
{


    /**
    @OA\Post(
    path="/api/v1/queue",
    tags={"Data Queue Process"},
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
    @OA\Property(property="insurance_id", type="string", optional=true),
    @OA\Property(property="doctor_schedule", type="string"),

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
            'doctor_schedule_id' => ['required', 'string'],
        ]);

        if ($fails = $validator->fails()) {
            return response()->json($fails, ResponseCodeEnum::Error);
        }

        QueueProcess::create([
            'user_id' => Auth::user()->id,
            'patient_id' => $request->patient_id,
            'insurance_id' => $request->insurance_id ?? null,
            'doctor_schedule_id' => $request->doctor_schedule_id,
            'is_valid' => QueueEnum::Valid,
            'submit_time' => Carbon::now(),
        ]);

        return response()->json([
            "success" => true,
        ], ResponseCodeEnum::Success);
    }

    /**
    @OA\Get(
    path="/api/v1/queue/index",
    tags={"Histories Queue"},
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
        $queues = Auth::user()->queue;

        return response($queues, ResponseCodeEnum::Success);
    }

}