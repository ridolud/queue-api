<?php


namespace App\Libs;


use Webpatser\Uuid\Uuid;

trait UUIDGenerator
{
    /**
     *  Setup model event hooks
     */
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = (string)Uuid::generate(4);
        });
    }
}
