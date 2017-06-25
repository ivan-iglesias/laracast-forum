<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ParticipateInForumTest extends TestCase
{
	use DatabaseMigrations;
    
    /** @test */
    public function unauthenticated_user_may_not_add_replies()
    {
    	// $this->expectException('Illuminate\Auth\AuthenticationException');
        // $this->post('/threads/some-channel/1/replies', []);

        $this->withExceptionHandling()
            ->post('/threads/some-channel/1/replies', [])
            ->assertRedirect('/login');
    }

    /** @test */
    public function an_authenticated_user_may_participate_in_forum_threads()
    {
    	// Given we have an authenticated user
        $this->signIn();

        // And an existing thread
        $thread = create('App\Thread');

        // When the user adds a reply to the thread
        // A dia de hoy, auque no exista la ruta no lanzara una excepcion.
        // Hacemos el arreglo en "app/Exceptions/Handler.php", funcion "render".
        // 
        // En la leccion 8 se elimina el cambio y se añade en TestCase el codigo
        // de Adam Watham para permitir lanzar o no excepcion.
        $reply = make('App\Reply');
        $this->post($thread->path() . '/replies', $reply->toArray());

        // Then their reply should be visible on the page
        $this->get($thread->path())
             ->assertSee($reply->body);
    }

    /** @test */
    public function a_reply_requires_a_body()
    {
        $this->withExceptionHandling()->signIn();

        $thread = create('App\Thread');
        $reply = make('App\Reply', ['body' => null]);
        
        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertSessionHasErrors('body');
    }

}
