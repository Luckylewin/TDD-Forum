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

        $this->post($thread->path() . '/subscriptions');

        $this->assertCount(1, $thread->subscriptions);
    }


}
