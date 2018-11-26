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
     * @test
     */
    public function a_user_can_browse_all_the_threads()
    {
        $this->get('/threads')->assertSee($this->thread->title);
    }

    /**
     * @test
     */
    public function a_user_can_browse_a_single_thread()
    {
        $this->get($this->thread->path())->assertSee($this->thread->title);
    }

    /**
     * @test
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
}