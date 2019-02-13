<?php

namespace App\Providers\custom;

use App\Http\Controllers\Customers\CustomerController;
use Illuminate\Support\ServiceProvider;

class CustomerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(CustomerController::class, function ($app)
        {
            return new CustomerController();
        });
    }
}
