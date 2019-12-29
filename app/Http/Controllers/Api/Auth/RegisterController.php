<?php


namespace App\Http\Controllers\Api\Auth;


use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\User;
use App\Notifications\EmailVerificationNotification;
use App\Rules\UniqueIdentityNumber;
use App\Rules\UniquePhoneNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
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
    public function register(Request $request)
    {

        if ($this->validator($request)->fails()) {
            return response()->json([
                'error' => $this->validator($request)->errors()
            ], ResponseCodeEnum::Success);
        }

        try {
            DB::beginTransaction();

            $input = $request->all();

            $user = $this->createUser($input);

            $data['name'] = $user->name;
            $data['email'] = $user->email;
            $data['phone_number'] = $user->phone_number;
            $data['verified'] = false;
            $data['token'] =  $user->createToken('AppName')->accessToken;

            $this->createPatient($user, $input);

            $user->sendEmailVerificationNotification();

            DB::commit();
        } catch (\Exception $e) {
            Log::info($e);
            return response()->json($e->getMessage(), ResponseCodeEnum::Error);
        }

        return response()->json($data, ResponseCodeEnum::Success);
    }

    /**
     * @param $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function validator($request)
    {
        return Validator::make($request->all(),
            [
                'phone_number'        => ['required', 'numeric', new UniquePhoneNumber()],
                'name'                => ['required', 'string', 'max:40'],
                'email'               => ['required', 'email', 'unique:users'],
                'password'            => ['required', 'min:8', 'alpha_num'],
                'c_password'          => ['required', 'same:password'],
                'full_name'           => ['required', 'string', 'max:40'],
                'mother_name'         => ['required', 'string', 'max:40'],
                'identity_number'     => ['nullable', 'numeric', new UniqueIdentityNumber()],
                'dob'                 => ['required', 'date_format:Y-m-d'],
                'gender'              => ['required', 'boolean'],
                'blood_type'          => ['required', 'string', 'in:A,B,AB,O'],
                'address'             => ['required', 'string', 'min:15'],
                'identity_photo'      => ['nullable', 'image', 'mimes: jpg, jpeg, png', 'max:5024']
            ]);
    }

    /**
     * @param $input
     * @return mixed
     */
    private function createUser($input)
    {
        $input['password'] = bcrypt($input['password']);

        return User::create($input);
    }

    /**
     * @param $user
     * @param $input
     * @return mixed
     */
    private function createPatient($user, $input)
    {
        $patient = Patient::create([
            'auth_id'           => $user->id,
            'full_name'         => $input['full_name'],
            'mother_name'       => $input['mother_name'],
            'identity_number'   => $input['identity_number'] ?? null,
            'dob'               => $input['dob'],
            'gender'            => $input['gender'],
            'blood_type'        => $input['blood_type'],
            'address'           => $input['address'],
            'identity_photo'    => $input['identity_number'] ?? null
        ]);

        return $patient;
    }

}
