<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
	protected $guarded = [];
	
    public function owner()
    {
    	// Especifico el user_id porque llamo a la relacion onwer en vez de user.
    	return $this->belongsTo(User::class, 'user_id');
    }
}
