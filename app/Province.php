<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    //
    protected $table = "indonesia_provinces";
    protected $casts = [
        'id' => 'integer'
    ];
    protected $hidden = [
        'id'
    ];
}
