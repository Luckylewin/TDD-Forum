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
    public function guests_may_not_see_the_create_thread_page()
    {
        $this->withExceptionHandling();

        $this->get('/threads/create')
             ->assertRedirect('/login');

        $this->post('/threads', [])
             ->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function an_authenticated_user_can_create_forum_threads()
    {
        // 测试逻辑
        $this->signIn();
        // 该用户创建一篇新的帖子
        $thread = make('App\Models\Thread');
        $response = $this->post('/threads', $thread->toArray());
        // 当我们访问帖子时 我们可以看到这篇新的帖子
        // 给定一个已登录的用户
        $this->get($response->headers->get('Location'))
             ->assertSee($thread->title)
             ->assertSee($thread->body);
    }

    /** @test */
    public function a_thread_requires_a_title()
    {
        $this->publishThread(['title' => null])
             ->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_thread_requires_a_body()
    {
        $this->publishThread(['body' => null])
             ->assertSessionHasErrors('body');
    }

    /** @test */
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

    public function publishThread($overrides = [])
    {
        $this->withExceptionHandling()->signIn();

        $thread = make('App\Models\Thread', $overrides);

        return $this->post('/threads', $thread->toArray());
    }
}
