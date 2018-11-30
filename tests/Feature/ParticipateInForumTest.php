<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test 未认证用户不能发表回复
     */
    public function unauthenticated_user_may_no_add_replies()
    {
       $this->withExceptionHandling()
            ->post('threads/some-channel/1/replies', [])
            ->assertRedirect('/login');
    }

    /**
     * @test 认证用户参与帖子讨论
     */
    public function an_authenticated_user_may_participated_in_forum_threads()
    {
        // 测试逻辑
        // 假设我们有一个已认证的用户 和 一篇帖子
        // 当用户回复帖子的时候，那么用户再次访问帖子时，应该可以看到该帖子
        $this->signIn();
        $thread = create('App\Models\Thread');
        $reply  = make('App\Models\Thread');

        $this->post($thread->path().'/replies', $reply->toArray());

        $this->get($thread->path())
             ->assertSee($reply->body);
    }

    /** @test */
    public function a_reply_requires_a_body()
    {
        $this->withExceptionHandling()->signIn();

        $thread = create('App\Models\Thread');
        $reply = make('App\Models\Reply', ['body' => null]);

        $this->post($thread->path() . '/replies', $reply->toArray())
             ->assertSessionHasErrors('body');
    }
}
