<?php
use Faker\Generator as Faker;

$factory->define(\App\Models\Thread::class, function (Faker $faker) {

    $title = $faker->sentence;

    return [
        'user_id' => function() {
            return factory('App\User')->create()->id;
        },
        'channel_id' => function() {
            return factory('App\Models\Channel')->create()->id;
        },
        'title' => $title,
        'body'  => $faker->paragraph,
        'visits' => 0,
        'slug' => str_slug($title)
    ];
});