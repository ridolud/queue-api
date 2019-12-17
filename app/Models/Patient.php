<?php

namespace App\Models;

use App\Libs\UUIDGenerator;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Patient
 * @package App
 */
class Patient extends Model
{
    /**
     * define table
     * @var string
     */
    protected $table = 'patient';

	 /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'full_name',
        'mother_name',
        'identity_number',
        'dob',
        'gender',
        'blood_type',
        'address',
        'auth_id',
    ];

}
