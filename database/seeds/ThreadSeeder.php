<?php
/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2019/1/13
 * Time: 21:53
 */

class ThreadSeeder extends \Illuminate\Database\Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Channel::query()->each(function ($channel){
            factory(\App\Models\Thread::class,30)->create([
                'channel_id' => $channel->id
            ]);
        });
    }
}