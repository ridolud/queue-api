<?php

namespace App\Http\Controllers\Api;

use App\Doctor;
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
     * Display a listing of the resource.
     *
     * @param Request $request
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
     * Show result of query from doctor name
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        try {
            $doctors = Doctor::select('doctor.full_name', 'schedule.day', 'schedule.time')
                ->doctorSchedule()
                ->where([
                    'poli_id'       => $request->poli_id,
                    'hospital_id'   => $request->hospital_id
                ])
                ->where('full_name', 'like', '%' . strtoupper($request->doctor_name) . '%')
                ->orWhere('full_name', 'like', '%' . strtoupper($request->doctor_name) . '%')
                ->orWhere('full_name', 'like', '%' . ucfirst($request->doctor_name) . '%')
                ->orWhere('full_name', 'like', '%' . ucwords($request->doctor_name) . '%')
                ->paginate(ListDataEnum::TotalItemPerRequest);

            return response()->json($doctors, ResponseCodeEnum::Success);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }

    }



}
