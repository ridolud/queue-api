<?php

namespace App;

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
        return $this->belongsTo(PoliClinic::class);
    }

}
