<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Libs\UUIDGenerator;

/**
 * Class PoliClinic
 * @package App
 */
class PoliClinic extends Model
{
    use UUIDGenerator;

    /**
     * @var string
     */
    protected $table = "poli";
    /**
     * @var array
     */
    protected $fillable = [
        "full_name",
        "hospital_name"
    ];
    /**
     * @var bool
     */
    public $incrementing = false;
    /**
     * @var string
     */
    protected $primaryKey = "id";
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
        return $this->belongsTo(Hospital::class, 'hospital_id');
    }
}
