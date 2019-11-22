<?php

namespace App;

use Hafael\LaraFlake\Traits\LaraFlakeTrait;
use Illuminate\Database\Eloquent\Model;
use App\Libs\UUIDGenerator;

class PoliClinic extends Model
{
    use UUIDGenerator;

    protected $table = "poli";
    protected $fillable = [
        "full_name",
        "hospital_name"
    ];
    public $incrementing = false;
    public $timestamps = false;

    public function hospital()
    {
        return $this->belongsTo(Hospital::class, 'hospital_id');
    }
}
