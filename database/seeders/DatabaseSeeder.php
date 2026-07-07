<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'ketuaRT',
            'email' => 'erte1026@gmail.com',
            'password' => Hash::make('ketuaRT'),
        ]);
        User::create([
            'name' => 'adminbesar',
            'email' => 'adminbesar@gmail.com',
            'password' => Hash::make('adminbesarsaja'),
        ]);
    }
}
