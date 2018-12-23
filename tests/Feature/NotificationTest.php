<?php

namespace Tests\Feature;

use App\Models\Thread;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;


class NotificationTest extends TestCase
{
    use DatabaseMigrations;


    /**
     * @test 当订阅话题有不是当前用户发布的回复时 通知就绪
     */
    public function a_notification_is_prepare_when_a_subscribed_thread_receives_a_new_reply_that_is_not_by_the_current_user()
    {
        $this->signIn();

        $thread = create(Thread::class);

        $thread->subscribe();

        $this->assertCount(0, auth()->user()->notifications);

        // 创建者回复
        $thread->addReply([
            'user_id' => auth()->id(),
            'body' => 'Some reply here'
        ]);

        $this->assertCount(0, auth()->user()->fresh()->notifications);

        // 别人回复
        $thread->addReply([
            'user_id' => create(User::class)->id,
            'body' => 'Some reply here'
        ]);

        $this->assertCount(1, auth()->user()->fresh()->notifications);
    }

    /**
     * @test 用户可以获取未读取的通知
     */
    public function a_user_can_fetch_their_unread_notifications()
    {
        $this->signIn();

        $thread = create(Thread::class);

        $thread->subscribe();

        $thread->addReply([
            'user_id' => create(User::class)->id,
            'body' => 'example test'
        ]);

        $response = $this->getJson('/profiles/' . auth()->user()->name . '/notifications')->json();

        $this->assertCount(1, $response);
    }

    /**
     * @test 用户移除通知消息
     */
    public function a_user_can_clear_a_notification()
    {
        $this->signIn();

        $thread = create(Thread::class);

        $thread->subscribe();

        $thread->addReply([
            'user_id' => create(User::class)->id,
            'body' => 'Some reply here'
        ]);

        $user = auth()->user();

        $this->assertCount(1, $user->unreadNotifications);

        $notificationId = $user->notifications->first()->id;

        $this->delete('/profiles/' . $user->name . "/notifications/{$notificationId}");

        $this->assertCount(0, $user->fresh()->unreadNotifications);
    }
}
