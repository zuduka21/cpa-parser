<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ParserServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        require_once app_path().'/Helpers/ParserFieldEmpty.php';
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
