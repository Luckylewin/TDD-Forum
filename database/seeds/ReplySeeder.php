<?php
use Illuminate\Database\Seeder;
use \App\Models\Thread;

class ReplySeeder extends Seeder
{
    public function run()
    {
        $faker = app(Faker\Generator::class);

        Thread::query()->each(function ($thread) use ($faker) {
             $replyOrNot = mt_rand(0,1);
             if ($replyOrNot) {
                 $replyNum = mt_rand(1,15);
                 for ($num=1; $num<=$replyNum; $num++) {
                     factory(\App\Models\Reply::class)->create([
                         'thread_id' => $thread->id,
                         'user_id' => function() {
                             return factory('App\User')->create()->id;
                         },
                         'body' => $faker->paragraph
                     ]);
                 }

             }

        });
    }
}