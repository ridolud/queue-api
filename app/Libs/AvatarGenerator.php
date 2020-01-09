<?php

namespace App\Libs;

use App\Enums\AssetPathEnum;
use Laravolt\Avatar\Avatar;
use Webpatser\Uuid\Uuid;

trait AvatarGenerator {

    /**
     * generate avatar using initial name
     */
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $file_name = Uuid::generate(4) . '.png';
            $avatar = new Avatar();
            $avatar->create($model->name)->save(public_path(AssetPathEnum::AVATAR_USER_PATH . $file_name));
            $model->avatar = $file_name;
        });
    }
}
