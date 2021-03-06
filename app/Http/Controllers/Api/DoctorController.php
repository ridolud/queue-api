<?php

namespace App\Http\Controllers\Api;

use App\Enums\TimeConfigEnum;
use App\Libs\Helper;
use App\Models\Doctor;
use App\Enums\ListDataEnum;
use App\Enums\ResponseCodeEnum;
use App\Models\DoctorSchedule;
use Carbon\Carbon;
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
        try {

            $list_doctors = [];

            $doctors = Doctor::where([
                    'poli_id'       => $request->poli_id,
                    'hospital_id'   => $request->hospital_id
                ])
                ->get();

            foreach ($doctors as $doctor) {
                $arr_doctor = [
                    'full_name' => $doctor->full_name,
                    'avatar' => $doctor->avatar
                ];

                $arr_doctor["schedule"] = DoctorSchedule::where('doctor_id', $doctor->id)
                                        ->get();

                $now = Carbon::now()
                    ->timeZone(TimeConfigEnum::zone)
                    ->format(TimeConfigEnum::format);

                foreach ($arr_doctor["schedule"] as $schedule) {

                    if ($now < $schedule->time_end) {
                        $schedule["is_available"] = true;
                        $tomorrow = Carbon::tomorrow(TimeConfigEnum::zone);
                        $schedule["queue_remaining"] = Helper::getQueueRemaining($schedule["id"], $tomorrow);
                    } else {
                        $schedule["is_available"] = false;
                        $schedule["queue_remaining"] = 0;
                    }

                }


                array_push($list_doctors, $arr_doctor);
            }

            return response()->json($list_doctors, ResponseCodeEnum::Success);

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
            $list_doctors = [];

            $doctors = Doctor::where([
                    'hospital_id' => $request->hospital_id,
                    'poli_id' => $request->poli_id
                ])
                ->where(function ($query) use ($request) {
                    $query->where('full_name', 'like', '%' . strtoupper($request->doctor_name) . '%')
                        ->orWhere('full_name', 'like', '%' . strtoupper($request->doctor_name) . '%')
                        ->orWhere('full_name', 'like', '%' . ucfirst($request->doctor_name) . '%')
                        ->orWhere('full_name', 'like', '%' . ucwords($request->doctor_name) . '%');
                })
                ->get();

             foreach ($doctors as $doctor) {

                 $arr_doctor = [
                     'full_name' => $doctor->full_name,
                 ];

                 $arr_doctor["schedule"] = DoctorSchedule::where('doctor_id', $doctor->id)
                     ->get();

                 $now = Carbon::now()
                     ->timeZone(TimeConfigEnum::zone)
                     ->format(TimeConfigEnum::format);

                 foreach ($arr_doctor["schedule"] as $schedule) {

                     if ($now < $schedule->time_end) {
                        $schedule["is_available"] = true;
                        $tomorrow = Carbon::tomorrow(TimeConfigEnum::zone);
                        $schedule["queue_remaining"] = Helper::getQueueRemaining($schedule["id"], $tomorrow);
                     } else {
                        $schedule["is_available"] = false;
                         $schedule["queue_remaining"] = 0;
                     }

                 }

                 array_push($list_doctors, $arr_doctor);
             }

            return response()->json($list_doctors, ResponseCodeEnum::Success);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }

    }

}
