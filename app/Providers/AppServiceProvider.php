<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use App\Models\OilPrice;
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
        // oil price component
        View::composer('components.oilprice', function ($view) {
            $OilPrice = OilPrice::latest()->first();
            $view->with('OilPrice', $OilPrice);
        });
    }
}
