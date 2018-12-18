<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/11/20
 * Time: 15:58
 */

namespace Tests\Feature;


use App\Models\Reply;
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

    /**
     * @test 根据用户名筛选话题
     */
    public function  a_user_can_filter_threads_by_any_username()
    {
        $this->signIn(create('App\User', ['name' => 'NoNo1']));

        $threadByNoNo1 = create('App\Models\Thread', ['user_id' => auth()->id()]);
        $threadNotByNoNo1 = create('App\Models\Thread');

        $this->get('/threads?by=NoNo1')
             ->assertSee($threadByNoNo1->title)
             ->assertDontSee($threadNotByNoNo1->title);
    }

    /**
     * @test 可以根据评论数进行筛选
     */
    public function a_user_can_filter_threads_by_popularity()
    {
        // 测试逻辑
        // 给定三个话题，对应回复数分别有2个回复 3个回复 0个回复
        $threadWithTwoReplies = create('App\Models\Thread');
        create('App\Models\Reply', ['thread_id' => $threadWithTwoReplies->id], 2);

        $threadWithThreeReplies = create('App\Models\Thread');
        create('App\Models\Reply', ['thread_id' => $threadWithThreeReplies], 3);

        $threadWithNoReplies = $this->thread;

        $response = $this->getJson('/threads?popularity=1')->json();
        $this->assertEquals([3,2,0], array_column($response, 'replies_count'));
    }

    /**
     * @test
     */
    public function a_user_can_request_all_replies_for_a_given_thread()
    {
        $thread = create(Thread::class);
        create(Reply::class, ['thread_id' => $thread->id], 2);

        $response = $this->json('get', $thread->path() . '/replies')->json();

        $this->assertCount(1, $response['data']);
        $this->assertEquals(2, $response['total']);
    }
}