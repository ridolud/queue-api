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
        'doctor_id' => 'string',
    ];

    /**
     * @var string
     */
    protected $primaryKey = "id";

    /**
     * Convert format H:i:s to H:i
     *
     * @param $value
     * @return string
     */
    public function getTimeStartAttribute($value)
    {
        return $this->convertTime($value);
    }

    /**
     * Convert format H:i:s to H:i
     *
     * @param $value
     * @return string
     */
    public function getTimeEndAttribute($value)
    {
        return $this->convertTime($value);
    }

    /**
     * Method to convert format H:i:s to H:i
     *
     * @param $time
     * @return string
     */
    private function convertTime($time)
    {
        return Carbon::create($time)->format('H:i');
    }

}
