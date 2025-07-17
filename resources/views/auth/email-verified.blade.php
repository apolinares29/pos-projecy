<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Email Verified - POS System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-green-50 to-blue-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto">
            <div class="bg-white rounded-xl shadow-xl p-8 text-center">
                <!-- Success Icon -->
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>

                <!-- Success Message -->
                <h1 class="text-2xl font-bold text-gray-800 mb-4">Email Verified Successfully!</h1>
                <p class="text-gray-600 mb-6">
                    Hello <strong>{{ $user->first_name }}</strong>! Your email address has been verified. 
                    You can now log in to your account.
                </p>

                <!-- User Details -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <div class="text-sm text-gray-600">
                        <p><strong>Username:</strong> {{ $user->username }}</p>
                        <p><strong>Role:</strong> {{ ucfirst($user->role) }}</p>
                        <p><strong>Email:</strong> {{ $user->email }}</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-3">
                    <a 
                        href="/" 
                        class="w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:from-blue-600 hover:to-blue-700 transition-all duration-200 block"
                    >
                        Go to Login
                    </a>
                    
                    <a 
                        href="/dashboard/{{ $user->role }}" 
                        class="w-full bg-gradient-to-r from-green-500 to-green-600 text-white py-3 px-4 rounded-lg font-semibold hover:from-green-600 hover:to-green-700 transition-all duration-200 block"
                    >
                        Go to Dashboard
                    </a>
                </div>

                <!-- Additional Info -->
                <div class="mt-6 text-sm text-gray-500">
                    <p>Thank you for choosing our POS System!</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 