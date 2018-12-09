<?php

namespace Tests\Feature;

use App\Models\Reply;
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

    /**
     * body 必填
     * @test
     */
    public function a_reply_requires_a_body()
    {
        $this->withExceptionHandling()->signIn();

        $thread = create('App\Models\Thread');
        $reply = make('App\Models\Reply', ['body' => null]);

        $this->post($thread->path() . '/replies', $reply->toArray())
             ->assertSessionHasErrors('body');
    }

    /**
     * 无权限用户不能删除评论
     * @test
     */
    public function unauthorized_users_cannot_delete_replies()
    {
        $this->withExceptionHandling();

        $reply = create(Reply::class);

        $this->delete("/replies/{$reply->id}")
             ->assertRedirect('/login');

        $this->signIn()
             ->delete("replies/{$reply->id}")
             ->assertStatus(403);
    }

    /**
     * 有权限的用户可以删除评论
     * @test
     */
    public function authorized_users_can_delete_replies()
    {
        $this->withExceptionHandling();

        $this->signIn();

        $reply = create(Reply::class, ['user_id' => auth()->id()]);

        $this->delete("/replies/{$reply->id}")->assertStatus(302);

        $this->assertDatabaseMissing('replies',['id' => $reply->id]);
    }

    /**
     * 没有权限的用户不能编辑回复
     * @test
     */
    public function unauthorized_users_cannot_update_replies()
    {
        $this->withExceptionHandling();

        $reply = create(Reply::class);

        $this->patch("/replies/{$reply->id}")
            ->assertRedirect('/login');

        $this->signIn();

        $this->patch("/replies/{$reply->id}")
            ->assertStatus(403);
    }

    /**
     * 有权限的用户可以编辑回复
     * @test
     */
    public function authorized_users_can_update_replies()
    {
        $this->signIn();

        $reply = create(Reply::class, ['user_id' => auth()->id()]);

        $updateContent = "it have been change,foo";
        $this->patch("/replies/{$reply->id}", ['body' => $updateContent]);

        $this->assertDatabaseHas('replies', ['id' => $reply->id, 'body' => $updateContent]);
    }
}
