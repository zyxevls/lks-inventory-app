<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@inventory.test'],
            [
                'name' => 'Administrator',
                'password' => bcrypt('admin123##'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'user@inventory.test'],
            [
                'name' => 'User Demo',
                'password' => bcrypt('user123##'),
                'role' => 'user',
                'email_verified_at' => now(),
            ]
        );
    }
}
