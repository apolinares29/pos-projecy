<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Clear all products before seeding
        \App\Models\Product::query()->delete();

        // 2. Prepare your product names and unique stock arrays
        $faker = \Faker\Factory::create();
        $productNames = [
            'Logitech Wireless Mouse', // high
            'Apple Magic Keyboard',    // high
            'Samsung SSD 1TB',         // high
            'Sony Noise Cancelling Headphones', // high
            'Dell 24\" Monitor',       // high
            'HP LaserJet Printer',     // medium
            'Anker Power Bank',        // medium
            'Kingston USB Flash Drive',// medium
            'Razer Gaming Mousepad',   // medium
            'Canon DSLR Camera',       // medium
            'TP-Link WiFi Router',     // medium
            'Seagate External HDD',    // medium
            'Microsoft Surface Pen',   // low
            'JBL Bluetooth Speaker',   // low
            'Sandisk MicroSD Card',    // low
            'Corsair RAM 16GB',        // low
            'Asus Gaming Laptop',      // low
            'Epson Ink Cartridge',     // low
            'Philips LED Desk Lamp',   // low
            'Xiaomi Smart Watch'       // low
        ];

        // Assign demand levels
        $demandLevels = [
            'high'   => range(15, 21),
            'medium' => range(9, 14),
            'low'    => range(2, 8),
        ];
        shuffle($demandLevels['high']);
        shuffle($demandLevels['medium']);
        shuffle($demandLevels['low']);

        $demandMap = [
            0 => 'high', 1 => 'high', 2 => 'high', 3 => 'high', 4 => 'high',
            5 => 'medium', 6 => 'medium', 7 => 'medium', 8 => 'medium', 9 => 'medium', 10 => 'medium',
            11 => 'medium',
            12 => 'low', 13 => 'low', 14 => 'low', 15 => 'low', 16 => 'low', 17 => 'low', 18 => 'low', 19 => 'low'
        ];

        $usedStocks = range(10, 50); // Unique stock_quantity values
        shuffle($usedStocks);
        $existingSkus = [];

        $usedMaxStocks = range(30, 100); // Unique max_stock values
        shuffle($usedMaxStocks);

        // 3. Loop and create products
        foreach ($productNames as $i => $name) {
            $category = $faker->randomElement(['Electronics', 'Accessories', 'Storage', 'Office', 'Home', 'Gaming']);
            $catAbbr = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $category), 0, 3));
            $nameAbbr = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $name), 0, 5));
            $skuBase = $catAbbr . '-' . $nameAbbr . '-' . str_pad($i+1, 3, '0', STR_PAD_LEFT);
            $sku = $skuBase;
            // Ensure SKU is unique in memory and in the database
            while (in_array($sku, $existingSkus) || Product::where('sku', $sku)->exists()) {
                $sku = $skuBase . '-' . strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 2));
            }
            $existingSkus[] = $sku;

            $max_stock = $usedMaxStocks[$i];
            $stock_quantity = min($usedStocks[$i], $max_stock); // Ensure stock_quantity does not exceed max_stock
            // Assign low_stock based on demand as a percentage of max_stock
            $demand = $demandMap[$i];
            if ($demand === 'high') {
                $low_stock = (int) round($max_stock * (rand(30, 40) / 100));
            } elseif ($demand === 'medium') {
                $low_stock = (int) round($max_stock * (rand(20, 30) / 100));
            } else {
                $low_stock = (int) round($max_stock * (rand(10, 20) / 100));
            }
            // Ensure low_stock is not equal to stock_quantity
            if ($low_stock == $stock_quantity) {
                $low_stock = $low_stock == $max_stock ? max(1, $low_stock - 1) : $low_stock + 1;
            }

            \App\Models\Product::create([
                'name' => $name,
                'description' => $faker->sentence(8),
                'price' => $faker->randomFloat(2, 100, 2000),
                'stock_quantity' => $stock_quantity,
                'low_stock' => $low_stock,
                'max_stock' => $max_stock,
                'sku' => $sku,
                'category' => $category
            ]);
        }
        // Optionally, add more products with unique names/SKUs if needed
    }
} 