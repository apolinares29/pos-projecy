<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Supervisor Dashboard - POS System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-blue-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-semibold">POS System - Supervisor</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span>Welcome, {{ $user['username'] }}</span>
                    <a href="/register" class="bg-green-600 hover:bg-green-700 px-4 py-2 rounded-lg transition-colors">Register</a>
                    <a href="/logout" class="bg-blue-700 hover:bg-blue-800 px-4 py-2 rounded-lg transition-colors">Logout</a>
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
                            <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                <span class="text-white text-lg">👥</span>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Team Members</dt>
                                <dd class="text-lg font-medium text-gray-900">8</dd>
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
                                <span class="text-white text-lg">📊</span>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Today's Sales</dt>
                                <dd class="text-lg font-medium text-gray-900">₱12,450</dd>
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
                                <span class="text-white text-lg">⚠️</span>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Pending Issues</dt>
                                <dd class="text-lg font-medium text-gray-900">3</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white shadow rounded-lg p-6 mb-8">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('supervisor.products') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg transition-colors text-center">
                    <div class="text-2xl mb-2">📦</div>
                    <div class="font-medium">Manage Products</div>
                </a>
                <a href="{{ route('supervisor.sales-reports') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-3 rounded-lg transition-colors text-center">
                    <div class="text-2xl mb-2">📊</div>
                    <div class="font-medium">Sales Reports</div>
                </a>
                <a href="{{ route('supervisor.low-stock') }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-3 rounded-lg transition-colors text-center">
                    <div class="text-2xl mb-2">⚠️</div>
                    <div class="font-medium">Monitor Stock</div>
                </a>
                <a href="{{ route('supervisor.price-override') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg transition-colors text-center">
                    <div class="text-2xl mb-2">💰</div>
                    <div class="font-medium">Price Override</div>
                </a>
            </div>
        </div>

        <!-- Team Activity -->
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Team Activity</h2>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <span class="text-green-600 text-sm font-medium">JD</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">John Doe</p>
                            <p class="text-xs text-gray-500">Cashier</p>
                        </div>
                    </div>
                    <div class="text-right">
                                                    <p class="text-sm font-medium text-gray-900">₱2,340</p>
                        <p class="text-xs text-gray-500">Today's sales</p>
                    </div>
                </div>
                
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <span class="text-blue-600 text-sm font-medium">JS</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Jane Smith</p>
                            <p class="text-xs text-gray-500">Cashier</p>
                        </div>
                    </div>
                    <div class="text-right">
                                                    <p class="text-sm font-medium text-gray-900">₱1,890</p>
                        <p class="text-xs text-gray-500">Today's sales</p>
                    </div>
                </div>
                
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                            <span class="text-purple-600 text-sm font-medium">MJ</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Mike Johnson</p>
                            <p class="text-xs text-gray-500">Cashier</p>
                        </div>
                    </div>
                    <div class="text-right">
                                                    <p class="text-sm font-medium text-gray-900">₱3,120</p>
                        <p class="text-xs text-gray-500">Today's sales</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('components.notifications')
    
    <script>
        // Enhanced supervisor dashboard notifications
        document.addEventListener('DOMContentLoaded', function() {
            showSuccess('Welcome back, {{ session("username", "Supervisor") }}!');
            
            // Auto-refresh dashboard data every 30 seconds
            setInterval(function() {
                fetch('{{ route("dashboard.supervisor") }}')
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newMetrics = doc.querySelector('.grid.grid-cols-1.md\\:grid-cols-3.gap-6');
                        const currentMetrics = document.querySelector('.grid.grid-cols-1.md\\:grid-cols-3.gap-6');
                        if (newMetrics && currentMetrics) {
                            currentMetrics.innerHTML = newMetrics.innerHTML;
                            showInfo('Dashboard data refreshed');
                        }
                    })
                    .catch(error => {
                        console.error('Error refreshing dashboard:', error);
                    });
            }, 30000);
        });
        
        // Enhanced quick actions
        function quickAction(action) {
            showInfo(`Performing ${action}...`);
            // Add specific action logic here
        }
    </script>
</body>
</html> 