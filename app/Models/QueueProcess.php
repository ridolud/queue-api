<?php

namespace App\Models;

use App\Libs\UUIDGenerator;
use Illuminate\Database\Eloquent\Model;

/**
 * Class QueueProcess
 * @package App\Models
 */
class QueueProcess extends Model
{
    //
    use UUIDGenerator;

    /**
     * define table
     * @var string
     */
    protected $table = "queue_process";
    /**
     * disable incrementing
     * @var bool
     */
    public $incrementing = false;
    /**
     * disable timestamp
     * @var bool
     */
    public $timestamps = false;
    /**
     * column that could filled
     * @var array
     */
    protected $fillable = [
        "user_id",
        "patient_id",
        "doctor_schedule_id",
        "is_valid",
        "submit_time",
        "insurance_id",
        "process_status"
    ];

    public function scopeHospital($query)
    {
        return $query->leftJoin('doctor_schedule', 'doctor_schedule.id', 'doctor_schedule_id')
            ->leftJoin('doctor', 'doctor.id', 'doctor_schedule.doctor_id')
            ->leftJoin('hospital', 'hospital.id', 'doctor.hospital_id');
    }

}
