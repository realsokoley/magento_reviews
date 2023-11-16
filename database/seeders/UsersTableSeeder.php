<?php

namespace Database\Seeders;

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        \App\User::truncate();

        $faker = \Faker\Factory::create();
        $password = bcrypt('secret');

        \App\User::create([
            'name'     => $faker->name,
            'email'    => 'graphql@test.com',
            'password' => $password,
        ]);

        for ($i = 0; $i < 10; ++$i) {
            \App\User::create([
                'name'     => $faker->name,
                'username'     => $faker->username,
                'email'    => $faker->email,
                'password' => $password,
            ]);
        }
    }
}
