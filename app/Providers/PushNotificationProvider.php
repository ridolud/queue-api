<?php

namespace App\Providers;

use Edujugon\PushNotification\PushNotification;
use Illuminate\Support\ServiceProvider;

class PushNotificationProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(PushNotification::class, function ($app) {
            return new PushNotification('apn');
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

    }
}
