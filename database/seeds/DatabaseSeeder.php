<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(ChannelSeeder::class);
        $this->call(ThreadSeeder::class);
        $this->call(ReplySeeder::class);
        $this->call(NotificationSeeder::class);
    }
}
