<?php

namespace Tests\Feature;

use App\Models\Reply;
use App\Models\Thread;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;


/**
 * @某人 测试
 * Class MentionUsersTest
 * @package Tests\Feature
 */
class MentionUsersTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test 在回复中被提及的用户将会被通知
     */
    public function mentioned_users_in_a_reply_are_notified()
    {
        $john = create(User::class,['name'=>'John']);

        $this->signIn();

        $jane = create(User::class,['name' => 'Jane']);

        $thread = create(Thread::class);

        $reply = make(Reply::class,[
            'body' => '@Jane hello world and @Luke'
        ]);

        $this->json('post',$thread->path() . '/replies', $reply->toArray());

        $this->assertCount(1, $jane->notifications);
    }
}
