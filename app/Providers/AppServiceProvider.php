<?php

namespace App\Providers;

use App\Observers\PartnersObserver;
use App\Observers\UsersObserver;
use App\Observers\CountryObserver;
use App\User;
use App\Partner;
use App\Country;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        User::observe(UsersObserver::class);
        Partner::observe(PartnersObserver::class);
        Country::observe(CountryObserver::class);
    }
}
