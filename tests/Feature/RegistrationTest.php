<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Mail\PleaseConfirmYourEmail;
use \Illuminate\Auth\Events\Registered;

class RegistrationTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test 注册时发送验证邮件
     */
    public function a_confirmation_email_is_sent_upon_registration()
    {
        Mail::fake();

        // 用路由命名代替 url
        $this->post(route('register'),[
            'name' => 'NoNo1',
            'email' => 'NoNo1@example.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ]);

        Mail::assertSent(PleaseConfirmYourEmail::class);
    }

    /**
     * @test 用户点击链接后验证邮箱
     */
    public function user_can_fully_confirm_their_email_address()
    {
        $this->post(route('register'),[
            'name' => 'lychee',
            'email' => 'zystyle@foxmail.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ]);

        $user = User::whereName('lychee')->first();

        $this->assertFalse($user->confirmed);

        $this->assertNotNull($user->confirmation_token);

        $this->get(route('register.confirm',['token' => $user->confirmation_token]))
            ->assertRedirect(route('threads'));

        // 当新注册用户点击认证链接，用户变成已认证，且跳转到话题列表页面
        tap($user->fresh(), function($user) {
            $this->assertTrue($user->confirmed);
            $this->assertNull($user->confirmation_token);
        });
    }

    /**
     * @test 测试无效的token
     */
    public function confirming_an_invalid_token()
    {
        // 测试无效 Token
        $this->get(route('register.confirm'),['token' => 'invalid'])
            ->assertRedirect(route('threads'))
            ->assertSessionHas('flash','Unknown token.');
    }
}
