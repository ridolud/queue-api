<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Enums\ResponseCodeEnum;

class AuthController extends Controller
{
	/**
      @OA\Post(
          path="/api/v1/register",
          tags={"Authenticate"},
          summary="Register",
          operationId="register",

          @OA\RequestBody(
              description="Authenticate",
              required=true,
              @OA\MediaType(
    	        mediaType="multipart/form-data",
    	        @OA\Schema (
                @OA\Property(property="phone_number", type="string"),
    	        	@OA\Property(property="name", type="string"),
      					@OA\Property(property="email", type="string"),
      					@OA\Property(property="password", type="string"),
      					@OA\Property(property="c_password", type="string"),
    	        )
     		  )
          ),
         @OA\Response(response="default", description="successful operation")
      )
     */
 	public function register(Request $request) {
 		$validator = Validator::make($request->all(),
            [
              'phone_number' => 'required',
              'name' => 'required',
              'email' => 'required|email|unique:users',
              'password' => 'required',
              'c_password' => 'required|same:password',
            ]);

 		if ($validator->fails()) {
       		return response()->json(['error'=>$validator->errors()], ResponseCodeEnum::Error);
    	}

	 	$input = $request->all();
	 	$input['password'] = bcrypt($input['password']);
	 	$user = User::create($input);
        $data['name'] = $user->name;
        $data['email'] = $user->name;
        $data['phone_number'] = $user->phone_number;
	 	$data['token'] =  $user->createToken('AppName')->accessToken;

	 	return response()->json($data, ResponseCodeEnum::Success);
	}

   	/**
      @OA\Post(
          path="/api/v1/login",
          tags={"Authenticate"},
          summary="Login",
          operationId="login",

          @OA\RequestBody(
              description="Authenticate",
              required=true,
              @OA\MediaType(
    	        mediaType="multipart/form-data",
    	        @OA\Schema (
					@OA\Property(property="email", type="string"),
					@OA\Property(property="password", type="string"),
    	        )
     		  )
          ),
         @OA\Response(response="default", description="successful operation")
      )
    */
	public function login(Request $request) {

        $validator = Validator::make($request->all(),
            [
                'email' => ['required', 'email'],
                'password' => ['required', 'alpha_num', 'string', 'min:6'],
            ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], ResponseCodeEnum::Error);
        }

        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){

	   		$user = Auth::user();
            $data["phone_number"] = $user->phone_number;
	   		$data["name"] = $user->name;
	   		$data["email"] = $user->email;
	   		$data["token"] =  $user->createToken('AppName')->accessToken;

	    	return response()->json($data, ResponseCodeEnum::Success);

	  	} else {

	   	    return response()->json(['error'=>'Unauthorised'], ResponseCodeEnum::UnAuthorized);

	   	}
	}


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
	public function getUser() {
		$user = Auth::user();

	 	return response()->json(['data' => $user], ResponseCodeEnum::Success);
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
      public function addDeviceToken(Request $request) {

        $validator = Validator::make($request->all(),
            [
              'device_token' => 'required',
            ]);

        if ($validator->fails()) {
          return response()->json(['error'=>$validator->errors()], ResponseCodeEnum::UnAuthorized);
        }

        $input = $request->all();
        $data = Auth::user();

        $data->device_token = $input['device_token'];
        $data->update();

        return response()->json($input, ResponseCodeEnum::Success);
      }
}
