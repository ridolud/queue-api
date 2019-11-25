<?php

namespace App;

use App\Libs\UUIDGenerator;
use Hafael\LaraFlake\Traits\LaraFlakeTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Hospital
 * @package App
 */
class Hospital extends Model
{
    use UUIDGenerator;

    /**
     * @var string
     */
    protected $table = "hospital";
    /**
     * @var array
     */
    protected $fillable = [
        "full_name",
        "phone_number",
        "address",
        "latitude",
        "longitude",
        "province_id",
        "city_id",
        "photo"
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
     * Relation to province model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id', 'id');
    }

    /**
     * Relation to city model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }
}
