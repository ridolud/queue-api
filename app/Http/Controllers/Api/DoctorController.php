<?php

namespace App\Http\Controllers\Api;

use App\Models\Doctor;
use App\Enums\ListDataEnum;
use App\Enums\ResponseCodeEnum;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class DoctorController
 * @package App\Http\Controllers\Api
 */
class DoctorController extends Controller
{
    /**
    @OA\Get(
    path="/api/v1/doctor/{hospital_id}/{poli_id}",
    tags={"Doctor"},
    summary="Get Doctor list",
    operationId="profile",

    @OA\Response(response="default", description="successful operation")
    )

     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        try {
            $doctors = Doctor::select('doctor.full_name', 'schedule.day', 'schedule.time')
                ->doctorSchedule()
                ->where([
                    'poli_id'       => $request->poli_id,
                    'hospital_id'   => $request->hospital_id
                ])
                ->paginate(ListDataEnum::TotalItemPerRequest);

            return response()->json($doctors, ResponseCodeEnum::Success);
        } catch (\Error $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }

    }


    /**
    @OA\Get(
    path="/api/v1/doctor/{hospital_id}/{poli_id}/{doctor_name}",
    tags={"Doctor"},
    summary="Get Doctor list from search",
    operationId="profile",

    @OA\Response(response="default", description="successful operation")
    )

     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {

        try {
            $doctors = Doctor::select('doctor.full_name', 'schedule.day', 'schedule.time')
                ->doctorSchedule()
                ->where([
                    'hospital_id' => $request->hospital_id,
                    'poli_id' => $request->poli_id
                ])
                ->where(function ($query) use ($request) {
                    $query->where('full_name', 'like', '%' . strtoupper($request->doctor_name) . '%')
                        ->orWhere('full_name', 'like', '%' . strtoupper($request->doctor_name) . '%')
                        ->orWhere('full_name', 'like', '%' . ucfirst($request->doctor_name) . '%')
                        ->orWhere('full_name', 'like', '%' . ucwords($request->doctor_name) . '%');
                })
                ->paginate(ListDataEnum::TotalItemPerRequest);

            return response()->json($doctors, ResponseCodeEnum::Success);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }

    }



}
