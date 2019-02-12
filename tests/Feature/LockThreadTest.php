<?php

namespace Tests\Feature;

use App\Models\Thread;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LockThreadTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test 话题一旦锁定后将不能再收到回复
     */
    public function once_locked_thread_may_not_received_new_replies()
    {
        $this->signIn();

        $thread = create(Thread::class);

        $thread->locked();

        $this->post($thread->path() . '/replies',[
            'body' => 'foo bar',
            'user_id' => auth()->id()
        ])->assertStatus(422);
    }

    /**
     * @test 只有管理员才能锁话题
     */
    public function non_administrator_may_not_lock_threads()
    {
        $this->withExceptionHandling();

        $this->signIn();

        $thread = create(Thread::class,[
            'body' => 'foo bar',
            'user_id' => 999
        ]);

        $this->post(route('locked-threads', $thread))->assertStatus(403);

        $this->assertFalse(!! $thread->fresh()->locked);
    }

    /**
     * @test 管理员可以锁定话题
     */
    public function administrator_can_lock_threads()
    {
        $this->signIn(factory(User::class)->states('administrator')->create());

        $thread = create(Thread::class,['user_id' => auth()->id()]);

        $this->post(route('locked-threads',$thread));

        $this->assertTrue(!! $thread->fresh()->locked);
    }

    /**
     * @test 管理员可以解锁话题
     */
    public function administrator_can_unlock_threads()
    {
        $this->signIn(factory(User::class)->states('administrator')->create());

        $thread = create(Thread::class,['user_id' => auth()->id(),'locked' => true]);

        $this->delete(route('locked-threads.destroy', $thread));

        $this->assertFalse($thread->fresh()->locked);
    }
}
