<?php
namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SearchTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_search_a_thread()
    {
        $search = 'foobar';

        create('App\Thread', [], 2);
        create('App\Thread', ['body' => "A thread with the {$search} term."],2);
        $results = $this->getJson("/threads/search?q={$search}")->json();

        $this->assertCount(2, $results['data']);
    }
}
