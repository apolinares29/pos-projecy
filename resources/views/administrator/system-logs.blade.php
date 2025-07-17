<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>System Logs - POS System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-red-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-semibold">System Logs</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('administrator.index') }}" class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg transition-colors">Dashboard</a>
                    <a href="{{ route('administrator.analytics') }}" class="bg-purple-600 hover:bg-purple-700 px-4 py-2 rounded-lg transition-colors">Analytics</a>
                    <a href="/logout" class="bg-red-700 hover:bg-red-800 px-4 py-2 rounded-lg transition-colors">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Log Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                <span class="text-white text-lg">ℹ️</span>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Info Logs</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $logs->where('level', 'INFO')->count() }}</dd>
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
                                <dt class="text-sm font-medium text-gray-500 truncate">Warning Logs</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $logs->where('level', 'WARNING')->count() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-red-500 rounded-md flex items-center justify-center">
                                <span class="text-white text-lg">❌</span>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Error Logs</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $logs->where('level', 'ERROR')->count() }}</dd>
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
                                <span class="text-white text-lg">📋</span>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Logs</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $logs->count() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Log Filters -->
        <div class="bg-white shadow rounded-lg p-6 mb-8">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Log Filters</h2>
            <div class="flex flex-wrap gap-4">
                <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors" onclick="filterLogs('all')">
                    All Logs
                </button>
                <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors" onclick="filterLogs('INFO')">
                    Info Only
                </button>
                <button class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition-colors" onclick="filterLogs('WARNING')">
                    Warnings Only
                </button>
                <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors" onclick="filterLogs('ERROR')">
                    Errors Only
                </button>
            </div>
        </div>

        <!-- Logs Table -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">System Activity Logs</h2>
                <p class="mt-1 text-sm text-gray-500">Recent system events and activities</p>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Timestamp</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Level</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Message</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="logsTableBody">
                        @foreach($logs as $log)
                        <tr class="log-row hover:bg-gray-50" data-level="{{ $log->level }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $log->timestamp ? $log->timestamp->format('M d, Y H:i:s') : $log->created_at->format('M d, Y H:i:s') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    @if($log->level === 'ERROR') bg-red-100 text-red-800
                                    @elseif($log->level === 'WARNING') bg-yellow-100 text-yellow-800
                                    @else bg-green-100 text-green-800 @endif">
                                    {{ $log->level }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div class="max-w-md">
                                    {{ $log->message }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($log->username === 'system')
                                    <span class="text-gray-500">System</span>
                                @else
                                    {{ $log->username }}
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $log->ip_address }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Log Export -->
        <div class="mt-8 bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Log Management</h3>
            <div class="flex space-x-4">
                <button onclick="exportLogs('csv')" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                    Export Logs (CSV)
                </button>
                <button onclick="exportLogs('json')" 
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                    Export Logs (JSON)
                </button>
                <button type="button" 
                        class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition-colors"
                        onclick="clearOldLogs()">
                    Clear Old Logs
                </button>
            </div>
        </div>

        <!-- Log Information -->
        <div class="mt-8 bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Log Level Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-green-600 text-sm font-medium">ℹ️</span>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">INFO</h4>
                        <p class="text-sm text-gray-500">General information about system operations, user actions, and successful transactions.</p>
                    </div>
                </div>
                
                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-yellow-600 text-sm font-medium">⚠️</span>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">WARNING</h4>
                        <p class="text-sm text-gray-500">Potential issues that don't stop operation but should be monitored, like low stock alerts.</p>
                    </div>
                </div>
                
                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-red-600 text-sm font-medium">❌</span>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">ERROR</h4>
                        <p class="text-sm text-gray-500">Critical issues that may affect system functionality, like database connection failures.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function filterLogs(level) {
            const rows = document.querySelectorAll('.log-row');
            
            rows.forEach(row => {
                if (level === 'all' || row.dataset.level === level) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        function exportLogs(format) {
            const url = format === 'csv' 
                ? '{{ route("administrator.export-logs-csv") }}'
                : '{{ route("administrator.export-logs-json") }}';
            
            // Create a temporary link and trigger download
            const link = document.createElement('a');
            link.href = url;
            link.download = `system-logs.${format}`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            showSuccess(`Logs exported successfully as ${format.toUpperCase()} file`);
        }

        function clearOldLogs() {
            showConfirm('Clear Old Logs', 'Are you sure you want to clear old logs? This action cannot be undone.', function() {
                // Create a form and submit it
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("administrator.clear-old-logs") }}';
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                form.appendChild(csrfToken);
                document.body.appendChild(form);
                form.submit();
            });
        }
    </script>
</body>
</html> 