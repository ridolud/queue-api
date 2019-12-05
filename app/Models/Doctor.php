<?php

namespace App\Models;

use App\Libs\UUIDGenerator;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Doctor
 * @package App
 */
class Doctor extends Model
{
    use UUIDGenerator;

    /**
     * define table
     * @var string
     */
    protected $table = "doctor";
    /**
     * disable incrementing
     * @var bool
     */
    public $incrementing = false;
    /**
     * disable timestamps
     * @var bool
     */
    public $timestamps = false;

    /**
     * define column primary key
     * @var string
     */
    protected $primaryKey = "id";
    /**
     * cast data type in table model
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'doctor_id' => 'string'
    ];

    /**
     * Relation to hospital model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    /**
     * Relation to poli clinic model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function poli()
    {
        return $this->belongsTo(PoliClinic::class, 'poli_id');
    }

    /**
     * Relation to doctor schedule
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function schedule()
    {
        return $this->hasOne(DoctorSchedule::class, 'doctor_id', 'id');
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeDoctorSchedule($query)
    {
        return $query->leftJoin('doctor_schedule as schedule', 'schedule.doctor_id', 'doctor.id');
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeAvailableSchedule($query)
    {
        return $query->selectRaw("array(select * from doctor_schedule where doctor_schedule.doctor_id = doctor_id) as schedule");
    }

}
