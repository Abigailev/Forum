<?php
namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LockThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function non_administrators_may_not_lock_threads()
    {
        $this->withExceptionHandling();
        $this->signIn();
        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $this->post(route('locked-threads.store', $thread),[
            'locked' => true
        ])->assertStatus(403);

        $this->assertFalse(!! $thread->fresh()->locked);
    }

    /** @test */
    public function administrators_can_lock_threads()
    {
        //$this->signIn(create('App\User', ['name' => 'MansonK']));
        $this->signIn(factory('App\User')->state('administrator')->create());
        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $this->post(route('locked-threads.store', $thread),[
            'locked' => true
        ]);

        //dd($thread);

        $this->assertTrue(!! $thread->fresh()->locked, 'Failed asserting that the thread was locked');

    }

    /** @test */
    public function administrators_can_unlock_threads()
    {
        //$this->signIn(create('App\User', ['name' => 'MansonK']));
        $this->signIn(factory('App\User')->state('administrator')->create());
        $thread = create('App\Thread', ['user_id' => auth()->id(), 'locked'=>false]);

        $this->delete(route('locked-threads.destroy', $thread));

        //dd($thread);

        $this->assertFalse($thread->fresh()->locked, 'Failed asserting that the thread was unlocked');

    }

        /** @test */
    public function once_locked_a_thread_may_not_receive_new_replies()
    {
         $this->signIn();
         $thread = create('App\Thread');

         $thread->lock();

         $this->post($thread->path() . '/replies', [
             'body' => 'Foobar',
             'user_id' => auth()->id()
         ])->assertStatus(422);
    }
}
