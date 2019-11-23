<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    //
    protected $table = "indonesia_provinces";

    protected $hidden = [
        'id'
    ];
}
