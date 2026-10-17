<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Listeners\MessageSendingListener;
use Illuminate\Mail\Events\MessageSending;

use SocialiteProviders\Manager\SocialiteWasCalled;
use SocialiteProviders\Apple\AppleExtendSocialite;


class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\SomeEvent' => [
            'App\Listeners\EventListener',
        ],
        MessageSending::class => [
            MessageSendingListener::class
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
      parent::boot();
      \Event::listen(SocialiteWasCalled::class, function (SocialiteWasCalled $event) {
          $event->extendSocialite('apple', AppleExtendSocialite::class);
      });
    }
}
