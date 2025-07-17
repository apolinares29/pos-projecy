<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit User - POS System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-red-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-semibold">Edit User</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('administrator.users') }}" class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg transition-colors">Back to Users</a>
                    <a href="/logout" class="bg-red-700 hover:bg-red-800 px-4 py-2 rounded-lg transition-colors">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-2xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-6">Edit User: {{ $user->first_name }} {{ $user->last_name }}</h2>

            @if($errors->any())
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('administrator.update-user', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                        <input type="text" name="first_name" id="first_name" 
                               value="{{ old('first_name', $user->first_name) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500"
                               required>
                    </div>

                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                        <input type="text" name="last_name" id="last_name" 
                               value="{{ old('last_name', $user->last_name) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500"
                               required>
                    </div>
                </div>

                <div class="mt-6">
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" name="username" id="username" 
                           value="{{ old('username', $user->username) }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500"
                           required>
                </div>

                <div class="mt-6">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" 
                           value="{{ old('email', $user->email) }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500"
                           required>
                </div>

                <div class="mt-6">
                    <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                    <select name="role" id="role" 
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500"
                            required>
                        <option value="">Select a role</option>
                        <option value="cashier" {{ old('role', $user->role) == 'cashier' ? 'selected' : '' }}>Cashier</option>
                        <option value="supervisor" {{ old('role', $user->role) == 'supervisor' ? 'selected' : '' }}>Supervisor</option>
                        <option value="administrator" {{ old('role', $user->role) == 'administrator' ? 'selected' : '' }}>Administrator</option>
                    </select>
                </div>

                <div class="mt-6">
                    <label for="password" class="block text-sm font-medium text-gray-700">New Password (Optional)</label>
                    <input type="password" name="password" id="password" 
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                    <p class="mt-1 text-sm text-gray-500">Leave blank to keep current password. Must be at least 8 characters if provided.</p>
                </div>

                <div class="mt-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" 
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                </div>

                <div class="mt-8 flex justify-end space-x-4">
                    <a href="{{ route('administrator.users') }}" 
                       class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors">
                        Update User
                    </button>
                </div>
            </form>
        </div>

        <!-- User Information -->
        <div class="mt-8 bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">User Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm font-medium text-gray-500">User ID</p>
                    <p class="text-sm text-gray-900">{{ $user->id }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Current Role</p>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                        @if($user->role === 'administrator') bg-red-100 text-red-800
                        @elseif($user->role === 'supervisor') bg-blue-100 text-blue-800
                        @else bg-green-100 text-green-800 @endif">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Email Verified</p>
                    <p class="text-sm text-gray-900">{{ $user->email_verified_at ? 'Yes' : 'No' }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Member Since</p>
                    <p class="text-sm text-gray-900">{{ $user->created_at->format('M d, Y') }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Last Updated</p>
                    <p class="text-sm text-gray-900">{{ $user->updated_at->format('M d, Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Sales</p>
                    <p class="text-sm text-gray-900">{{ $user->sales()->count() }}</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 