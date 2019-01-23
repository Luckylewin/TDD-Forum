<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\Reply;
use App\Models\Thread;
use App\User;
use Tests\TestCase;

use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test 创建话题游客不可见
     */
    public function guests_may_not_see_the_create_thread_page()
    {
        $this->withExceptionHandling();

        $this->get('/threads/create')
             ->assertRedirect(route('login'));

        $this->post('/threads', [])
             ->assertRedirect(route('login'));
    }

    /**
     * @test 认证的用户可以创建话题
     */
    public function an_authenticated_user_can_create_forum_threads()
    {
        // 测试逻辑
        $this->signIn();
        // 该用户创建一篇新的帖子
        $thread = make(Thread::class);
        $response = $this->post(route('threads'), $thread->toArray());
        // 当我们访问帖子时 我们可以看到这篇新的帖子
        // 给定一个已登录的用户
        $this->get($response->headers->get('Location'))
             ->assertSee($thread->title)
             ->assertSee($thread->body);
    }

    /**
     * @test 话题-title 必填写
     */
    public function a_thread_requires_a_title()
    {
        $this->publishThread(['title' => null])
             ->assertSessionHasErrors('title');
    }

    /**
     * @test 话题-body 必填写
     */
    public function a_thread_requires_a_body()
    {
        $this->publishThread(['body' => null])
             ->assertSessionHasErrors('body');
    }

    /**
     * @test 话题-频道必须选择
     */
    public function a_thread_requires_a_valid_channel()
    {
        factory('App\Models\Channel', 2)->create();

        $this->publishThread(['channel_id' => null])
             ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => 9999])
             ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => 2])
            ->assertSessionMissing('channel_id');
    }

    /**
     * 测试帖子删除
     * @test
     */
    public function authorized_users_cant_delete_threads()
    {
        $this->signIn();

        // 用户自己的文章
        $thread = create(Thread::class, ['user_id' => auth()->id()]);
        $reply = create(Reply::class, ['thread_id' => $thread->id]);

        $response = $this->json('DELETE', $thread->path());

        $response->assertStatus(204);

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);

        // 相关的信息流被删除
        $this->assertEquals(0, Activity::count());

    }

    /**
     * 游客不能删除话题
     * @test
     */
    public function unauthorized_users_may_not_delete_threads()
    {
        $thread = create(Thread::class);
        // 游客会跳转到登录
        $this->withExceptionHandling();
        $this->delete($thread->path())->assertRedirect(route('login'));
        // 不能删除不是自己的文章
        $this->signIn();
        $this->delete($thread->path())->assertStatus(403);
    }

    protected function publishThread($overrides = [])
    {
        $this->withExceptionHandling()->signIn();

        $thread = make('App\Models\Thread', $overrides);

        return $this->post('/threads', $thread->toArray());
    }

    /**
     * @test 登录用户在创建话题前需要先验证邮箱
     */
    public function new_users_must_first_confirm_their_email_address_before_creating_threads()
    {
        // 调用 unconfirmed，生成未认证用户
        $user = factory(User::class)->states('unconfirmed')->create();

        $this->signIn($user);

        $thread = make(Thread::class);

        $this->post(route('threads'),$thread->toArray())
            ->assertRedirect(route('threads'))
            ->assertSessionHas('flash','You must first confirm your email address.');
    }
}
