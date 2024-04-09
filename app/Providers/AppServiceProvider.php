<?php

namespace App\Providers;

use App\src\Domain\Cache\CacheInterface;
use App\src\Domain\Repositories\PointRepositoryInterface;
use App\src\Domain\Services\PointsService;
use App\src\Infrastructure\Cache\RedisCache;
use App\src\Infrastructure\Persistence\Eloquent\PointRepositoryEoloquent;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            PointRepositoryInterface::class,
            PointRepositoryEoloquent::class
        );

        $this->app->bind(
            CacheInterface::class,
            RedisCache::class
        );

        $this->app->singleton(
            PointsService::class,
            function ($app) {
                return new PointsService(
                    $app->make(PointRepositoryInterface::class),
                    $app->make(CacheInterface::class)
                );
            }
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
