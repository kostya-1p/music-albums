<?php

namespace App\Providers;

use App\Repositories\AlbumRepository;
use App\Repositories\ArtistRepository;
use App\Repositories\Interfaces\AlbumRepositoryInterface;
use App\Repositories\Interfaces\ArtistRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            AlbumRepositoryInterface::class,
            AlbumRepository::class
        );

        $this->app->bind(
            ArtistRepositoryInterface::class,
            ArtistRepository::class
        );
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
