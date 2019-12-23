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
}
