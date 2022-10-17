<?php

namespace App\Providers;

use App\Repositories\AlbumRepository;
use App\Repositories\Interfaces\AlbumRepositoryInterface;
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
