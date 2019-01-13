<?php
use App\Models\Channel;

/**
 * Created by PhpStorm.
 * User: lychee
 * Date: 2019/1/13
 * Time: 21:48
 */

class ChannelSeeder extends \Illuminate\Database\Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 给每个用户建立 1~3 个收货地址
        $channels = [
            '编程' => 'code',
            '生活' => 'life',
            '工作' => 'work',
            '运动' => 'sport',
            '诗词' => 'poem',
            '情感' => 'emotion',
        ];

        collect($channels)->each(function ($slug,$name) {
            factory(Channel::class)->create([
                'name' => $name,
                'slug' => $slug
            ]);
        });

    }
}