<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\User::class)->create([
            'name' => 'admin',
            'email' => 'admin@qq.com',
            'password' => bcrypt('123456'),
            'remember_token' => str_random(10),
        ]);

        factory(\App\User::class)->create([
            'name' => 'zy2010816',
            'email' => '876505905@qq.com',
            'password' => bcrypt('123456'),
            'remember_token' => str_random(10),
        ]);

        factory(\App\User::class,30)->create();
    }
}
