<?php

namespace App\Models;

use App\Libs\UUIDGenerator;
use Illuminate\Database\Eloquent\Model;

class Insurance extends Model
{
    //
    use UUIDGenerator;

    protected $table = "insurance";
    public $incrementing = false;
    public $timestamps = false;

    public function hospital()
    {
        return $this->belongsTo(Hospital::class, 'hospital_id');
    }
}
