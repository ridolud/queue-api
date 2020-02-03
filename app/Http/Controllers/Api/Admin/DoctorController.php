<?php

namespace App\Http\Controllers\Api\Admin;

use App\Enums\ListDataEnum;
use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = Doctor::paginate(ListDataEnum::TotalItemPerRequest);

        return response()->json($doctors);
    }

}
