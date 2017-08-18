<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateThreadsTest extends TestCase
{
	use DatabaseMigrations;

	/** @test */
	public function guest_may_not_create_threads()
	{
		// $this->expectException('Illuminate\Auth\AuthenticationException');
		// $thread = make('App\Thread');
		// $this->post('/threads', $thread->toArray());

		$this->withExceptionHandling();

		$this->get('/threads/create')
			->assertRedirect('/login');

		$this->post('/threads')
			->assertRedirect('/login');
	}

	/** @test
	 *
	 * Se unifica con guest_may_not_create_threads
	 *
	public function guest_cannot_see_the_create_thread_page()
	{
		$this->withExceptionHandling()
			->get('/threads/create')
			->assertRedirect('/login');
	}
	*/

    /** @test */
    public function an_authenticated_user_can_create_new_forum_thread()
    {
        // $this->actingAs(factory('App\User')->create());
        $this->signIn();

		// Uso 'make' porque quiero una instancia de 'Thread'
		// en vez de un array para poder llamar a 'path()'
		// $thread = factory('App\Thread')->raw();
		// $this->post('/threads', $thread);

        $thread = make('App\Thread');
        $response = $this->post('/threads', $thread->toArray());

        // Antes tenia "$thread->path()"
        $this->get($response->headers->get('Location'))
			 ->assertSee($thread->title)
        	 ->assertSee($thread->body);
    }

    /** @test */
    public function a_thread_requires_a_title()
    {
    	$this->publishThread(['title' => null])
    		->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_thread_requires_a_body()
    {
    	$this->publishThread(['body' => null])
    		->assertSessionHasErrors('body');
    }

    /** @test */
    public function a_thread_requires_a_valid_channel()
    {
    	factory('App\Channel', 2)->create();

    	$this->publishThread(['channel_id' => null])
    		->assertSessionHasErrors('channel_id');

    	$this->publishThread(['channel_id' => 999])
    		->assertSessionHasErrors('channel_id');
    }

    /** @test */
    public function unauthorized_users_may_not_delete_threads()
    {
        $this->withExceptionHandling();

        $thread = create('App\Thread');

        $this->delete($thread->path())->assertRedirect('/login');

        $this->signIn();

        $this->delete($thread->path())->assertStatus(403);
    }

    /** @test */
    public function authorized_users_can_delete_threads()
    {
        $this->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        $reply = create('App\Reply', ['thread_id' => $thread->id]);

        $response = $this->json('DELETE', $thread->path());

        $response->assertStatus(204);

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
    }

    public function publishThread($overrides = [])
    {
		$this->withExceptionHandling()->signIn();

    	$thread = make('App\Thread', $overrides);

    	return $this->post('/threads', $thread->toArray());
    }
}
