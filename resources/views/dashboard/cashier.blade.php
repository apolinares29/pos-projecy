<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cashier Dashboard - POS System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-green-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-semibold">POS System - Cashier</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span>Welcome, {{ session('username', $user['username'] ?? 'Guest') }}</span>
                    <a href="/register" class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg transition-colors">Register</a>
                    <a href="/logout" class="bg-green-700 hover:bg-green-800 px-4 py-2 rounded-lg transition-colors">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                <span class="text-white text-lg">💰</span>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Today's Sales</dt>
                                <dd class="text-lg font-medium text-gray-900">$1,234</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                <span class="text-white text-lg">🛒</span>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Transactions</dt>
                                <dd class="text-lg font-medium text-gray-900">45</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                                <span class="text-white text-lg">⭐</span>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Customer Rating</dt>
                                <dd class="text-lg font-medium text-gray-900">4.8/5</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white shadow rounded-lg p-6 mb-8">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <a href="{{ route('pos.index') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg transition-colors text-center">
                    <div class="text-2xl mb-2">🛒</div>
                    <div class="font-medium">Process Sales</div>
                </a>
                <a href="{{ route('pos.inventory') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg transition-colors text-center">
                    <div class="text-2xl mb-2">📦</div>
                    <div class="font-medium">View Inventory</div>
                </a>
                <a href="{{ route('pos.sales') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-3 rounded-lg transition-colors text-center">
                    <div class="text-2xl mb-2">📊</div>
                    <div class="font-medium">View Sales</div>
                </a>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Recent Transactions</h2>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <span class="text-green-600 text-sm font-medium">✓</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Transaction #1234</p>
                            <p class="text-xs text-gray-500">2 minutes ago</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-900">$45.67</p>
                        <p class="text-xs text-gray-500">Cash</p>
                    </div>
                </div>
                
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <span class="text-blue-600 text-sm font-medium">✓</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Transaction #1233</p>
                            <p class="text-xs text-gray-500">5 minutes ago</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-900">$89.99</p>
                        <p class="text-xs text-gray-500">Card</p>
                    </div>
                </div>
                
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                            <span class="text-purple-600 text-sm font-medium">✓</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Transaction #1232</p>
                            <p class="text-xs text-gray-500">8 minutes ago</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-900">$23.45</p>
                        <p class="text-xs text-gray-500">Cash</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 