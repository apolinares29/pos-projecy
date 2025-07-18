<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Default system settings
        $settings = [
            'currency' => 'PHP',
            'company_name' => 'TechStore POS',
            'tax_rate' => '10.0',
            'low_stock_threshold' => '10',
            'session_timeout' => '30',
            'backup_frequency' => 'daily',
            'email_notifications' => '1',
            'sms_notifications' => '0',
        ];

        foreach ($settings as $key => $value) {
            Setting::set($key, $value);
        }
    }
} 