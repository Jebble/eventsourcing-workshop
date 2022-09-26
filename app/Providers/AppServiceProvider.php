<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Workshop\Domains\Wallet\Infra\EloquentTransactionsReadModelRepository;
use Workshop\Domains\Wallet\Infra\EloquentWalletsReadModelRepository;
use Workshop\Domains\Wallet\Projectors\TransactionsProjector;
use Workshop\Domains\Wallet\Projectors\WalletsProjector;
use Workshop\Domains\Wallet\Reactors\WalletsReactor;
use Workshop\Domains\Wallet\Tests\InMemoryNotificationService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(TransactionsProjector::class, function () {
            $readModelRepository = new EloquentTransactionsReadModelRepository();
            return new TransactionsProjector($readModelRepository);
        });

        $this->app->bind(WalletsProjector::class, function () {
            $readModelRepository = new EloquentWalletsReadModelRepository();
            return new WalletsProjector($readModelRepository);
        });

        $this->app->bind(WalletsReactor::class, function () {
            $readModelRepository = new EloquentWalletsReadModelRepository();
            $notificationService = new InMemoryNotificationService();
            return new WalletsReactor($readModelRepository, $notificationService);
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
