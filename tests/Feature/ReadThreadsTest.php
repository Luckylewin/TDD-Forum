<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/11/20
 * Time: 15:58
 */

namespace Tests\Feature;


use App\Models\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ReadThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var Thread
     */
    public $thread;

    public function setUp()
    {
        parent::setUp();
        $this->thread = factory('App\Models\Thread')->create();
    }

    /**
     * @test 用户可以看到帖子列表
     */
    public function a_user_can_browse_all_the_threads()
    {
        $this->get('/threads')->assertSee($this->thread->title);
    }

    /**
     * @test 用户可以看帖子详情
     */
    public function a_user_can_browse_a_single_thread()
    {
        $this->get($this->thread->path())->assertSee($this->thread->title);
    }

    /**
     * @test 用户可以回复帖子
     */
    public function a_user_can_read_replies_that_are_associated_with_a_thread()
    {
        // 当有 thread 的时候 且该 thread 有 reply 的时候
        $reply = factory('App\Models\Reply')->create([
                'thread_id' => $this->thread->id
        ]);
        // 用户一定会看到 reply
        $this->get($this->thread->path())->assertSee($reply->body);
    }

    /**
     * @test 用户可以根据频道来过滤帖子
     */
    public function a_user_can_filter_threads_according_to_a_channel()
    {
        $channel = create('App\Models\Channel');
        $threadInChannel = create('App\Models\Thread', ['channel_id' => $channel->id]);
        $threadNotInChannel = create('App\Models\Thread');

        $this->get('/threads/' . $channel->slug)
             ->assertSee($threadInChannel->title)
             ->assertDontSee($threadNotInChannel->title);
    }
}