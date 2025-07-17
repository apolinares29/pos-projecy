<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Helpers\ActivityLogger;

class SupervisorController extends Controller
{
    public function index()
    {
        // Get today's statistics
        $todaySales = Sale::today()->completed()->sum('final_amount');
        $todayTransactions = Sale::today()->completed()->count();
        $lowStockProducts = Product::where('stock_quantity', '<=', 10)->where('stock_quantity', '>', 0)->count();
        $outOfStockProducts = Product::where('stock_quantity', 0)->count();
        
        // Get team members (cashiers)
        $teamMembers = User::where('role', 'cashier')->get();
        
        // Get recent sales
        $recentSales = Sale::with(['user', 'saleItems.product'])->latest()->take(10)->get();
        
        return view('supervisor.index', compact('todaySales', 'todayTransactions', 'lowStockProducts', 'outOfStockProducts', 'teamMembers', 'recentSales'));
    }

    public function products()
    {
        $products = Product::withCount('saleItems')->paginate(20);
        return view('supervisor.products', compact('products'));
    }

    public function createProduct()
    {
        return view('supervisor.create-product');
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'category' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = uniqid('prod_') . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('products'), $imageName);
            $imagePath = 'products/' . $imageName;
        }

        // SKU auto-generation logic
        $category = strtolower($request->category);
        $categoryCodes = [
            'cellphone' => 'PHN',
            'laptop' => 'LPT',
            'television' => 'TVS',
            'accessories' => 'ACC',
            'storage' => 'STR',
            'office' => 'OFF',
            'home' => 'HOM',
            'gaming' => 'GMG',
        ];
        $prefix = $categoryCodes[$category] ?? strtoupper(substr($category, 0, 3));
        $count = \App\Models\Product::where('sku', 'like', $prefix . '%')->count() + 1;
        $sku = $prefix . str_pad($count, 3, '0', STR_PAD_LEFT);

        $product = \App\Models\Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
            'sku' => $sku,
            'category' => $request->category,
            'is_active' => true,
            'image' => $imagePath,
        ]);
        // Log product creation
        ActivityLogger::log('create', 'Product', $product->id, 'Product created: ' . $product->name);

        return redirect()->route('supervisor.products')->with('success', 'Product created successfully!');
    }

    public function editProduct($id)
    {
        $product = Product::findOrFail($id);
        return view('supervisor.edit-product', compact('product'));
    }

    public function updateProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'category' => 'required|string|max:255',
            'is_active' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Handle image upload
        $imagePath = $product->image; // Keep existing image by default
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = uniqid('prod_') . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('products'), $imageName);
            $imagePath = 'products/' . $imageName;
        }

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
            'category' => $request->category,
            'is_active' => $request->has('is_active'),
            'image' => $imagePath,
        ]);
        
        // Log product update
        ActivityLogger::log('update', 'Product', $product->id, 'Product updated: ' . $product->name);

        return redirect()->route('supervisor.products')->with('success', 'Product updated successfully!');
    }

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        // Log product deletion
        ActivityLogger::log('delete', 'Product', $id, 'Product deleted.');

        return redirect()->route('supervisor.products')->with('success', 'Product deleted successfully!');
    }

    public function salesReports()
    {
        // Get date range from request or default to last 30 days
        $startDate = request('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = request('end_date', Carbon::now()->format('Y-m-d'));
        
        // Sales by date
        $salesByDate = Sale::selectRaw('DATE(created_at) as date, COUNT(*) as transactions, SUM(final_amount) as total_sales')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        // Sales by cashier
        $salesByCashier = Sale::selectRaw('users.username, COUNT(*) as transactions, SUM(sales.final_amount) as total_sales')
            ->join('users', 'sales.user_id', '=', 'users.id')
            ->whereBetween('sales.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->groupBy('users.id', 'users.username')
            ->orderBy('total_sales', 'desc')
            ->get();
        
        // Top selling products
        $topProducts = DB::table('sale_items')
            ->selectRaw('products.name, products.sku, SUM(sale_items.quantity) as total_quantity, SUM(sale_items.total_price) as total_revenue')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->whereBetween('sales.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->groupBy('products.id', 'products.name', 'products.sku')
            ->orderBy('total_quantity', 'desc')
            ->limit(10)
            ->get();
        
        // Payment method breakdown
        $paymentMethods = Sale::selectRaw('payment_method, COUNT(*) as count, SUM(final_amount) as total')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->groupBy('payment_method')
            ->get();
        
        return view('supervisor.sales-reports', compact('salesByDate', 'salesByCashier', 'topProducts', 'paymentMethods', 'startDate', 'endDate'));
    }

    public function lowStock()
    {
        $lowStockProducts = Product::where('stock_quantity', '<=', 10)
            ->where('stock_quantity', '>', 0)
            ->orderBy('stock_quantity')
            ->get();
            
        $outOfStockProducts = Product::where('stock_quantity', 0)->get();
        
        return view('supervisor.low-stock', compact('lowStockProducts', 'outOfStockProducts'));
    }

    public function updateStock(Request $request, $id)
    {
        $request->validate([
            'stock_quantity' => 'required|integer|min:0',
        ]);

        $product = Product::findOrFail($id);
        $product->update(['stock_quantity' => $request->stock_quantity]);

        return redirect()->route('supervisor.low-stock')->with('success', 'Stock updated successfully');
    }

    public function priceOverride()
    {
        $products = Product::active()->get();
        return view('supervisor.price-override', compact('products'));
    }

    public function updatePrice(Request $request, $id)
    {
        $request->validate([
            'price' => 'required|numeric|min:0',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        $product = Product::findOrFail($id);
        $product->update([
            'price' => $request->price,
            'discount_percentage' => $request->discount_percentage ?? 0,
        ]);

        return redirect()->route('supervisor.price-override')->with('success', 'Price updated successfully');
    }

    public function teamPerformance()
    {
        $cashiers = User::where('role', 'cashier')->get();
        
        $performanceData = collect();
        foreach ($cashiers as $cashier) {
            $todaySales = Sale::where('user_id', $cashier->id)
                ->today()
                ->completed()
                ->sum('final_amount');
                
            $todayTransactions = Sale::where('user_id', $cashier->id)
                ->today()
                ->completed()
                ->count();
                
            $monthlySales = Sale::where('user_id', $cashier->id)
                ->whereMonth('created_at', Carbon::now()->month)
                ->completed()
                ->sum('final_amount');
                
            $performanceData->push([
                'cashier' => $cashier,
                'today_sales' => $todaySales,
                'today_transactions' => $todayTransactions,
                'monthly_sales' => $monthlySales,
            ]);
        }
        
        return view('supervisor.team-performance', compact('performanceData'));
    }

    public function sales()
    {
        $sales = Sale::with(['user', 'saleItems.product'])
            ->latest()
            ->paginate(20);
        
        $todaySales = Sale::today()->completed()->sum('final_amount');
        $todayTransactions = Sale::today()->completed()->count();
        
        return view('supervisor.sales', compact('sales', 'todaySales', 'todayTransactions'));
    }
} 