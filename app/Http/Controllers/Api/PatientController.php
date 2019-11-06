<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use App\Patient;
use Illuminate\Support\Facades\Auth;
use Validator;

class PatientController extends Controller
{

	public $successStatus = 200;

    function getMyData()
    {


    	$data = Patient::where('auth_id', Auth::id())->first();	

    	return response()->json([
    		'data' => $data,
            'status' => '',
            'code' => '',
    	], $this->successStatus);
    }

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
       		return response()->json(['error'=>$validator->errors()], 401);                       
    	}

    	$input = $request->all();  
	 	$input['auth_id'] = Auth::id();
	 	$data = Patient::create($input); 

	 	return response()->json(['data'=>$data], $this->successStatus); 
    }
}
