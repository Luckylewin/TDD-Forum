<?php

namespace Tests\Feature;

use App\Models\Reply;
use App\Models\Thread;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BestReplyTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test 话题可能有最佳回复
     */
    public function a_thread_creator_may_mark_any_reply_as_the_best_reply()
    {
        $this->signIn();

        $thread = create(Thread::class,['user_id' => auth()->id()]);

        $replies = create(Reply::class,['thread_id' => $thread->id],2);

        $this->assertFalse($replies[0]->isBest());

        $this->postJson(route('best-replies.store',[$replies[0]->id]));

        $this->assertTrue($replies[0]->fresh()->isBest());
    }

    /**
     * @test 话题的创建者才能标记回复为最佳回复
     */
    public function only_the_thread_creator_can_mark_a_reply_as_best_reply()
    {
        $this->withExceptionHandling()->signIn();

        $thread = create(Thread::class,['user_id' => auth()->id()]);

        $replies = create(Reply::class,['thread_id' => $thread->id],2);

        $this->signIn(create(User::class));
        $this->postJson(route('best-replies.store', [$replies[0]->id]))
            ->assertStatus(403);
        $this->assertFalse($replies[0]->fresh()->isBest());
    }

    /**
     * @test 最佳回复被删除了 在话题中置之为 null
     */
    public function if_a_best_reply_is_deleted_then_the_thread_is_properly_updated_to_reflect_that()
    {
        $this->signIn();

        $reply = create(Reply::class, ['user_id' => auth()->id()]);

        $reply->thread->markBestReply($reply);

        $this->deleteJson(route('replies.destroy', $reply));

        $this->assertNull($reply->thread->fresh()->best_reply_id);
    }
}
