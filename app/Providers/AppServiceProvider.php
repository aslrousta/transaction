<?php

namespace App\Providers;

use App\Services\Transaction\OptimistTransactionManager;
use App\Services\Transaction\PessimistTransactionManager;
use App\Services\Transaction\TransactionManagerInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(TransactionManagerInterface::class, function () {
            return env('TRANSACTION_USE_LOCKS')
                ? new PessimistTransactionManager()
                : new OptimistTransactionManager();
        });
    }
}
