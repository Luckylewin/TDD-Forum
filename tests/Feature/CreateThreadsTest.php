<?php

namespace Tests\Feature;

use Tests\TestCase;

use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function guests_may_not_create_thread()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');

        $thread = factory('App\Models\Thread')->make();
        $this->post('/threads', $thread->toArray());
    }

    /**
     * @test
     */
    public function an_authenticated_user_can_create_forum_threads()
    {
        // 测试逻辑
        // 给定一个已登录的用户
        // 该用户创建一篇新的帖子
        // 当我们访问帖子时 我们可以看到这篇新的帖子

        $this->actingAs(factory('App\User')->create());

        $thread = factory('App\Models\Thread')->make();
        $this->post('/threads', $thread->toArray());

        $this->get($thread->path())
             ->assertSee($thread->title)
             ->assertSee($thread->body);
    }
}
