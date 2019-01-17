<?php

namespace Tests\Feature;

use App\Models\Thread;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TrendingThreadsTest extends TestCase
{
   use DatabaseMigrations;

   protected function setUp()
   {
       parent::setUp();

       Redis::del('trending_threads');
   }

    /**
     * @test 话题被阅读后阅读数自增
     */
   public function it_increments_a_thread_score_each_time_it_is_read()
   {
       $this->assertEmpty(Redis::zrevrange('trending_threads',0,-1));

       $thread = create(Thread::class);

       $this->get($thread->path());

       $trending = Redis::zrevrange('trending_threads',0,-1);

       $this->assertCount(1, $trending);

       $this->assertEquals($thread->title, json_decode($trending[0])->title);
   }
}
