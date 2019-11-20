<?php

namespace App;

use Hafael\LaraFlake\Traits\LaraFlakeTrait;
use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    use LaraFlakeTrait;

    protected $table = "hospital";
    protected $fillable = [
        "full_name",
        "phone_number",
        "address",
        "latitude",
        "longitude",
        "province_id",
        "city_id",
        "photo"
    ];
}
