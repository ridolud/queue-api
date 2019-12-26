<?php

namespace App\Http\Controllers\Api;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\Controller;
use App\Rules\UniqueIdentityNumber;
use Illuminate\Http\Request;
use App\Models\Patient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PatientController extends Controller
{
    public function __construct(Request $request)
    {
        $this->validator = Validator::make($request->all(),
            [
                'full_name'         => ['required', 'string', 'max:40'],
                'mother_name'       => ['required', 'string', 'max:40'],
                'identity_number'   => ['nullable', 'numeric', new UniqueIdentityNumber()],
                'dob'               => ['required', 'date_format:Y-m-d'],
                'gender'            => ['required', 'boolean'],
                'blood_type'        => ['required', 'string', 'in:A,B,AB,O'],
                'address'           => ['required', 'string', 'min:15'],
                'identity_photo'    => ['nullable', 'image', 'mimes: jpg, jpeg, png', 'max:5024']
            ]);
    }


    /**
      @OA\get(
          path="/api/v1/patient/index",
          tags={"Data Patient"},
          summary="get list patient",
          operationId="getmydata",
          security={ {"bearerAuth": {}}, },

         @OA\Response(response="default", description="successful operation")
      )
    */

    function index()
    {
      $data = Patient::where('auth_id', Auth::id())->get();

      return response()->json($data, ResponseCodeEnum::Success);
    }

    /**
      @OA\get(
          path="/api/v1/patient/show",
          tags={"Data Patient"},
          summary="get current data",
          operationId="getmydata",
          security={ {"bearerAuth": {}}, },

         @OA\Response(response="default", description="successful operation")
      )
    */

    function show()
    {
    	$data = Patient::where('auth_id', Auth::id())
            ->orderBy('created_at', 'asc')
            ->first();

    	return response()->json($data, ResponseCodeEnum::Success);
    }

    /**
      @OA\Post(
          path="/api/v1/patient/store",
          tags={"Data Patient"},
          summary="Save my data patient",
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
    function store(Request $request)
    {
 		if ($this->validator->fails()) {
       		return response()->json([
       		    'error' => $this->validator->errors()
            ], ResponseCodeEnum::UnAuthorized);
    	}

 		try {
 		    DB::beginTransaction();

 		    $data = [
                'full_name'         => $request->full_name,
                'mother_name'       => $request->mother_name,
                'identity_number'   => $request->identity_number ?? null,
                'dob'               => $request->dob,
                'gender'            => $request->gender,
                'blood_type'        => $request->blood_type,
                'address'           => $request->address,
                'identity_photo'    => $request->identity_photo ?? null,
                'auth_id'           => Auth::user()->id,
            ];

 		    Patient::create($data);

 		    DB::commit();
        } catch (\Exception $e) {
 		    DB::rollBack();
            return response()->json([
                'error' => $this->validator->errors()
            ], ResponseCodeEnum::UnAuthorized);
        }

        return response()->json([
            'success' => true,
            'message' => 'Success Save Data Patient',
            'data' => $data
        ], ResponseCodeEnum::Success);
    }

    /**
    @OA\Post(
    path="/api/v1/patient/update/{patient_id}",
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
    @OA\Property(property="identity_photo", type="string")
    )
    )
    ),

    @OA\Response(response="default", description="successful operation")
    )
     */
    public function update(Request $request, $patient_id)
    {
        if ($this->validator->fails()) {
            return response()->json([
                'error' => $this->validator->errors()
            ], ResponseCodeEnum::UnAuthorized);
        }

        try {
            DB::beginTransaction();

            $data = [
                'full_name'         => $request->full_name,
                'mother_name'       => $request->mother_name,
                'identity_number'   => $request->identity_number ?? null,
                'dob'               => $request->dob,
                'gender'            => $request->gender,
                'blood_type'        => $request->blood_type,
                'address'           => $request->address,
                'identity_photo'    => $request->identity_photo ?? null
            ];

            Patient::where([
                    'id'        => $patient_id,
                    'auth_id'   => Auth::user()->id
                ])
                ->update($data);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => $this->validator->errors()
            ], ResponseCodeEnum::UnAuthorized);
        }

        return response()->json([
            'success' => true,
            'message' => 'Success Update Data Patient',
            'data' => $data
        ], ResponseCodeEnum::Success);
    }
}
