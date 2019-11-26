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
     * @var string
     */
    protected $table = "doctor";
    /**
     * @var bool
     */
    public $incrementing = false;
    /**
     * @var bool
     */
    public $timestamps = false;

    protected $primaryKey = "id";
    protected $casts = [
      'id' => 'string'
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
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function schedule()
    {
        return $this->hasOne(DoctorSchedule::class, 'doctor_id', 'id');
    }

    public function scopeDoctorSchedule($query)
    {
        return $query->leftJoin('doctor_schedule as schedule', 'schedule.doctor_id', 'doctor.id');
    }

}
