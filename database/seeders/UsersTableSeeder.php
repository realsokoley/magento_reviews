<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $password = bcrypt('secret');

        // Create a specific user
        User::factory()->create([
            'name'     => 'Specific User',
            'email'    => 'graphql@test.com',
            'username' => 'specificusername',
            'password' => $password,
        ]);

        // Create 10 additional users
        User::factory(10)->create([
            'password' => $password,
        ]);
    }
}
