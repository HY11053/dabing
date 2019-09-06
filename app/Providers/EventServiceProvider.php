<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\PhoneEvent' => [
            'App\Listeners\PhoneEventListener',
        ],
        'App\Events\SitemapEvent' => [
            'App\Listeners\SitemapEventListener',
        ],
        'App\Events\ArticleCacheCreateEvent' => [
            'App\Listeners\ArticleCacheCreateEventListener',
        ],
        'App\Events\ArticleCacheDeleteEvent' => [
            'App\Listeners\ArticleCacheDeleteEventListener',
        ],
        'App\Events\BrandArticleCacheCreateEvent' => [
            'App\Listeners\BrandArticleCacheCreateEventListener',
        ],
        'App\Events\BrandArticleCacheDeleteEvent' => [
            'App\Listeners\BrandArticleCacheDeleteEventListener',
        ],
        'App\Events\CategoryCacheCreateEvent' => [
            'App\Listeners\CategoryCacheCreateEventListener',
        ],
        'App\Events\CategoryCacheDeleteEvent' => [
            'App\Listeners\CategoryCacheDeleteEventListener',
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

        //
    }
}
