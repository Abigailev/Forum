<?php
namespace Tests\Feature;

use Illuminate\Support\Facades\Redis;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TrendingThreadTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        $this->trending = new \App\Trending();

        Redis::del('trending_threads');
    }

    /** @test */
    public function it_increments_a_thread_score_each_time_it_is_read()
    {
        $this->assertCount(0, Redis::zrevrange('testing_trending_threads', 0, -1));

        $thread = create('App\Thread');
        $this->call('GET', $thread->path());

        $trending = Redis::zrevrange('testing_trending_threads', 0, -1);
        $this->assertCount(1, $trending);

        $this->assertEquals($thread->title, json_decode($trending[0])->title);


    }
}
