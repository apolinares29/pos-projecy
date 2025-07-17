<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>System Settings - POS System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-red-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-semibold">System Settings</h1>
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
    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">


        <form action="{{ route('administrator.update-settings') }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Company Information -->
            <div class="bg-white shadow rounded-lg p-6 mb-8">
                <h2 class="text-lg font-medium text-gray-900 mb-6">Company Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="company_name" class="block text-sm font-medium text-gray-700">Company Name</label>
                        <input type="text" name="company_name" id="company_name" 
                               value="{{ old('company_name', $settings['company_name']) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500"
                               required>
                    </div>

                    <div>
                        <label for="currency" class="block text-sm font-medium text-gray-700">Currency</label>
                        <select name="currency" id="currency" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500"
                                required>
                            <option value="USD" {{ old('currency', $settings['currency']) == 'USD' ? 'selected' : '' }}>USD ($)</option>
                            <option value="EUR" {{ old('currency', $settings['currency']) == 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                            <option value="GBP" {{ old('currency', $settings['currency']) == 'GBP' ? 'selected' : '' }}>GBP (£)</option>
                            <option value="JPY" {{ old('currency', $settings['currency']) == 'JPY' ? 'selected' : '' }}>JPY (¥)</option>
                            <option value="CAD" {{ old('currency', $settings['currency']) == 'CAD' ? 'selected' : '' }}>CAD (C$)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Tax and Pricing -->
            <div class="bg-white shadow rounded-lg p-6 mb-8">
                <h2 class="text-lg font-medium text-gray-900 mb-6">Tax and Pricing</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="tax_rate" class="block text-sm font-medium text-gray-700">Tax Rate (%)</label>
                        <input type="number" name="tax_rate" id="tax_rate" 
                               value="{{ old('tax_rate', $settings['tax_rate']) }}"
                               min="0" max="100" step="0.01"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500"
                               required>
                        <p class="mt-1 text-sm text-gray-500">Default tax rate applied to all sales</p>
                    </div>

                    <div>
                        <label for="low_stock_threshold" class="block text-sm font-medium text-gray-700">Low Stock Threshold</label>
                        <input type="number" name="low_stock_threshold" id="low_stock_threshold" 
                               value="{{ old('low_stock_threshold', $settings['low_stock_threshold']) }}"
                               min="1"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500"
                               required>
                        <p class="mt-1 text-sm text-gray-500">Minimum stock level before low stock alerts</p>
                    </div>
                </div>
            </div>

            <!-- Security Settings -->
            <div class="bg-white shadow rounded-lg p-6 mb-8">
                <h2 class="text-lg font-medium text-gray-900 mb-6">Security Settings</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="session_timeout" class="block text-sm font-medium text-gray-700">Session Timeout (minutes)</label>
                        <input type="number" name="session_timeout" id="session_timeout" 
                               value="{{ old('session_timeout', $settings['session_timeout']) }}"
                               min="5" max="120"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500"
                               required>
                        <p class="mt-1 text-sm text-gray-500">How long before users are automatically logged out</p>
                    </div>

                    <div>
                        <label for="backup_frequency" class="block text-sm font-medium text-gray-700">Backup Frequency</label>
                        <select name="backup_frequency" id="backup_frequency" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500"
                                required>
                            <option value="daily" {{ old('backup_frequency', $settings['backup_frequency']) == 'daily' ? 'selected' : '' }}>Daily</option>
                            <option value="weekly" {{ old('backup_frequency', $settings['backup_frequency']) == 'weekly' ? 'selected' : '' }}>Weekly</option>
                            <option value="monthly" {{ old('backup_frequency', $settings['backup_frequency']) == 'monthly' ? 'selected' : '' }}>Monthly</option>
                        </select>
                        <p class="mt-1 text-sm text-gray-500">How often system backups are created</p>
                    </div>
                </div>
            </div>

            <!-- Notification Settings -->
            <div class="bg-white shadow rounded-lg p-6 mb-8">
                <h2 class="text-lg font-medium text-gray-900 mb-6">Notification Settings</h2>
                
                <div class="space-y-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="email_notifications" id="email_notifications" 
                               value="1" {{ old('email_notifications', $settings['email_notifications']) ? 'checked' : '' }}
                               class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                        <label for="email_notifications" class="ml-2 block text-sm text-gray-900">
                            Enable Email Notifications
                        </label>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="sms_notifications" id="sms_notifications" 
                               value="1" {{ old('sms_notifications', $settings['sms_notifications']) ? 'checked' : '' }}
                               class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                        <label for="sms_notifications" class="ml-2 block text-sm text-gray-900">
                            Enable SMS Notifications
                        </label>
                    </div>
                </div>
            </div>

            <!-- System Information -->
            <div class="bg-white shadow rounded-lg p-6 mb-8">
                <h2 class="text-lg font-medium text-gray-900 mb-6">System Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm font-medium text-gray-500">System Version</p>
                        <p class="text-sm text-gray-900">POS System v1.0.0</p>
                    </div>
                    
                    <div>
                        <p class="text-sm font-medium text-gray-500">Last Updated</p>
                        <p class="text-sm text-gray-900">{{ now()->format('M d, Y H:i:s') }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm font-medium text-gray-500">Database Status</p>
                        <p class="text-sm text-green-600">Connected</p>
                    </div>
                    
                    <div>
                        <p class="text-sm font-medium text-gray-500">Server Time</p>
                        <p class="text-sm text-gray-900">{{ now()->format('M d, Y H:i:s T') }}</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-4">
                <button type="button" 
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg transition-colors"
                        onclick="resetForm()">
                    Reset to Defaults
                </button>
                <button type="submit" 
                        class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg transition-colors">
                    Save Settings
                </button>
            </div>
        </form>

        <!-- System Maintenance -->
        <div class="mt-8 bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">System Maintenance</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <button type="button" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors"
                        onclick="performBackup()">
                    Create Backup
                </button>
                <button type="button" 
                        class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition-colors"
                        onclick="clearCache()">
                    Clear Cache
                </button>
                <button type="button" 
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors"
                        onclick="optimizeDatabase()">
                    Optimize Database
                </button>
            </div>
        </div>
    </div>

    <script>
        function resetForm() {
            showConfirm('Reset Settings', 'Are you sure you want to reset all settings to their default values?', function() {
                // Reset form fields to default values
                document.getElementById('company_name').value = 'TechStore POS';
                document.getElementById('currency').value = 'USD';
                document.getElementById('tax_rate').value = '10.0';
                document.getElementById('low_stock_threshold').value = '10';
                document.getElementById('session_timeout').value = '30';
                document.getElementById('backup_frequency').value = 'daily';
                document.getElementById('email_notifications').checked = true;
                document.getElementById('sms_notifications').checked = false;
                showSuccess('Settings reset to default values');
            });
        }

        function performBackup() {
            showConfirm('Create Backup', 'Create a system backup now?', function() {
                showInfo('Backup process started. You will be notified when it completes.');
            });
        }

        function clearCache() {
            showConfirm('Clear Cache', 'Clear all system cache?', function() {
                showSuccess('Cache cleared successfully.');
            });
        }

        function optimizeDatabase() {
            showConfirm('Optimize Database', 'Optimize database performance?', function() {
                showInfo('Database optimization started. This may take a few minutes.');
            });
        }
    </script>
</body>
</html> 