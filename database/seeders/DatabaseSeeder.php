<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\Admin::create([
            'name' => 'Admin Saiful',
            'email' => 'saiful@gmail.com',
            'phone' => '0123456789',
            'password' => \Illuminate\Support\Facades\Hash::make('Admin123'),
        ]);

        $this->command->info('Admin account created: saiful@gmail.com / Admin123');
    }
}
