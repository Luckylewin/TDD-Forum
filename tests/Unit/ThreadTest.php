<?php

namespace Tests\Unit;

use App\Models\Thread;
use App\Notifications\ThreadWasUpdated;
use App\User;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;


class ThreadTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var Thread
     */
    public $thread;

    public function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->thread = factory('App\Models\Thread')->create();
    }

    /**
     * @test 帖子有回复
     */
    public function a_thread_has_replies()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->thread->replies);
    }

    /**
     * @test 帖子有创建者
     */
    public function a_thread_has_a_creator()
    {
       $this->assertInstanceOf('App\User', $this->thread->creator);
    }

    /**
     * @test 可以回复帖子
     */
    public function a_thread_can_add_a_reply()
    {
        $this->thread->addReply([
            'body' => 'foo',
            'user_id' => 1
        ]);

        $this->assertCount(1, $this->thread->replies);
    }

    /**
     * @test 当有回复 通知所有订阅文章的用户
     */
    public function a_thread_notifies_all_registered_subscribers_when_reply_is_added()
    {
        // 测试模拟器
        Notification::fake();

        $this->signIn()
            ->thread
            ->subscribe()
            ->addReply([
                'body' => 'text',
                'user_id' => 999
            ]);

        Notification::assertSentTo(auth()->user(), ThreadWasUpdated::class);
    }

    /**
     * 一个话题属于一个频道
     * @test
     */
    public function a_thread_belongs_to_a_channel()
    {
        $thread = $this->thread;

        $this->assertInstanceOf('App\Models\Channel', $thread->channel);
    }

    /**
     * 一个话题拥有它的专属路径
     * @test
     */
    public function a_thread_has_a_path()
    {
        $thread = $this->thread;

        $this->assertEquals("/threads/{$thread->channel->slug}/{$thread->slug}", $thread->path());
    }

    /**
     * 话题可以被订阅
     * @test
     */
    public function a_thread_can_be_subscribed_to()
    {
        // 测试逻辑 ：给定一个话题，一个认证的用户,当用户订阅此话题，用户能够看到所有该话题的回复
        $thread = create(Thread::class);

        $this->signIn();

        $thread->subscribe();

        $this->assertCount(
            1,
                     $thread->subscriptions()->where('user_id',auth()->id())->get()
            );
    }

    /**
     * 用户取消订阅
     * @test
     */
    public function a_thread_can_be_unsubscribed()
    {
        // 测试逻辑
        // 给定一个话题
        $thread = create(Thread::class);

        // 用户ID=1的人去订阅
        $thread->subscribe($userId = 1);

        // 然后取消了订阅
        $thread->unsubscribe($userId);

        $this->assertCount(0, $thread->subscriptions);
    }

    /**
     * @test 判断用户是否订阅过该话题
     */
    public function it_knows_if_the_authenticated_user_is_subscribed_to_it()
    {
        $thread = create(Thread::class);

        $this->signIn();

        $this->assertFalse($thread->isSubscribedTo);

        $thread->subscribe();

        $this->assertTrue($thread->isSubscribedTo);
    }

    /**
     * @test 如果登录用户已经阅读过所有,则话题可以检测到此状态
     */
    public function a_thread_can_check_if_the_authenticated_user_has_read_all_replies()
    {
        $this->signIn();

        $thread = create(Thread::class);


        tap(auth()->user(), function ($user) use ($thread) {
            // 对标题进行加粗显示
            $this->assertTrue($thread->hasUpdatesFor($user));

            /**
             * 浏览话题
             * @var $user User
             */
            $user->read($thread);

            // 取消加粗
            $this->assertFalse($thread->hasUpdatesFor($user));
        });
    }

}
