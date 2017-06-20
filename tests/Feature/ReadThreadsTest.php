<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ReadThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /*
    Para ejecutar las pruebas:
    ./vendor/bin/phpunit

    En el fichero phpunit.xml, he añadido:
    <env name="DB_CONNECTION" value="sqlite"/>
    <env name="DB_DATABASE" value=":memory:"/>

    El string especial :memory: indica que guarde los datos
    en memoria en vez de un fichero.
     */
    
    public function setUp()
    {
        // Como extendentos de TestCase, no aseguramos que llamemos al setUp del padre.
        parent::setUp();
        $this->thread = create('App\Thread');
    }

    /** @test */
    public function a_user_can_view_all_threads()
    {
        $response = $this->get('/threads');
        $response->assertSee($this->thread->title);
    }

    /** @test */
    public function a_user_can_read_a_single_thread()
    {
        $this->get($this->thread->path())
            ->assertSee($this->thread->title);
    }

    /** @test */
    public function a_user_can_read_replies_that_are_associated_with_a_thread()
    {
        // $reply = factory('App\Reply')->create(['thread_id' => $this->thread->id]);
        $reply = create('App\Reply', ['thread_id' => $this->thread->id]);
        
        $this->get($this->thread->path())
            ->assertSee($reply->body);
    }
}
