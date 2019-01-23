<?php

namespace Tests\Feature;

use App\Components\Trending;
use App\Models\Thread;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TrendingThreadsTest extends TestCase
{
   use DatabaseMigrations;

    /**
     * @var Trending
     */
   public $trending;

   protected function setUp()
   {
       parent::setUp();

       $this->trending = (new Trending());
       $this->trending->reset();
   }

    /**
     * @test 话题被阅读后阅读数自增
     */
   public function it_increments_a_thread_score_each_time_it_is_read()
   {
       $this->assertEmpty(Redis::zrevrange($this->trending->cacheKey(),0,-1));

       $thread = create(Thread::class);

       $this->get($thread->path());

       $trending = Redis::zrevrange($this->trending->cacheKey(),0,-1);

       $this->assertCount(1, $trending);

       $this->assertEquals($thread->title, json_decode($trending[0])->title);
   }
}
