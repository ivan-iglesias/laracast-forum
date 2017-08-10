<?php

namespace App\Providers;

use App\Channel;
use Illuminate\Support\Facades\Schema;
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
        Schema::defaultStringLength(191);

        // If we have too many shared variables, we can make a dedicate service provider with:
        // php artisan make:provider ViewServiceProvider
        
        /* Make the channels available everywhere
        \View::composer('threads.create', function($view) { $view->with('channels', \App\Channel::all()); }
        \View::composer(['threads.create', 'layouts.app'], function($view) { ... }
        \View::composer('*', function($view) { ... });
        */
        \View::share('channels', Channel::all());

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
