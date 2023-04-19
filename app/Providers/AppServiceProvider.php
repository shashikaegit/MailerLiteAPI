<?php

namespace App\Providers;

use App\Repositories\Subscriber\ExternalApiSubscriberRepository;
use App\Repositories\Subscriber\SubscriberRepositoryInterface;
use App\Responses\Response;
use App\Responses\ResponseInterface;
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
        $this->app->bind(SubscriberRepositoryInterface::class, ExternalApiSubscriberRepository::class);
        $this->app->bind(ResponseInterface::class, Response::class);
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
