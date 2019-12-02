<?php

namespace App\Http\Controllers\Api;

use App\Enums\ListDataEnum;
use App\Enums\ResponseCodeEnum;
use App\Models\PoliClinic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PoliClinicController extends Controller
{
    /**
    @OA\Get(
    path="/api/v1/poli/{hospital_id}",
    tags={"Poli Clinic"},
    summary="Get Policlinic list by query",
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
            $poli = PoliClinic::where('hospital_id', $request->hospital_id)
                ->paginate(ListDataEnum::TotalItemPerRequest);

            return response()->json($poli, ResponseCodeEnum::Success);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }

    /**
    @OA\Get(
    path="/api/v1/poli/{hospital_id}/{poli_name}",
    tags={"Search Poli Clinic"},
    summary="Search Policlinic list by query",
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
            $poli = PoliClinic::where('hospital_id', $request->hospital_id)
                ->where(function ($query) use ($request) {
                    $query->where('full_name', 'like', '%' . strtoupper($request->poli_name) . '%')
                        ->orWhere('full_name', 'like', '%' . strtolower($request->poli_name) . '%')
                        ->orWhere('full_name', 'like', '%' . ucfirst($request->poli_name) . '%')
                        ->orWhere('full_name', 'like', '%' . ucwords($request->poli_name) . '%');
                })
                ->paginate(ListDataEnum::TotalItemPerRequest);

            return response()->json($poli, ResponseCodeEnum::Success);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), $e->getCode());
        }
    }
}
