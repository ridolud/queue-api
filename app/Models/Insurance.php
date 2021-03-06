<?php

namespace App\Models;

use App\Libs\UUIDGenerator;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Insurance
 * @package App\Models
 */
class Insurance extends Model
{
    //
    use UUIDGenerator;

    /**
     * define table
     * @var string
     */
    protected $table = "insurance";
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
     * Relation hospital
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function hospital()
    {
        return $this->belongsTo(Hospital::class, 'hospital_id');
    }
}
