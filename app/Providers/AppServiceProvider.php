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

        // Si tenemos muchas variables podemos crear un ServiceProvider dedicado a ello:
        // php artisan make:provider ViewServiceProvider
        
        /* channels es visible en todas las paginas.
        \View::composer('threads.create', function($view) { $view->with('channels', \App\Channel::all()); }
        \View::composer(['threads.create', 'layouts.app'], function($view) { ... }
        \View::composer('*', function($view) { ... });
        
        Hace lo mismo que con el *, pero a nivel de test falla por que se ejecuta antes
        de realizar la migracion de las pruebas "use DatabaseMigrations;":
        \View::share('channels', Channel::all());
        */

        \View::composer('*', function($view) {
            $view->with('channels', \App\Channel::all());
        });
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
