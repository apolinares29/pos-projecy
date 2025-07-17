<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Helpers\ActivityLogger;
use Illuminate\Support\Facades\Auth;

class POSController extends Controller
{
    public function index()
    {
        $products = Product::active()->inStock()->get();
        $recentSales = Sale::with('user')->latest()->take(5)->get();
        
        return view('pos.index', compact('products', 'recentSales'));
    }

    public function processSale(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:cash,card,mobile_payment',
            'notes' => 'nullable|string',
            'card_number' => 'required_if:payment_method,card|nullable|string|regex:/^[0-9]{12,19}$/',
            'mobile_payment_reference' => 'required_if:payment_method,mobile_payment|nullable|string|max:32',
            'amount_tendered' => 'nullable|numeric|min:0',
        ], [
            'card_number.required_if' => 'Card number is required for card payments.',
            'card_number.regex' => 'Card number must be 12-19 digits.',
            'mobile_payment_reference.required_if' => 'Reference number is required for mobile payments.'
        ]);

        try {
            DB::beginTransaction();

            $totalAmount = 0;
            $items = [];

            // Calculate totals and validate stock
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                
                if ($product->stock_quantity < $item['quantity']) {
                    throw new \Exception("Insufficient stock for {$product->name}");
                }

                $unitPrice = $product->price * (1 - $product->discount_percentage / 100);
                $itemTotal = $unitPrice * $item['quantity'];
                $totalAmount += $itemTotal;

                $items[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'unit_price' => $unitPrice,
                    'total_price' => $itemTotal
                ];
            }

            // Calculate tax and final amount (simplified tax calculation)
            $taxAmount = $totalAmount * 0.1; // 10% tax
            $finalAmount = $totalAmount + $taxAmount;

            // Get user from session
            $userId = session('user_id');
            $username = session('username');
            if (!$userId || !$username) {
                throw new \Exception('User session not found. Please log in again.');
            }
            $user = User::where('id', $userId)->where('username', $username)->first();
            if (!$user) {
                throw new \Exception('Authenticated user not found. Please log in again.');
            }

            // Create sale record
            $extraNotes = $request->notes;
            if ($request->payment_method === 'card' && $request->card_number) {
                $last4 = substr(preg_replace('/\D/', '', $request->card_number), -4);
                $extraNotes = trim(($extraNotes ? $extraNotes . ' ' : '') . '(Card ending in ' . $last4 . ')');
            }
            if ($request->payment_method === 'mobile_payment' && $request->mobile_payment_reference) {
                $extraNotes = trim(($extraNotes ? $extraNotes . ' ' : '') . '(Mobile Ref: ' . $request->mobile_payment_reference . ')');
            }
            // For cash, validate amount tendered and add to notes
            if ($request->payment_method === 'cash' && $request->amount_tendered !== null) {
                if ($request->amount_tendered < $finalAmount) {
                    throw new \Exception('Amount tendered is insufficient.');
                }
                $change = $request->amount_tendered - $finalAmount;
                $extraNotes = trim(($extraNotes ? $extraNotes . ' ' : '') . '(Cash: Tendered ₱' . number_format($request->amount_tendered,2) . ', Change ₱' . number_format($change,2) . ')');
            }
            $sale = Sale::create([
                'transaction_number' => Sale::generateTransactionNumber(),
                'user_id' => $user->id,
                'total_amount' => $totalAmount,
                'tax_amount' => $taxAmount,
                'discount_amount' => 0,
                'final_amount' => $finalAmount,
                'payment_method' => $request->payment_method,
                'status' => 'completed',
                'notes' => $extraNotes
            ]);

            // Create sale items and update stock
            foreach ($items as $item) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product']->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['total_price']
                ]);

                // Update product stock
                $item['product']->decrement('stock_quantity', $item['quantity']);
            }

            DB::commit();
            // Log sale creation
            ActivityLogger::log('create', 'Sale', $sale->id, 'Sale processed successfully. Transaction: ' . $sale->transaction_number);
            return response()->json([
                'success' => true,
                'sale_id' => $sale->id,
                'transaction_number' => $sale->transaction_number,
                'message' => 'Sale processed successfully!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            // Log sale creation error
            ActivityLogger::log('error', 'Sale', null, 'Sale processing failed: ' . $e->getMessage(), 'ERROR');
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function printReceipt($saleId)
    {
        $sale = Sale::with(['saleItems.product', 'user'])->findOrFail($saleId);
        
        return view('pos.receipt', compact('sale'));
    }

    public function inventory()
    {
        $products = Product::withCount('saleItems')->paginate(20);
        
        return view('pos.inventory', compact('products'));
    }

    public function sales()
    {
        $sales = Sale::with(['user', 'saleItems.product'])
            ->latest()
            ->paginate(20);
        
        $todaySales = Sale::today()->completed()->sum('final_amount');
        $todayTransactions = Sale::today()->completed()->count();
        
        return view('pos.sales', compact('sales', 'todaySales', 'todayTransactions'));
    }

    public function editSale($id)
    {
        $sale = Sale::with(['saleItems.product', 'user'])->findOrFail($id);
        $products = Product::active()->get();
        
        return view('pos.edit-sale', compact('sale', 'products'));
    }

    public function updateSale(Request $request, $id)
    {
        $sale = Sale::findOrFail($id);
        $old = $sale->only(['status', 'payment_method', 'notes']);
        
        $request->validate([
            'status' => 'required|in:completed,pending,cancelled',
            'payment_method' => 'required|in:cash,card,mobile_payment',
            'notes' => 'nullable|string'
        ]);

        $sale->update($request->only(['status', 'payment_method', 'notes']));
        $new = $sale->only(['status', 'payment_method', 'notes']);
        $diffMsg = ActivityLogger::diff($old, $new);
        // Only log detailed diff if admin
        if (Auth::check() && Auth::user()->role === 'administrator') {
            ActivityLogger::log('update', 'Sale', $sale->id, 'Sale updated. ' . $diffMsg);
        } else {
            ActivityLogger::log('update', 'Sale', $sale->id, 'Sale updated.');
        }
        return redirect()->route('pos.sales')->with('success', 'Sale updated successfully!');
    }

    public function deleteSale($id)
    {
        $sale = Sale::findOrFail($id);
        
        // Restore stock if sale is cancelled
        if ($sale->status === 'completed') {
            foreach ($sale->saleItems as $item) {
                $item->product->increment('stock_quantity', $item->quantity);
            }
        }
        
        $sale->delete();
        // Log sale deletion
        ActivityLogger::log('delete', 'Sale', $id, 'Sale deleted.');
        return redirect()->route('pos.sales')->with('success', 'Sale deleted successfully!');
    }

    public function searchProducts(Request $request)
    {
        $query = $request->get('query');
        
        $products = Product::active()
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('sku', 'like', "%{$query}%")
                  ->orWhere('category', 'like', "%{$query}%");
            })
            ->inStock()
            ->get()
            ->map(function($product) {
                $product->final_price = $product->price * (1 - $product->discount_percentage / 100);
                return $product;
            });

        return response()->json($products);
    }
} 