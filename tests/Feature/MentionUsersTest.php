<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Notifications\YouWereMentioned;

class MentionUsersTest extends TestCase
{

use DatabaseMigrations;

    /** @test */
    function mentioned_users_in_a_reply_are_notified()
    {
      //given I have a user
      $john = create('App\User', ['name' => 'JohnDoe']);
      $this->signIn($john);
      //and another
      $jane = create('App\User', ['name' => 'JaneDoe']);

      //if we have a threads
      $thread = create('App\Thread');
      //and john mention jane
      $reply = make('App\Reply', [
        'body'=> '@JaneDoe look at this.'
      ]);

      $this->json('post',$thread->path() . '/replies', $reply->toArray());
      //then jane gets a notification
      $this->assertCount(1, $jane->notifications);
    }

    /** @test */
    function it_can_fetch_all_mentioned_users_starting_with_the_given_characters()
    {
        create('App\User', ['name' => 'JohnDoe']);
        create('App\User', ['name' => 'JaneDoe']);
        create('App\User', ['name' => 'MansonK']);

        $results = $this->json('GET', 'api/users', ['name' => 'manson']);

        $this->assertCount(1, $results->json());
    }
}
