<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        \App\Models\User::truncate();
        // Sample supervisors
        User::create([
            'first_name' => 'Jane',
            'last_name' => 'Supervisor',
            'username' => 'supervisor1',
            'email' => 'supervisor1@possystem.com',
            'password' => Hash::make('supervisor123'),
            'role' => 'supervisor',
            'email_verified_at' => now(),
        ]);
        User::create([
            'first_name' => 'John',
            'last_name' => 'Supervisor',
            'username' => 'supervisor2',
            'email' => 'supervisor2@possystem.com',
            'password' => Hash::make('supervisor123'),
            'role' => 'supervisor',
            'email_verified_at' => now(),
        ]);

        // Sample cashiers
        User::create([
            'first_name' => 'Alice',
            'last_name' => 'Cashier',
            'username' => 'cashier1',
            'email' => 'cashier1@possystem.com',
            'password' => Hash::make('cashier123'),
            'role' => 'cashier',
            'email_verified_at' => now(),
        ]);
        User::create([
            'first_name' => 'Bob',
            'last_name' => 'Cashier',
            'username' => 'cashier2',
            'email' => 'cashier2@possystem.com',
            'password' => Hash::make('cashier123'),
            'role' => 'cashier',
            'email_verified_at' => now(),
        ]);

        // Bulk random cashiers
        \App\Models\User::factory()->count(10)->create(['role' => 'cashier']);
        // Bulk random supervisors
        \App\Models\User::factory()->count(5)->create(['role' => 'supervisor']);
    }
} 