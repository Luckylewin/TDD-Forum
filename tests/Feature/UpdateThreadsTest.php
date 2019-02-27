<?php

namespace Tests\Feature;

use App\Models\Thread;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateThreadsTest extends TestCase
{
   use RefreshDatabase;

   public function setUp(): void
   {
       parent::setUp();

       $this->withExceptionHandling();

       $this->signIn();
   }

    /**
     * @test 只有话题创建者才能更新
     */
   public function unauthorized_users_may_not_update_threads()
   {
        $thread = create(Thread::class,['user_id' => create(User::class)->id]);

        $this->patch($thread->path(),[])->assertStatus(403);
   }

    /**
     * @test 更新的字段要符合规则
     */
   public function a_thread_requires_a_title_and_body_to_be_updated()
   {
        $thread = create(Thread::class,['user_id' => auth()->id()]);

        $this->patch($thread->path(),[
            'title' => 'Changed.'
        ])->assertSessionHasErrors('body');

        $this->patch($thread->path(), [
            'body' => 'Changed.'
        ])->assertSessionHasErrors('title');
   }

    /**
     * 话题可以成功更新
     */
   public function a_thread_can_be_updated_by_its_creator()
   {
       $thread = create(Thread::class,['user_id' => auth()->id()]);

       $this->patch($thread->path(),[
          'title' => 'changed title',
          'body' => 'changed body'
       ]);

       tap($thread->fresh(), function ($thread) {
            $this->assertEquals('changed title', $thread->title);
            $this->assertEquals('changed body', $thread->body);
       });
   }
}
