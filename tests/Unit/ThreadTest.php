<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ThreadTest extends TestCase
{
	use DatabaseMigrations;

    protected $thread;

    // ./vendor/bin/phpunit --filter ThreadTest
    
    public function setUp()
    {
        parent::setUp();
        $this->thread = create('App\Thread');
    }

    /** @test */
    public function a_thread_can_make_a_string_path()
    {
        $this->assertEquals(
            "/threads/{$this->thread->channel->slug}/{$this->thread->id}", $this->thread->path()
        );
    }

    /** @test */
    public function a_thread_has_a_creator()
    {
    	$this->assertInstanceOf('App\User', $this->thread->creator);
    }

    /** @tests */
    public function a_thread_has_replies()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->thread->replies);
    }

    /** @test */
    public function a_thread_can_add_a_reply()
    {
        $this->thread->addReply([
            'body' => 'Foobar',
            'user_id' => 1
        ]);

        $this->assertCount(1, $this->thread->replies);
    }

    /** @tests */
    public function a_thread_belongs_to_a_chanel()
    {
        $this->assertInstanceOf('App\Channel', $this->thread->channel);
    }
}
