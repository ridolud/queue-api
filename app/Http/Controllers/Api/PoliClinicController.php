<?php

namespace App\Http\Controllers\Api;

use App\Enums\ListDataEnum;
use App\Enums\ResponseCodeEnum;
use App\PoliClinic;
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
}
