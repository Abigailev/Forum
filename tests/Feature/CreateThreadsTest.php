<?php

namespace Tests\Feature;

use App\Activity;
use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function guests_may_not_create_threads()
    {

        $this->withExceptionHandling()
             ->get('threads/create')
             ->assertRedirect('/login');

        $this->post('/threads')
             ->assertRedirect('/login');

        //$this->expectException('Illuminate\Auth\AuthenticationException');

        //es lo mismo//$thread = factory('App\Thread')->make();
        //$thread = make('App\Thread');
        //$this->post('/threads',$thread->toArray());
    }

    /** @test */
    function new_users_must_first_confirm_their_email_address_before_creating_threads()
    {
        $user = factory('App\User')->state('unconfirmed')->create();

        $this->withExceptionHandling()->signIn($user);

        $thread = make('App\Thread');

        $this->post('/threads', $thread->toArray())
             ->assertRedirect('/threads')
             ->assertSessionHas('flash', 'You must confirm your email address.');
    }

    /** @test */
    function a_user_can_create_new_forum_threads()
    {
        $this->signIn();

        $thread = make('App\Thread');
        $response = $this->post('/threads', $thread->toArray());

        //dd($response->headers->get('Location'));

        $this->get($response->headers->get('Location'))
             ->assertSee($thread->title)
             ->assertSee($thread->body);
    }

    /** @test */
    function a_thread_requires_title()
    {
        $this->publishThread(['title'=>null])
             ->assertSessionHasErrors('title');
    }

    /** @test */
    function a_thread_requires_body()
    {
        $this->publishThread(['body'=>null])
            ->assertSessionHasErrors('body');
    }

    /** @test */
    function a_thread_requires_a_valid_channel()
    {
        factory('App\Channel', 2)->create();

        $this->publishThread(['channel_id'=>null])
            ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id'=>999])
            ->assertSessionHasErrors('channel_id');
    }

    /** @test */
    function a_thread_requires_a_unique_slug()
    {
        $this->signIn();

        $thread = create('App\Thread', ['title'=>'Foo Title']);
        $this->assertEquals($thread->fresh()->slug,'foo-title');

        $thread = $this->postJson(route('threads'), $thread->toArray())->json();
        $this->assertEquals("foo-title-{$thread['id']}", $thread['slug']);


    }

    /** @test */
    function a_thread_with_a_title_that_ends_in_a_number_should_generate_the_proper_slug()
    {
        $this->signIn();
        $thread = create('App\Thread', ['title'=>'Thread Test 24']);

        $thread = $this->postJson(route('threads'), $thread->toArray())->json();
        $this->assertTrue(Thread::whereSlug('thread-test-24-2')->exists());

    }

    /** @test */
    function unauthorized_users_may_not_delete_threads ()
    {
         $this->withExceptionHandling();
         $thread = create('App\Thread');

         $this->delete($thread->path())->assertRedirect('/login');

         $this->signIn();
         $this->delete($thread->path())->assertStatus(403);

    }

    /** @test */
    function authorized_users_can_delete_threads()
    {
          $this->signIn();

          $thread = create('App\Thread', ['user_id' => auth()->id()]);
          $reply = create('App\Reply', ['thread_id' => $thread->id]);

          $response = $this->json('DELETE', $thread->path());

          $response->assertStatus(204);

          $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
          $this->assertDatabaseMissing('replies', ['id' => $reply->id]);

          $this->assertEquals(0, Activity::count());
//          $this->assertDatabaseMissing('activities', [
//              'subject_id' => $thread->id,
//              'subject_type' => get_class($thread)
//          ]);
//        $this->assertDatabaseMissing('activities', [
//            'subject_id' => $reply->id,
//            'subject_type' => get_class($reply)
//        ]);
    }

    function publishThread($overrides = [])
    {
        $this->withExceptionHandling()->signIn();

        $thread = make('App\Thread', $overrides);

        return $this->post('/threads', $thread->toArray());

    }
}


/*
 * we put on the terminal "php artisan thinker" to see what we have
 * then we put what we want to create, like this
 * factory ('App\User')->create();
 * that will return the id, name and email of the created user
 *
 * the return works like:
 * factory ('App\User')->make();
 * return:
 * name, email, password and token
 * */
