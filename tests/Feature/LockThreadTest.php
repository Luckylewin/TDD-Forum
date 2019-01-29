<?php

namespace Tests\Feature;

use App\Models\Thread;
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

        $thread->locks();

        $this->post($thread->path() . '/replies',[
            'body' => 'foo bar',
            'user_id' => auth()->id()
        ])->assertStatus(422);
    }
}
