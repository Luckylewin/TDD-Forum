<?php
use Faker\Generator as Faker;

$factory->define(\App\Models\Thread::class, function (Faker $faker) {

    return [
        'user_id' => function() {
            return factory('App\User')->create()->id;
        },
        'channel_id' => function() {
            return factory('App\Models\Channel')->create()->id;
        },
        'title' => $faker->sentence,
        'body'  => $faker->paragraph,
        'visits' => 0
    ];
});