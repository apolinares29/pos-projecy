<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Product - POS System</title>
    <script src="https://cdn.tailwindcss.com"</script>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-red-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-semibold">Create New Product</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('administrator.products') }}" class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg transition-colors">Back to Products</a>
                    <a href="/logout" class="bg-red-700 hover:bg-red-800 px-4 py-2 rounded-lg transition-colors">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-2xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-6">Create New Product</h2>

            @if($errors->any())
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('administrator.store-product') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Product Name</label>
                        <input type="text" name="name" id="name" 
                               value="{{ old('name') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500"
                               required>
                    </div>

                    <div>
                        <label for="sku" class="block text-sm font-medium text-gray-700">SKU</label>
                        <input type="text" name="sku" id="sku" 
                               value="{{ old('sku') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500"
                               required>
                    </div>
                </div>

                <div class="mt-6">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="3"
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">{{ old('description') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700">Price ($)</label>
                        <input type="number" name="price" id="price" 
                               value="{{ old('price') }}"
                               min="0" step="0.01"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500"
                               required>
                    </div>

                    <div>
                        <label for="stock_quantity" class="block text-sm font-medium text-gray-700">Stock Quantity</label>
                        <input type="number" name="stock_quantity" id="stock_quantity" 
                               value="{{ old('stock_quantity', 0) }}"
                               min="0"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500"
                               required>
                    </div>

                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                        <select name="category" id="category" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500"
                                required>
                            <option value="">Select a category</option>
                            <option value="Electronics" {{ old('category') == 'Electronics' ? 'selected' : '' }}>Electronics</option>
                            <option value="Clothing" {{ old('category') == 'Clothing' ? 'selected' : '' }}>Clothing</option>
                            <option value="Books" {{ old('category') == 'Books' ? 'selected' : '' }}>Books</option>
                            <option value="Home & Garden" {{ old('category') == 'Home & Garden' ? 'selected' : '' }}>Home & Garden</option>
                            <option value="Sports" {{ old('category') == 'Sports' ? 'selected' : '' }}>Sports</option>
                            <option value="Automotive" {{ old('category') == 'Automotive' ? 'selected' : '' }}>Automotive</option>
                            <option value="Health & Beauty" {{ old('category') == 'Health & Beauty' ? 'selected' : '' }}>Health & Beauty</option>
                            <option value="Toys & Games" {{ old('category') == 'Toys & Games' ? 'selected' : '' }}>Toys & Games</option>
                            <option value="Food & Beverages" {{ old('category') == 'Food & Beverages' ? 'selected' : '' }}>Food & Beverages</option>
                            <option value="Other" {{ old('category') == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                </div>

                <div class="mt-8 flex justify-end space-x-4">
                    <a href="{{ route('administrator.products') }}" 
                       class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors">
                        Create Product
                    </button>
                </div>
            </form>
        </div>

        <!-- Product Guidelines -->
        <div class="mt-8 bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Product Creation Guidelines</h3>
            <div class="space-y-3 text-sm text-gray-600">
                <div class="flex items-start space-x-2">
                    <span class="text-red-500 mt-1">•</span>
                    <p><strong>Product Name:</strong> Use clear, descriptive names that customers will understand.</p>
                </div>
                <div class="flex items-start space-x-2">
                    <span class="text-red-500 mt-1">•</span>
                    <p><strong>SKU:</strong> Stock Keeping Unit should be unique and follow a consistent format (e.g., PROD-001).</p>
                </div>
                <div class="flex items-start space-x-2">
                    <span class="text-red-500 mt-1">•</span>
                    <p><strong>Description:</strong> Provide detailed information about the product features and specifications.</p>
                </div>
                <div class="flex items-start space-x-2">
                    <span class="text-red-500 mt-1">•</span>
                    <p><strong>Price:</strong> Enter the retail price in dollars and cents (e.g., 29.99).</p>
                </div>
                <div class="flex items-start space-x-2">
                    <span class="text-red-500 mt-1">•</span>
                    <p><strong>Stock Quantity:</strong> Enter the current available inventory count.</p>
                </div>
                <div class="flex items-start space-x-2">
                    <span class="text-red-500 mt-1">•</span>
                    <p><strong>Category:</strong> Choose the most appropriate category to help with inventory organization.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 