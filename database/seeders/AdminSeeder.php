<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Buat akun admin
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        // Buat data employee untuk admin
        Employee::create([
            'user_id' => $admin->id,
            'phone' => '08123456789',
            'address' => 'Alamat Admin'
        ]);

        // Buat akun user contoh
        $user = User::create([
            'name' => 'User Example',
            'email' => 'user@example.com',
            'password' => Hash::make('user123'),
            'role' => 'user'
        ]);

        Employee::create([
            'user_id' => $user->id,
            'phone' => '08987654321',
            'address' => 'Alamat User'
        ]);
    }
}