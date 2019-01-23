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

        $user = create(User::class);

        event(new Registered($user));

        Mail::assertSent(PleaseConfirmYourEmail::class);
    }

    /**
     * @test 用户点击链接后验证邮箱
     */
    public function user_can_fully_confirm_their_email_address()
    {
        $this->post('/register',[
            'name' => 'lychee',
            'email' => 'zystyle@foxmail.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ]);

        $user = User::whereName('lychee')->first();

        $this->assertFalse($user->confirmed);

        $this->assertNotNull($user->confirmation_token);

        $response = $this->get('/register/confirm?token=' . $user->confirmation_token);

        // 当新注册用户点击认证链接，用户变成已认证，且跳转到话题列表页面
        $this->assertTrue($user->fresh()->confirmed);
        $response->assertRedirect('/threads');
    }
}
