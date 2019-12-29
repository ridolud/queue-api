<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Enums\ResponseCodeEnum;

class AuthController extends Controller
{
	/**
      @OA\Get(
          path="/api/v1/user",
          tags={"Profile"},
          summary="Get profile data",
          operationId="profile",
          security={ {"bearerAuth": {}}, },

         @OA\Response(response="default", description="successful operation")
      )
    */
	public function getUser()
    {
		$user = Auth::user();

	 	return response()->json($user, ResponseCodeEnum::Success);
	}

    /**
      @OA\Get(
          path="/api/v1/add-device-token",
          tags={"Profile"},
          summary="Add Device Token",
          operationId="adddevicetoken",
          security={ {"bearerAuth": {}}, },

         @OA\Response(response="default", description="successful operation")
      )
    */
      public function addDeviceToken(Request $request)
      {

        $validator = Validator::make($request->all(),
            [
              'device_token' => 'required',
            ]);

        if ($validator->fails()) {
          return response()->json([
              'error'=>$validator->errors()
          ], ResponseCodeEnum::InvalidRequest);
        }

        $input = $request->all();
        $data = Auth::user();

        $data->device_token = $input['device_token'];
        $data->update();

        return response()->json($input, ResponseCodeEnum::Success);
      }
}
