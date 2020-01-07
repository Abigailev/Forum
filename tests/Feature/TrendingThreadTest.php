<?php
namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TrendingThreadTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        $this->trending = new \App\Trending();

        $this->trending->reset();

    }

    /** @test */
    public function it_increments_a_thread_score_each_time_it_is_read()
    {

        //Por alguna razon el codigo comentado no funcionaba con el assertEmpty pero el Count si,
        //ahora, refactorizado funciona con el empty y con el Count no
        //$this->assertCount(0, Redis::zrevrange('testing_trending_threads', 0, -1));
        $this->assertEmpty($this->trending->get());

        $thread = create('App\Thread');
        $this->call('GET', $thread->path());

        //$trending = Redis::zrevrange('testing_trending_threads', 0, -1);

        $this->assertCount(1, $trending = $this->trending->get());

        //$this->assertEquals($thread->title, json_decode($trending[0])->title);
        $this->assertEquals($thread->title, $trending[0]->title);


    }
}
