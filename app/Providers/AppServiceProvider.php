<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Notification;
use App\Notifications\Channels\CustomNotificationChannel;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Schema::defaultStringLength(191);

        // Register custom notification channel
        Notification::extend('custom', function ($app) {
            return new CustomNotificationChannel();
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
      $loader = \Illuminate\Foundation\AliasLoader::getInstance();
      $loader->alias('Debugbar', \Barryvdh\Debugbar\Facades\Debugbar::class);
    }
}
