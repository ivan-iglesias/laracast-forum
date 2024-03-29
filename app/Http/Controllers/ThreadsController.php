<?php

namespace App\Http\Controllers;

use App\Filters\ThreadFilters;
use App\Channel;
use App\Thread;
use Illuminate\Http\Request;

class ThreadsController extends Controller
{
    /**
     * ThreadsController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
        // $this->middleware('auth')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @param  Channel      $channel
     * @param ThreadFilters $filters
     * @return \Illuminate\Http\Response
     */
    public function index(Channel $channel, ThreadFilters $filters)
    {
        $threads = $this->getThreads($channel, $filters);

        if (request()->wantsJson()) {
            return $threads;
        }

        return view('threads.index', compact('threads'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('threads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
            'channel_id' => 'required|exists:channels,id'
        ]);

        $thread = Thread::create([
            'user_id' => auth()->id(),
            'channel_id' => request('channel_id'),
            'title' => request('title'),
            'body' => request('body')
        ]);

        return redirect($thread->path());
    }

    /**
     * Display the specified resource.
     *
     * @param  integer     $channel
     * @param  \App\Thread $thread
     * @return \Illuminate\Http\Response
     */
    public function show($channel, Thread $thread)
    {
        // return $thread->load('replies.favorites')->load('replies.owner');

        // Formas de saber el numero de comentarios de un articulo.
        // 1. return $thread->load('replies');
        // 2. return Thread::withCount('replies')->find(51);
        // 3. Crear la funcion getReplyCountAttribute en la clase Thread y llamar a "$thread->replies()->count()"
        // 4. Crear un GlobalScope en la clase thread que devuelva la cuenta para todas las consultas.
        return view('threads.show', [
            'thread'  => $thread,
            'replies' => $thread->replies()->paginate(20)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function edit(Thread $thread)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Thread $thread)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  integer     $channel
     * @param  \App\Thread $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy($channel, Thread $thread)
    {
        // Lo reemplazo por ThreadPolicy, generado con el comando:
        // php artisan make:policy ThreadPolicy --model=Thread
        /* if ($thread->user_id != auth()->id()) {
            abort(403, 'You do not have permission to do this.');
        } */

        $this->authorize('update', $thread);

        // Si deseamos controlar el borrado de los comentarios a nivel
        // de PHP y no base de datos, podemos borrar aqui los comentarios
        // o mediante "static::deleting" en el modelo "Thread".
        // $thread->replies()->delete();
        $thread->delete();

        if (request()->wantsJson()) {
            return response([], 204);
        }

        return redirect('/threads');
    }

    /**
     * Fetch all relevant threads.
     * 
     * @param  Channel       $channel
     * @param  ThreadFilters $filters
     * @return mixed
     */
    public function getThreads(Channel $channel, ThreadFilters $filters)
    {
        $threads = Thread::latest()->filter($filters);

        if($channel->exists) {
            $threads->where('channel_id', $channel->id);
        }

        // dd($threads->toSql());

        return $threads->get(); 
    }
}
