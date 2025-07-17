<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;

class SaleSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $products = Product::all();
        
        if ($users->isEmpty() || $products->isEmpty()) {
            return;
        }

        // Generate sales for the last 30 days
        for ($i = 0; $i < 1000; $i++) {
            $saleDate = Carbon::now()->subDays(rand(0, 30))->subHours(rand(0, 23))->subMinutes(rand(0, 59));
            
            // Random number of items per sale (1-5 items)
            $numItems = rand(1, 5);
            $selectedProducts = $products->random($numItems);
            
            $totalAmount = 0;
            $saleItems = [];
            
            // Calculate totals
            foreach ($selectedProducts as $product) {
                $quantity = rand(1, min(3, $product->stock_quantity));
                $itemTotal = $product->price * $quantity;
                $totalAmount += $itemTotal;
                
                $saleItems[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'unit_price' => $product->price,
                    'total_price' => $itemTotal
                ];
            }
            
            // Calculate tax and final amount
            $taxAmount = $totalAmount * 0.1; // 10% tax
            $discountAmount = rand(0, 1) ? rand(5, 50) : 0; // Random discount
            $finalAmount = $totalAmount + $taxAmount - $discountAmount;
            
            // Payment methods
            $paymentMethods = ['cash', 'card', 'mobile_payment'];
            $paymentMethod = $paymentMethods[array_rand($paymentMethods)];
            
            // Create sale record
            $sale = Sale::create([
                'transaction_number' => Sale::generateTransactionNumber(),
                'user_id' => $users->random()->id,
                'total_amount' => $totalAmount,
                'tax_amount' => $taxAmount,
                'discount_amount' => $discountAmount,
                'final_amount' => $finalAmount,
                'payment_method' => $paymentMethod,
                'status' => 'completed',
                'notes' => rand(0, 1) ? 'Customer requested receipt' : null,
                'created_at' => $saleDate,
                'updated_at' => $saleDate
            ]);
            
            // Create sale items
            foreach ($saleItems as $item) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product']->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['total_price'],
                    'created_at' => $saleDate,
                    'updated_at' => $saleDate
                ]);
                
                // Update product stock (simulate stock reduction)
                $item['product']->decrement('stock_quantity', $item['quantity']);
            }
        }
    }
} 