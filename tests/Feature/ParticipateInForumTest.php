<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @1test 未认证用户不能发表回复
     */
    public function unauthenticated_user_may_no_add_replies()
    {
       $this->expectException('Illuminate\Auth\AuthenticationException');

       $this->post('threads/1/replies', []);
    }

    /**
     * @test 认证用户参与帖子讨论
     */
    public function an_authenticated_user_may_participated_in_forum_threads()
    {
        // 测试逻辑
        // 假设我们有一个已认证的用户 和 一篇帖子
        // 当用户回复帖子的时候，那么用户再次访问帖子时，应该可以看到该帖子
        $this->be(factory('App\User')->create());
        $thread = factory('App\Models\Thread')->create();
        $reply  = factory('App\Models\Thread')->create();

        $this->post($thread->path().'/replies', $reply->toArray());

        $this->get($thread->path())->assertSee($reply->body);
    }
}
