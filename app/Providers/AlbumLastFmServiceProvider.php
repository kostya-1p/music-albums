<?php

namespace App\Providers;

use App\Services\AlbumLastFmService;
use Illuminate\Support\ServiceProvider;

class AlbumLastFmServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->scoped(AlbumLastFmService::class, function ($app) {
            return new AlbumLastFmService(config('services.lastfm_api.domain'),
                config('services.lastfm_api.api_key'),
                config('services.lastfm_api.api_version'));
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
