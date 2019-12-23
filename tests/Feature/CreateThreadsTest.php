<?php

namespace Tests\Feature;

use App\Activity;
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
    function an_authenticated_user_can_create_new_forum_threads()
    {
        //given the signedIn user
        //$this->factory('App\User')->create();
        //$this->actingAs(factory('App\User')->create());
        $this->signIn();

        //when hit the endpoint to create a new thread
        $thread = make('App\Thread');
        $response = $this->post('/threads', $thread->toArray());

        //dd($response->headers->get('Location'));

        //visit the thread page
        $this->get($response->headers->get('Location'))
        //we should see
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
          $this->assertDatabaseMissing('activities', [
              'subject_id' => $thread->id,
              'subject_type' => get_class($thread)
          ]);
        $this->assertDatabaseMissing('activities', [
            'subject_id' => $reply->id,
            'subject_type' => get_class($reply)
        ]);
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
