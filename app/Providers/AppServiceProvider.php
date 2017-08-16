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
            // Si no lo guardo en el cache, se ejecuta dos veces la query, como
            // son datos que no van a variar mucho, lo almaceno en la cache. Si
            // no esta en el cache, los recupera de la base de datos.
            $channels = \Cache::rememberForever('channels', function() {
                return Channel::all();
            });

            $view->with('channels', $channels);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // No lo añado en config/app.php porque solo nos interesa en desarollo.
        if ($this->app->isLocal()) {
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
        }
    }
}
