<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Helpers\ActivityLogger;
use App\Models\ActivityLog;

class AdministratorController extends Controller
{
    public function index()
    {
        // Get system-wide statistics
        $totalUsers = User::count();
        $totalProducts = Product::count();
        $totalSales = Sale::count();
        $totalRevenue = Sale::completed()->sum('final_amount');
        
        // Get today's statistics
        $todaySales = Sale::today()->completed()->sum('final_amount');
        $todayTransactions = Sale::today()->completed()->count();
        $newUsersToday = User::whereDate('created_at', Carbon::today())->count();
        
        // Get system health metrics
        $lowStockProducts = Product::where('stock_quantity', '<=', 10)->where('stock_quantity', '>', 0)->count();
        $outOfStockProducts = Product::where('stock_quantity', 0)->count();
        $inactiveProducts = Product::where('is_active', false)->count();
        
        // Get user statistics
        $cashiers = User::where('role', 'cashier')->count();
        $supervisors = User::where('role', 'supervisor')->count();
        $administrators = User::where('role', 'administrator')->count();
        
        // Get recent activity
        $recentSales = Sale::with(['user', 'saleItems.product'])->latest()->take(10)->get();
        $recentUsers = User::latest()->take(5)->get();
        
        return view('administrator.index', compact(
            'totalUsers', 'totalProducts', 'totalSales', 'totalRevenue',
            'todaySales', 'todayTransactions', 'newUsersToday',
            'lowStockProducts', 'outOfStockProducts', 'inactiveProducts',
            'cashiers', 'supervisors', 'administrators',
            'recentSales', 'recentUsers'
        ));
    }

    public function users()
    {
        $users = User::withCount('sales')->get();
        return view('administrator.users', compact('users'));
    }

