<?php

namespace App\Http\Controllers\Api;

use App\Enums\ResponseCodeEnum;
use App\Models\Insurance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InsuranceController extends Controller
{
    /**
    @OA\get(
    path="/api/v1/insurance/{hospital_id}",
    tags={"Insurance"},
    summary="List of insurance",

    @OA\Response(response="default", description="successful operation")
    )
     */
    public function index($hospital_id)
    {
        $insurances = Insurance::where('hospital_id', $hospital_id)->get();

        return response()->json($insurances, ResponseCodeEnum::Success);
    }

}
