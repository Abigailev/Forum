<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SubscribeToThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_subscribe_to_threads()
    {
        $this->signIn();
        //given we have a thread
        $thread = create('App\Thread');
        //user subscribes to the thread
         $this->post($thread->path() . '/subscriptions');

         //each time a new reply is left...
        $thread->addReply([
           'user_id' => auth()->id(),
            'body' => 'Some reply here'
        ]);

        //notifcation drop
         $this->assertCount(1, auth()->user()->notifications);
    }

    /** @test */
    public function a_user_can_unsubscribe_from_threads()
    {
        $thread = create('App\Thread');
        $thread->subscribe();

        $this->delete($thread->path() . '/subscriptions');

        $this->assertCount(0, $thread->subscriptions);
    }
}