    public function createUser()
    {
        return view('administrator.create-user');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:cashier,supervisor,administrator',
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'email_verified_at' => now(),
        ]);
        // Log user creation
        ActivityLogger::log('create', 'User', $user->id, 'User created: ' . $user->username);

        return redirect()->route('administrator.users')->with('success', 'User created successfully!');
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('administrator.edit-user', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $old = $user->only([
            'first_name', 'last_name', 'username', 'email', 'role', 'email_verified_at'
        ]);
        
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'role' => 'required|in:cashier,supervisor,administrator',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = $request->only(['first_name', 'last_name', 'username', 'email', 'role']);
        
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        $new = $user->only([
            'first_name', 'last_name', 'username', 'email', 'role', 'email_verified_at'
        ]);
        $diffMsg = ActivityLogger::diff($old, $new);
        // Log user update with detailed diff
        ActivityLogger::log('update', 'User', $user->id, 'User updated: ' . $user->username . '. ' . $diffMsg);

        return redirect()->route('administrator.users')->with('success', 'User updated successfully!');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent deleting the last administrator
        if ($user->role === 'administrator' && User::where('role', 'administrator')->count() <= 1) {
            return redirect()->route('administrator.users')->with('error', 'Cannot delete the last administrator');
        }
        
        $user->delete();
        // Log user deletion
        ActivityLogger::log('delete', 'User', $id, 'User deleted.');

        return redirect()->route('administrator.users')->with('success', 'User deleted successfully!');
    }

    public function systemAnalytics()
    {
        // Get date range from request or default to last 30 days
        $startDate = request('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = request('end_date', Carbon::now()->format('Y-m-d'));
        
        // Sales analytics
        $salesByDate = Sale::selectRaw('DATE(created_at) as date, COUNT(*) as transactions, SUM(final_amount) as total_sales')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        // User activity
        $userActivity = Sale::selectRaw('users.username, users.role, COUNT(*) as transactions, SUM(sales.final_amount) as total_sales')
            ->join('users', 'sales.user_id', '=', 'users.id')
            ->whereBetween('sales.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->groupBy('users.id', 'users.username', 'users.role')
            ->orderBy('total_sales', 'desc')
            ->get();
        
        // Product performance
        $productPerformance = DB::table('sale_items')
            ->selectRaw('products.name, products.category, SUM(sale_items.quantity) as total_quantity, SUM(sale_items.total_price) as total_revenue')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->whereBetween('sales.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->groupBy('products.id', 'products.name', 'products.category')
            ->orderBy('total_quantity', 'desc')
            ->limit(15)
            ->get();
        
        // System metrics
        $systemMetrics = [
            'total_users' => User::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])->count(),
            'active_products' => Product::where('is_active', true)->count(), // Not filtered by date
            'total_sales' => Sale::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])->count(),
            'total_revenue' => Sale::completed()->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])->sum('final_amount'),
            'avg_sale_value' => Sale::completed()->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])->avg('final_amount'),
            'low_stock_items' => Product::where('stock_quantity', '<=', 10)->where('stock_quantity', '>', 0)
                ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])->count(),
            'out_of_stock_items' => Product::where('stock_quantity', 0)
                ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])->count(),
        ];
        
        // Monthly trends
        $monthlyTrends = Sale::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as transactions, SUM(final_amount) as revenue')
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->map(function($item) {
                $item->month_name = date('M', mktime(0, 0, 0, $item->month, 1));
                return $item;
            });
        
        return view('administrator.system-analytics', compact(
            'salesByDate', 'userActivity', 'productPerformance', 'systemMetrics', 'monthlyTrends', 'startDate', 'endDate'
        ));
    }

    public function systemLogs()
    {
        // Fetch logs from the database, most recent first
        $logs = ActivityLog::orderByDesc('timestamp')->limit(500)->get();
        return view('administrator.system-logs', compact('logs'));
    }

    public function exportLogsCsv()
    {
        $logs = ActivityLog::orderByDesc('timestamp')->limit(1000)->get();
        $filename = 'system_logs_' . date('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Timestamp', 'Level', 'Action', 'Target Type', 'Target ID', 'User', 'IP Address', 'Message']);
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->timestamp ?? $log->created_at,
                    $log->level,
                    $log->action_type,
                    $log->target_type,
                    $log->target_id,
                    $log->username,
                    $log->ip_address,
                    $log->message,
                ]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function exportLogsJson()
    {
        $logs = ActivityLog::orderByDesc('timestamp')->limit(1000)->get();
        $filename = 'system_logs_' . date('Y-m-d_H-i-s') . '.json';
        return response()->json($logs)
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Content-Type', 'application/json');
    }

    public function clearOldLogs()
    {
        $deleted = ActivityLog::where('timestamp', '<', now()->subDays(30))->delete();
        ActivityLogger::log('delete', 'ActivityLog', null, 'Admin cleared old logs. ' . $deleted . ' log(s) deleted.');
        return redirect()->route('administrator.logs')->with('success', 'Old logs cleared successfully! Logs older than 30 days have been removed.');
    }

    public function systemSettings()
    {
        // In a real application, you would have a settings table
        // For now, we'll create some sample settings
        $settings = [
            'company_name' => 'TechStore POS',
            'tax_rate' => 10.0,
            'currency' => 'USD',
            'low_stock_threshold' => 10,
            'session_timeout' => 30,
            'backup_frequency' => 'daily',
            'email_notifications' => true,
            'sms_notifications' => false,
        ];
        
        return view('administrator.system-settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'currency' => 'required|string|max:10',
            'low_stock_threshold' => 'required|integer|min:1',
            'session_timeout' => 'required|integer|min:5|max:120',
            'backup_frequency' => 'required|in:daily,weekly,monthly',
            'email_notifications' => 'boolean',
            'sms_notifications' => 'boolean',
        ]);

        // In a real application, you would save these to a settings table
        // For now, we'll just redirect with a success message
        
        return redirect()->route('administrator.system-settings')->with('success', 'System settings updated successfully!');
    }

    // Inherit all supervisor functions
    public function products()
    {
        $products = Product::withCount('saleItems')->get();
        return view('administrator.products', compact('products'));
    }

    public function createProduct()
    {
        return view('administrator.create-product');
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'sku' => 'required|string|unique:products,sku',
            'category' => 'required|string|max:255',
        ]);

        $product = Product::create($request->all());
        // Log product creation
        ActivityLogger::log('create', 'Product', $product->id, 'Product created: ' . $product->name);

        return redirect()->route('administrator.products')->with('success', 'Product created successfully!');
    }

    public function editProduct($id)
    {
        $product = Product::findOrFail($id);
        return view('administrator.edit-product', compact('product'));
    }

    public function updateProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $old = $product->only([
            'name', 'description', 'price', 'discount_percentage', 'stock_quantity', 'sku', 'category', 'is_active', 'image'
        ]);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'sku' => 'required|string|unique:products,sku,' . $id,
            'category' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $product->update($request->all());
        $new = $product->only([
            'name', 'description', 'price', 'discount_percentage', 'stock_quantity', 'sku', 'category', 'is_active', 'image'
        ]);
        $diffMsg = ActivityLogger::diff($old, $new);
        // Log product update with detailed diff
        ActivityLogger::log('update', 'Product', $product->id, 'Product updated: ' . $product->name . '. ' . $diffMsg);

        return redirect()->route('administrator.products')->with('success', 'Product updated successfully!');
    }

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        // Log product deletion
        ActivityLogger::log('delete', 'Product', $id, 'Product deleted.');

        return redirect()->route('administrator.products')->with('success', 'Product deleted successfully!');
    }

    public function salesReports()
    {
        $startDate = request('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = request('end_date', Carbon::now()->format('Y-m-d'));
        
        $salesByDate = Sale::selectRaw('DATE(created_at) as date, COUNT(*) as transactions, SUM(final_amount) as total_sales')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        $salesByCashier = Sale::selectRaw('users.username, COUNT(*) as transactions, SUM(sales.final_amount) as total_sales')
            ->join('users', 'sales.user_id', '=', 'users.id')
            ->whereBetween('sales.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->groupBy('users.id', 'users.username')
            ->orderBy('total_sales', 'desc')
            ->get();
        
        $topProducts = DB::table('sale_items')
            ->selectRaw('products.name, products.sku, SUM(sale_items.quantity) as total_quantity, SUM(sale_items.total_price) as total_revenue')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->whereBetween('sales.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->groupBy('products.id', 'products.name', 'products.sku')
            ->orderBy('total_quantity', 'desc')
            ->limit(10)
            ->get();
        
        $paymentMethods = Sale::selectRaw('payment_method, COUNT(*) as count, SUM(final_amount) as total')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->groupBy('payment_method')
            ->get();
        
        return view('administrator.sales-reports', compact('salesByDate', 'salesByCashier', 'topProducts', 'paymentMethods', 'startDate', 'endDate'));
    }

    public function lowStock()
    {
        $lowStockProducts = Product::where('stock_quantity', '<=', 10)
            ->where('stock_quantity', '>', 0)
            ->orderBy('stock_quantity')
            ->get();
            
        $outOfStockProducts = Product::where('stock_quantity', 0)->get();
        
        return view('administrator.low-stock', compact('lowStockProducts', 'outOfStockProducts'));
    }

    public function updateStock(Request $request, $id)
    {
        $request->validate([
            'stock_quantity' => 'required|integer|min:0',
        ]);

        $product = Product::findOrFail($id);
        $product->update(['stock_quantity' => $request->stock_quantity]);

        return redirect()->route('administrator.low-stock')->with('success', 'Stock updated successfully');
    }

    public function priceOverride()
    {
        $products = Product::active()->get();
        return view('administrator.price-override', compact('products'));
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

        return redirect()->route('administrator.price-override')->with('success', 'Price updated successfully');
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
        
        return view('administrator.team-performance', compact('performanceData'));
    }

    public function sales()
    {
        $sales = Sale::with(['user', 'saleItems.product'])
            ->latest()
            ->paginate(20);
        
        $todaySales = Sale::today()->completed()->sum('final_amount');
        $todayTransactions = Sale::today()->completed()->count();
        
        return view('administrator.sales', compact('sales', 'todaySales', 'todayTransactions'));
    }
} 