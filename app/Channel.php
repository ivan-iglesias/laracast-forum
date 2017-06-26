<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    /**
     * override
     * Por defecto, el enlazado con el modelo de laravel intentara
     * obtener el canal por su id, por lo que podemos sobreescribir
     * el metodo e indicar que lo buque por el campo slug".
     * $this->get('/threads/' . $channel->slug)
     */
    public function getRouteKeyName()
    {
    	return 'slug';
    }

    public function threads()
    {
    	return $this->hasMany(Thread::class);
    }
}
