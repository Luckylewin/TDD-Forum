<?php

namespace Tests\Unit;

use App\Models\Reply;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReplyTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_it_has_an_owner()
    {
        $reply = factory('App\Models\Reply')->create();
        $this->assertInstanceOf('App\User', $reply->owner);
    }

    /**
     * @test 测试是否为刚刚发布的回复
     */
    public function it_knows_if_it_was_just_published()
    {
        $reply = create(Reply::class);

        $this->assertTrue($reply->wasJustPublished());

        $reply->created_at = Carbon::now()->subMonth();

        $this->assertFalse($reply->wasJustPublished());
    }

    /**
     * @test 所有被提及的用户都会被检测到
     */
    public function it_can_detect_all_mentioned_users_in_the_body()
    {
        $reply = create(Reply::class,[
            'body' => '@JaneDoe wants to talk to @JohnDoe'
        ]);

        $this->assertEquals(['JaneDoe','JohnDoe'], $reply->mentionedUsers());
    }
}
