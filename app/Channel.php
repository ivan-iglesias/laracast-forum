<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    /**
     * Get the route key name for Laravel.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        /* override
         * Por defecto, el enlazado con el modelo de laravel intentara obtener el canal por su id,
         * por lo que podemos sobreescribir el metodo e indicar que lo buque por el campo slug".
         * $this->get('/threads/' . $channel->slug)
         */
    	return 'slug';
    }

    /**
     * A channel consists of threads.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function threads()
    {
    	return $this->hasMany(Thread::class);
    }
}
