<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdministratorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'first_name' => 'System',
            'last_name' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@possystem.com',
            'password' => Hash::make('admin123'),
            'role' => 'administrator',
            'email_verified_at' => now(),
        ]);

        $this->command->info('Administrator user created successfully!');
        $this->command->info('Username: admin');
        $this->command->info('Password: admin123');
    }
}
