<?php

namespace App\Http\Controllers\Api;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Patient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PatientController extends Controller
{

    /**
      @OA\get(
          path="/api/v1/patient/my_data",
          tags={"Data Patient"},
          summary="Edit my data patient",
          operationId="getmydata",
          security={ {"bearerAuth": {}}, },

         @OA\Response(response="default", description="successful operation")
      )
    */

    function getMyData()
    {
    	$data = Patient::where('auth_id', Auth::id())->first();

    	return response()->json([
    		'data' => $data,
    	], ResponseCodeEnum::Success);
    }

    /**
      @OA\Post(
          path="/api/v1/patient/my_data",
          tags={"Data Patient"},
          summary="Edit my data patient",
          operationId="editmydata",
          security={ {"bearerAuth": {}}, },
          @OA\RequestBody(
              description="form",
              required=true,
              @OA\MediaType(
                mediaType="multipart/form-data",
                @OA\Schema (
                    @OA\Property(property="full_name", type="string"),
                    @OA\Property(property="mother_name", type="string"),
                    @OA\Property(property="identity_number", type="string"),
                    @OA\Property(property="dob", type="date"),
                    @OA\Property(property="gender", type="int", description="1 = female, 0 = male"),
                    @OA\Property(property="blood_type", type="string", description="ex: o"),
                    @OA\Property(property="address", type="string"),

                )
              )
          ),

         @OA\Response(response="default", description="successful operation")
      )
    */
    function saveMyData(Request $request)
    {
    	$validator = Validator::make($request->all(),
            [
            	'full_name' => 'required',
		        'mother_name' => 'required',
		        'identity_number' => 'required',
		        'dob' => 'required|date',
		        'gender' => 'required',
		        'blood_type' => 'required',
		        'address' => 'required',
            ]);

 		if ($validator->fails()) {
       		return response()->json(['error'=>$validator->errors()], ResponseCodeEnum::UnAuthorized);
    	}

 		try {
 		    $patient = Patient::updateOrCreate([
                ['auth_id' => Auth::id()],
                $request->all()
            ]);

            return response()->json($patient, ResponseCodeEnum::Success);
        } catch (\Exception $e) {
            return response()->json(['error'=>$validator->errors()], ResponseCodeEnum::UnAuthorized);
        }
    }
}
