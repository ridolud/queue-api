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

    /**
     * variable that contain the selected column
     * @var array
     */
    private $columns = [
        'queue_process.id as queue_id',
        'user_id',
        'patient_id',
        'doctor_schedule_id',
        'is_valid',
        'submit_time',
        'insurance_id',
        'process_status',
        'doctor_schedule.*',
        'hospital.*',
        'poli.*',
        'patient.*',
        'patient.full_name as patient_fullname',
        'hospital.full_name as hospital_fullname',
        'insurance.full_name as insurance_fullname',
        'doctor.full_name as doctor_fullname',
        'poli.full_name as poli_fullname',
        'is_valid as queue_remaining'
    ];

    /**
     * @param $query
     * @return mixed
     */
    public function scopeSelectedColumn($query)
    {
        return $query->select($this->columns);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeHospital($query)
    {
        return $query->leftJoin('doctor_schedule', 'doctor_schedule.id', 'doctor_schedule_id')
            ->leftJoin('patient', 'patient.id', 'patient_id')
            ->leftJoin('doctor', 'doctor.id', 'doctor_schedule.doctor_id')
            ->leftJoin('hospital', 'hospital.id', 'doctor.hospital_id')
            ->leftJoin('poli', 'poli.id', 'doctor.poli_id')
            ->leftJoin('insurance', 'insurance.id', 'insurance_id');
    }

    /**
     * handle empty value in insurance_id attribute
     * @return string
     */
    public function getInsuranceIdAttribute()
    {
        if (empty($this->insurance_id)) {
            return "";
        }
    }

    /**
     * relation to insurance
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function insurance()
    {
        return $this->belongsTo(Insurance::class, 'insurance_id');
    }

    /**
     * relation to doctor schedule
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function schedule()
    {
        return $this->belongsTo(DoctorSchedule::class, 'doctor_schedule_id');
    }

    /**
     * handle null value at insurance fullname
     * @return string
     */
    public function getInsuranceFullnameAttribute()
    {
        if (empty($this->insurance_full_name)) {
            return "";
        }
    }

    /**
     * set attribute queue_remaining
     * @param $value
     */
    public function setQueueRemainingAttribute($value)
    {
        $this->attributes["queue_remaining"] = $value;
    }

    /**
     * Relation to Log Process Queue
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function log()
    {
        return $this->hasMany(QueueProcessLog::class, 'queue_process_id');
    }

    /**
     * relation to user model
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function doctor()
    {
        return $this->hasOneThrough(
            Doctor::class,
            DoctorSchedule::class,
            'doctor_id',
            'id',
            'id',
            'doctor_id'
        );
    }

}
