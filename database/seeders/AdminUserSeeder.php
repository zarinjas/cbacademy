<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@chefbullacademy.local',
            'password' => Hash::make('Admin!234'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Shared Learner',
            'email' => 'access@chefbullacademy.local',
            'password' => Hash::make('Learn!234'),
            'role' => 'learner',
        ]);
    }
}
