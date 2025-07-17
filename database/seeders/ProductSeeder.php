<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        for ($i = 1; $i <= 1000; $i++) {
            Product::create([
                'name' => $faker->words(3, true) . " #$i",
                'description' => $faker->sentence(8),
                'price' => $faker->randomFloat(2, 10, 2000),
                'stock_quantity' => $faker->numberBetween(0, 200),
                'sku' => 'SKU' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'category' => $faker->randomElement(['Electronics', 'Accessories', 'Storage', 'Office', 'Home', 'Gaming'])
            ]);
        }
    }
} 