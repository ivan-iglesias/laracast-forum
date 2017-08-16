<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use Favoritable;

	/**
     * Don't auto-apply mass assignment protection.
     *
     * @var array
     */
	protected $guarded = [];

    protected $with = ['owner', 'favorites'];
	
	/**
     * A reply has an owner.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
    	// Especifico el user_id porque llamo a la relacion onwer en vez de user.
    	return $this->belongsTo(User::class, 'user_id');
    }
}
