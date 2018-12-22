<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/12/22
 * Time: 21:01
 */

namespace Tests\Feature;


use App\Models\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SubscribeToThreadTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test 用户可以订阅话题
     */
    public function a_user_can_subscribe_to_threads()
    {
        $thread = create(Thread::class);

        $this->signIn();

        // 订阅
        $this->post($thread->path() . '/subscriptions');

        // 该文章有人回复
        $thread->addReply([
            'user_id' => auth()->id(),
            'body' => 'reply for example'
        ]);

        // 订阅该话题的用户可以获取到通知
        $this->assertCount(1, auth()->user()->notifications);
    }

    /**
     * @test 取消订阅
     */
    public function a_user_can_unsubscribe_from_threads()
    {
        $this->signIn();

        $thread = create(Thread::class);

        $this->delete($thread->path() . '/subscriptions');

        $this->assertCount(0,$thread->subscriptions);
    }

}
