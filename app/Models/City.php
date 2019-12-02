<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class City
 * @package App\Models
 */
class City extends Model
{
    /**
     * define table
     * @var string
     */
    protected $table = "indonesia_cities";

    /**
     * hide id from get method
     * @var array
     */
    protected $hidden = [
        'id'
    ];
}
