<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administrator Dashboard - POS System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-purple-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-semibold">POS System - Administrator</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span>Welcome, {{ $user['username'] }}</span>
                    <a href="/register" class="bg-green-600 hover:bg-green-700 px-4 py-2 rounded-lg transition-colors">Register</a>
                    <a href="/logout" class="bg-purple-700 hover:bg-purple-800 px-4 py-2 rounded-lg transition-colors">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                                <span class="text-white text-lg">💰</span>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Sales</dt>
                                <dd class="text-lg font-medium text-gray-900">$45,231</dd>
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
                                <span class="text-white text-lg">👥</span>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Active Users</dt>
                                <dd class="text-lg font-medium text-gray-900">12</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                <span class="text-white text-lg">📦</span>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Products</dt>
                                <dd class="text-lg font-medium text-gray-900">1,234</dd>
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
                                <span class="text-white text-lg">📊</span>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Reports</dt>
                                <dd class="text-lg font-medium text-gray-900">25</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white shadow rounded-lg p-6 mb-8">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <button class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-3 rounded-lg transition-colors">
                    Manage Users
                </button>
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg transition-colors">
                    System Settings
                </button>
                <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg transition-colors">
                    Generate Reports
                </button>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Recent Activity</h2>
            <div class="space-y-4">
                <div class="flex items-center space-x-3">
                    <div class="w-2 h-2 bg-green-400 rounded-full"></div>
                    <span class="text-sm text-gray-600">New user "cashier01" was created</span>
                    <span class="text-xs text-gray-400">2 minutes ago</span>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="w-2 h-2 bg-blue-400 rounded-full"></div>
                    <span class="text-sm text-gray-600">System backup completed successfully</span>
                    <span class="text-xs text-gray-400">15 minutes ago</span>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="w-2 h-2 bg-yellow-400 rounded-full"></div>
                    <span class="text-sm text-gray-600">Inventory alert: Low stock for Product #123</span>
                    <span class="text-xs text-gray-400">1 hour ago</span>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 