<?php


class NotificationSeeder extends \Illuminate\Database\Seeder
{
    public function run()
    {
        factory(\Illuminate\Notifications\DatabaseNotification::class,5)->create();
    }
}