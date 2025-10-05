<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Administrator',
                'email' => 'admin@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Petugas',
                'email' => 'petugas@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('petugas123'),
                'role' => 'petugas',
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tamu',
                'email' => 'tamu@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('tamu123'),
                'role' => 'tamu',
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('users')->insert($users);
    }
}
