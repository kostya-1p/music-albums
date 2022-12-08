<?php

namespace App\Providers;

use App\Services\AlbumLastFmService;
use App\Services\ArtistLastFmService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(AlbumLastFmService::class, function ($app) {
            return new AlbumLastFmService(
                config('services.lastfm_api.domain'),
                config('services.lastfm_api.api_key'),
                config('services.lastfm_api.api_version')
            );
        });

        $this->app->bind(ArtistLastFmService::class, function ($app) {
            return new ArtistLastFmService(
                config('services.lastfm_api.domain'),
                config('services.lastfm_api.api_key'),
                config('services.lastfm_api.api_version')
            );
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
