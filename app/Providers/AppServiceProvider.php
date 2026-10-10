<?php

namespace App\Providers;

use App\Contracts\ContentRepositoryInterface;
use App\Contracts\GenreRepositoryInterface;
use App\Contracts\PlaybackRepositoryInterface;
use App\Contracts\UserRepositoryInterface;
use App\Repositories\ContentRepository;
use App\Repositories\GenreRepository;
use App\Repositories\PlaybackRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class,
        );

        $this->app->bind(
            ContentRepositoryInterface::class,
            ContentRepository::class,
        );

        $this->app->bind(
            GenreRepositoryInterface::class,
            GenreRepository::class,
        );

        $this->app->bind(
            PlaybackRepositoryInterface::class,
            PlaybackRepository::class,
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
