<?php


namespace App\Http\Controllers\Api\Auth;


use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
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
    public function login(Request $request)
    {
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
            $data["id"] = $user->id;
            $data["phone_number"] = $user->phone_number;
            $data["name"] = $user->name;
            $data["email"] = $user->email;
            $data["verified"] = $user->hasVerifiedEmail();
            $data["token"] =  $user->createToken('AppName')->accessToken;

            return response()->json($data, ResponseCodeEnum::Success);

        }

        return response()->json(['error'=>'Unauthorised'], ResponseCodeEnum::UnAuthorized);

    }

}
