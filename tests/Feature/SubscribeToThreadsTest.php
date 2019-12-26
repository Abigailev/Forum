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

         $this->assertCount(1, $thread->fresh()->subscriptions);

    }

    /** @test */
    public function a_user_can_unsubscribe_from_threads()
    {

        $this->signIn();
        $thread = create('App\Thread');
        $thread->subscribe();

        $this->delete($thread->path() . '/subscriptions');

        $this->assertCount(1, $thread->subscriptions);
    }
}
