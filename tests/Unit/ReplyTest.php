<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReplyTest extends TestCase
{
	use DatabaseMigrations;
	
	// php artisan make:test ReplyTest --unit
	// 
	// ./vendor/bin/phpunit tests/Unit/ReplyTest.php
	// ./vendor/bin/phpunit --filter a_thread_has_a_creator
	
    /** @test */
    public function it_has_an_owner()
    {
		$reply = factory('App\Reply')->create();
		$this->assertInstanceOf('App\User', $reply->owner);
    }
}
