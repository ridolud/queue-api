<?php

namespace App;

use App\Libs\UUIDGenerator;
use Hafael\LaraFlake\Traits\LaraFlakeTrait;
use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    use UUIDGenerator;

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
    public $timestamps = false;
}
