<?php

namespace App;

use Hafael\LaraFlake\Traits\LaraFlakeTrait;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
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
