<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2018/12/2
 * Time: 11:38
 */

namespace Tests\Feature;


use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FavoritesTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test 登录用户可以点赞任何回复
     */
    public function an_authenticated_user_can_favorite_any_reply()
    {
        // 如果已经登录的用户发送了 "favorite"
        // 那么将会记录到数据表中
        $this->signIn();
        $reply = create('App\Models\Reply');
        $this->post('/replies/' . $reply->id . '/favorites');

        $this->assertCount(1, $reply->favorites);
    }

    /**
     * @test 未登录用户不能评论
     */
    public function guests_can_not_favorite_anything()
    {
        $this->withExceptionHandling()
             ->post('/replies/1/favorites')
             ->assertRedirect('/login');
    }

    /**
     * @test 用户只能对一个回复点赞一次
     */
    public function an_authenticated_user_may_only_favorite_a_reply_once()
    {
        $this->signIn();

        $reply = create('App\Models\Reply');

        try {
            $url = '/replies/' . $reply->id . '/favorites';
            $this->post($url);
            $this->post($url);
        } catch (\Exception $e) {
            $this->fail('不能插入相同的一条记录');
        }

        $this->assertCount(1, $reply->favorites);
    }
}