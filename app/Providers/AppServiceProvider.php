<?php

namespace App\Providers;

use App\Models\Customers\Customer;
use App\Models\Orders\Order;
use App\Policies\Customers\CustomerPolicy;
use App\Policies\Orders\OrderPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Customer::class, CustomerPolicy::class);
        Gate::policy(Order::class, OrderPolicy::class);
    }
}
