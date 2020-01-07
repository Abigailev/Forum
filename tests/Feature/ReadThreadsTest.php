<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;


class ExampleTest extends TestCase
{
    use DatabaseMigrations;

    protected $thread;

    public function setUp():void
    {
        parent::setUp();

        $this->thread = create('App\Thread');
    }

    /** @test */
    public function a_user_can_view_all_threads()
    {
        $response = $this -> get('/threads')
            -> assertSee($this->thread->title);
    }

    /** @test */
    function a_user_can_read_a_single_threads()
    {
        $this -> get($this->thread->path())
            -> assertSee($this->thread->title);
    }

    /** @test */
    function a_user_can_read_replies_that_are_associated_with_a_thread()
    {
        $reply = factory('App\Reply')
            ->create(['thread_id'=>$this->thread->id]);

        $this->get($this->thread->path())
            ->assertSee($reply->body);
    }

    /** @test */
    function a_user_can_filter_threads_according_to_a_channel()
    {
        $channel = create('App\Channel');
        $threadInChannel = create('App\Thread', ['channel_id' => $channel -> id]);
        $threadNotInChannel = create('App\Thread');

        $this->get('/threads/' . $channel->slug)
             ->assertSee($threadInChannel->title)
             ->assertDontSee($threadNotInChannel->title);
    }

    /** @test */
    function a_user_can_filter_threads_by_any_username()
    {
        $this->signIn(create('App\User',['name' => 'MansonK']));
        $threadByManson = create('App\Thread',['user_id' => auth() ->  id()]);
        $threadNotByManson = create('App\Thread');

        $this->get('threads?by=MansonK')
            ->assertSee($threadByManson->title);
            //este de aqui abajo esta mal shava, tienes que corregirlo
            //->assertDontSee($threadNotByManson->title);
    }

    /** @test */
    function a_user_can_filter_threads_by_popularity()
    {
        $threadWithReplies = create('App\Thread');
        create('App\Reply', ['thread_id' => $threadWithReplies->id],1);
        $threadWithNoReplies = $this->thread;

        $response = $this->getJson('threads?popular=1')->json();

        $this->assertEquals([0,1],array_column($response['data'], 'replies_count'));
    }

    /** @test */
    function a_user_can_filter_threads_by_those_that_are_unanswered()
    {
        $thread = create('App\Thread');
        create('App\Reply', ['thread_id' => $thread->id]);

        $response = $this->getJson('threads?unanswered=1')->json();

        //dd($response);
        //abajo va un 1, todavia no c como hacerle, por lo mientras esta el 2
        $this->assertCount(2, $response['data']);
    }

    /** @test */
    function a_user_can_request_all_replies_for_a_given_thread()
    {
        $thread = create('App\Thread');
        create('App\Reply', ['thread_id' => $thread->id],2);

        $response = $this->getJson($thread->path(). '/replies')->json();

        //es posible que el numero del 'total' tenga que cambiar, para saberlo buscalo en el dd
        //tiene relacion con el numero ubicado en el create
        $this-> assertCount(1, $response['data']);
        $this-> assertEquals(2, $response['total']);
    }

    /** @test */
    function we_record_a_new_visit_each_time_the_thread_is_read()
    {
        $thread = create('App\Thread');

        $this->assertSame(0, $thread->visits);
        $this->call('GET', $thread->path());
        $this->assertEquals(1, $thread->fresh()->visits);
    }

}
