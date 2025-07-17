<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to POS Mini System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col justify-between">
    <!-- Hero Section -->
    <section class="bg-blue-700 text-white py-16 px-4 text-center">
        <h1 class="text-4xl font-bold mb-4">Welcome to POS Mini System</h1>
        <p class="text-lg mb-6 max-w-2xl mx-auto">A modern, easy-to-use Point of Sale solution for your business. Manage products, sales, and analytics with ease and security.</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('login') }}" class="bg-white text-blue-700 font-semibold py-2 px-6 rounded-lg shadow hover:bg-blue-100 transition">Login</a>
            <a href="{{ route('register') }}" class="bg-blue-500 border border-white font-semibold py-2 px-6 rounded-lg hover:bg-blue-600 transition">Register</a>
        </div>
    </section>

    <!-- Product Catalog Preview -->
    <section class="py-12 px-4 max-w-6xl mx-auto w-full">
        <h2 class="text-2xl font-bold text-center mb-8 text-blue-700">Featured Products</h2>
        @if($products->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
            @foreach($products as $product)
            <div class="bg-white rounded-lg shadow hover:shadow-lg transition p-4 flex flex-col">
                <div class="h-40 flex items-center justify-center mb-4 bg-gray-100 rounded">
                    @if($product->image)
                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="object-contain h-36 max-w-full">
                    @else
                        <span class="text-gray-400">No Image</span>
                    @endif
                </div>
                <h3 class="text-lg font-semibold mb-1">{{ $product->name }}</h3>
                <div class="text-blue-700 font-bold text-xl mb-2">₱{{ number_format($product->price * (1 - $product->discount_percentage / 100), 2) }}</div>
                <p class="text-gray-600 text-sm flex-1">{{ Str::limit($product->description, 60) }}</p>
                <div class="mt-4">
                    <span class="inline-block bg-green-100 text-green-700 text-xs px-2 py-1 rounded">In Stock: {{ $product->stock_quantity }}</span>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center text-gray-500 py-12">No products available at the moment.</div>
        @endif
        <div class="flex justify-center mt-8">
            <a href="#" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition">View All Products</a>
        </div>
    </section>

    <!-- Features Section -->
    <section class="bg-white py-12 px-4">
        <h2 class="text-2xl font-bold text-center mb-8 text-blue-700">Why Choose POS Mini System?</h2>
        <div class="max-w-4xl mx-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
            <div class="flex flex-col items-center">
                <div class="bg-blue-100 text-blue-700 rounded-full p-4 mb-2">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 3h18v18H3V3z"/><path d="M8 8h8v8H8V8z"/></svg>
                </div>
                <h3 class="font-semibold mb-1">Easy Inventory</h3>
                <p class="text-gray-600 text-sm text-center">Track and manage your products effortlessly.</p>
            </div>
            <div class="flex flex-col items-center">
                <div class="bg-blue-100 text-blue-700 rounded-full p-4 mb-2">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3"/><circle cx="12" cy="12" r="10"/></svg>
                </div>
                <h3 class="font-semibold mb-1">Fast Checkout</h3>
                <p class="text-gray-600 text-sm text-center">Speedy and reliable sales processing for your customers.</p>
            </div>
            <div class="flex flex-col items-center">
                <div class="bg-blue-100 text-blue-700 rounded-full p-4 mb-2">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 20v-6"/><path d="M6 12l6-6 6 6"/></svg>
                </div>
                <h3 class="font-semibold mb-1">Analytics</h3>
                <p class="text-gray-600 text-sm text-center">Get insights and reports to grow your business.</p>
            </div>
            <div class="flex flex-col items-center">
                <div class="bg-blue-100 text-blue-700 rounded-full p-4 mb-2">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
                </div>
                <h3 class="font-semibold mb-1">Secure & Reliable</h3>
                <p class="text-gray-600 text-sm text-center">Your data is protected with industry-standard security.</p>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="bg-gray-100 py-12 px-4">
        <div class="max-w-3xl mx-auto text-center">
            <h2 class="text-2xl font-bold text-blue-700 mb-4">About POS Mini System</h2>
            <p class="text-gray-700 text-lg mb-2">POS Mini System is designed for small and medium businesses that want a simple, effective, and affordable way to manage sales and inventory. Our mission is to empower business owners with tools that are easy to use and deliver real results.</p>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-blue-900 text-white py-6 text-center mt-8">
        <div class="mb-2">&copy; {{ date('Y') }} POS Mini System. All rights reserved.</div>
        <div class="flex justify-center gap-4 text-blue-200 text-sm">
            <a href="#" class="hover:underline">Contact</a>
            <a href="#" class="hover:underline">Privacy Policy</a>
            <a href="#" class="hover:underline">Facebook</a>
        </div>
    </footer>
</body>
</html> 