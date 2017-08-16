<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    /**
     * Don't auto-apply mass assignment protection.
     *
     * @var array
     */
    protected $guarded = [];

    // Esto se aplica a todas las queries, si para algunas
    // no nos interesa podria usa un global scope, para filtrar
    // los hilos con o si el dueño.
    protected $with = ['creator', 'channel'];
    
    /**
     * Laravel sabe automaicamente cuando ejecutarlo.
     */
    protected static function boot()
    {
        parent::boot();

        // "GlobalScope" es una "query scope" que se aplica
        // automaticamente a todas las consultas.
        static::addGlobalScope('replyCount', function ($builder) {
            $builder->withCount('replies');
        });

        /*
         * App\Thread::first();
         * App\Thread::withoutGlobalScopes()->first();
         * static::addGlobalScope('creator', function ($builder) {
         *    $builder->with('creator');
         * });
        */
    }

    /**
     * Get a string path for the thread.
     * 
     * @return string
     */
    public function path()
    {
    	return "/threads/{$this->channel->slug}/{$this->id}";
    }

    /**
     * A thread belongs to a creator.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
    	return $this->belongsTo(User::Class, 'user_id');
    }

    /**
     * A thread is assigned a channel.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function channel()
    {
        return $this->belongsTo(Channel::Class);
    }

    /**
     * A thread may have many replies.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies()
    {
        return $this->hasMany(Reply::Class);

        /* Podramos usa un addGlobalScope pero usamos "protected $with = ['owner'];" en Reply.php.
        return $this->hasMany(Reply::Class)
            ->withCount('favorites')
            ->with('owner');
        */
    }

    /**
     * Add a reply to the thread.
     *
     * @param $reply
     */
    public function addReply($reply)
    {
        $this->replies()->create($reply);
    }

    /**
     * Apply all relevant thread filters.
     *
     * @param  Builder       $query
     * @param  ThreadFilters $filters
     * @return Builder
     */
    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }
}
