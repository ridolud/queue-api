<?php

namespace App\Models;

use App\Libs\UUIDGenerator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DoctorSchedule
 * @package App
 */
class DoctorSchedule extends Model
{
    //
    use UUIDGenerator;

    /**
     * @var string
     */
    protected $table = "doctor_schedule";
    /**
     * @var bool
     */
    public $incrementing = false;
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'doctor_id' => 'string'
    ];

    /**
     * @var string
     */
    protected $primaryKey = "id";

    public function getTimeStartAttribute($value)
    {
        return $this->convertTime($value);
    }

    public function getTimeEndAttribute($value)
    {
        return $this->convertTime($value);
    }

    private function convertTime($time)
    {
        return Carbon::create($time)->format('H:i');
    }

}
