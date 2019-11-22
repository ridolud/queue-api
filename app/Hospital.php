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

    protected $casts = [
        'province_id' => 'integer',
        'city_id' => 'integer'
    ];

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id', 'id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }
}
