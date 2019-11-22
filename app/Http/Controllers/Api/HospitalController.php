<?php

namespace App\Http\Controllers\Api;

use App\Enums\ListDataEnum;
use App\Enums\ResponseCodeEnum;
use App\Hospital;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Psy\Util\Str;

class HospitalController extends Controller
{
    /**
      @OA\Get(
          path="/api/v1/hospital",
          tags={"Hospital"},
          summary="Get hospital list",
          operationId="profile",

         @OA\Response(response="default", description="successful operation")
      )

     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $data = Hospital::select('*', 'indonesia_cities.name as city_name', 'indonesia_provinces.name as province_name')
                ->leftJoin('indonesia_cities', 'indonesia_cities.id', 'hospital.city_id')
                ->leftJoin("indonesia_provinces", "hospital.province_id", "indonesia_provinces.id")
                ->paginate(ListDataEnum::TotalItemPerRequest);
            return response()->json($data, ResponseCodeEnum::Success);
        } catch (\Error $e) {
            return response()->json($e, $e->getCode());
        }
    }

    /**
    @OA\Get(
    path="/api/v1/hospital/{full_name}",
    tags={"Hospital"},
    summary="Get hospital list by query",
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
            $data = Hospital::select('*', 'indonesia_cities.name as city_name', 'indonesia_provinces.name as province_name')
                ->leftJoin('indonesia_cities', 'indonesia_cities.id', 'hospital.city_id')
                ->leftJoin("indonesia_provinces", "hospital.province_id", "indonesia_provinces.id")
                ->where('full_name', 'like', '%' . strtolower($request->full_name) . '%')
                ->orWhere('full_name', 'like', '%' . strtoupper($request->full_name) . '%')
                ->orWhere('full_name', 'like', '%' . ucfirst($request->full_name) . '%')
                ->orWhere('full_name', 'like', '%' . ucwords($request->full_name) . '%')
                ->paginate(ListDataEnum::TotalItemPerRequest);
            return response()->json($data, ResponseCodeEnum::Success);
        } catch (\Error $e) {
            return response()->json($e, $e->getCode());
        }
    }

}
