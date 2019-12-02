<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Province
 * @package App\Models
 */
class Province extends Model
{
    /**
     * define table
     * @var string
     */
    protected $table = "indonesia_provinces";

    /**
     * @var array
     */
    protected $hidden = [
        'id'
    ];
}
