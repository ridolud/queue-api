<?php

namespace App\Models;

use App\Libs\UUIDGenerator;
use Illuminate\Database\Eloquent\Model;

class QueueEstimationTime extends Model
{
    use UUIDGenerator;

    /**
     * @var string
     */
    protected $table = "queue_estimation_time";
    /**
     * @var bool
     */
    public $incrementing = false;
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * column that could filled
     * @var array
     */
    protected $fillable = [
        'doctor_schedule_id',
        'estimation'
    ];

}
