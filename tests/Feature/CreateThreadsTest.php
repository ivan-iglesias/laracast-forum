<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateThreadsTest extends TestCase
{
	use DatabaseMigrations;

	// ./vendor/bin/phpunit --filter CreateThreadsTest

	/** @test */
	public function guest_may_not_create_threads()
	{
		$this->expectException('Illuminate\Auth\AuthenticationException');
		
		// $thread = factory('App\Thread')->make();
		$thread = make('App\Thread');
		
		$this->post('/threads', $thread->toArray());
	}

	/** @test */
	public function guest_cannot_see_the_create_thread_page()
	{
		$this->withExceptionHandling()
			->get('/threads/create')
			->assertRedirect('/login');
	}

    /** @test */
    public function an_authenticated_user_can_create_new_forum_thread()
    {
		// Given we have a signed user
        // $this->actingAs(factory('App\User')->create());
        $this->signIn();

        // When we hit the endpoint to create a new thread
        //$thread = factory('App\Thread')->make();
        $thread = make('App\Thread');

        $this->post('/threads', $thread->toArray());

	        // Uso 'make' porque quiero una instancia de 'Thread'
	        // en vez de un array para poder llamar a 'path()'
	        // $thread = factory('App\Thread')->raw();
	        // $this->post('/threads', $thread);

        // Then, when we visit the thread page and we should see the new thread
        $this->get($thread->path())
			 ->assertSee($thread->title)
        	 ->assertSee($thread->body);
    }
}
