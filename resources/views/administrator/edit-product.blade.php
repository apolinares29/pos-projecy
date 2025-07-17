<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Product - POS System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-red-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-semibold">Edit Product</h1>
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
            <h2 class="text-lg font-medium text-gray-900 mb-6">Edit Product: {{ $product->name }}</h2>

            @if($errors->any())
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('administrator.update-product', $product->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Product Name</label>
                        <input type="text" name="name" id="name" 
                               value="{{ old('name', $product->name) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500"
                               required>
                    </div>

                    <div>
                        <label for="sku" class="block text-sm font-medium text-gray-700">SKU</label>
                        <input type="text" name="sku" id="sku" 
                               value="{{ old('sku', $product->sku) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500"
                               required>
                    </div>
                </div>

                <div class="mt-6">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="3"
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">{{ old('description', $product->description) }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700">Price ($)</label>
                        <input type="number" name="price" id="price" 
                               value="{{ old('price', $product->price) }}"
                               min="0" step="0.01"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500"
                               required>
                    </div>

                    <div>
                        <label for="stock_quantity" class="block text-sm font-medium text-gray-700">Stock Quantity</label>
                        <input type="number" name="stock_quantity" id="stock_quantity" 
                               value="{{ old('stock_quantity', $product->stock_quantity) }}"
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
                            <option value="Electronics" {{ old('category', $product->category) == 'Electronics' ? 'selected' : '' }}>Electronics</option>
                            <option value="Clothing" {{ old('category', $product->category) == 'Clothing' ? 'selected' : '' }}>Clothing</option>
                            <option value="Books" {{ old('category', $product->category) == 'Books' ? 'selected' : '' }}>Books</option>
                            <option value="Home & Garden" {{ old('category', $product->category) == 'Home & Garden' ? 'selected' : '' }}>Home & Garden</option>
                            <option value="Sports" {{ old('category', $product->category) == 'Sports' ? 'selected' : '' }}>Sports</option>
                            <option value="Automotive" {{ old('category', $product->category) == 'Automotive' ? 'selected' : '' }}>Automotive</option>
                            <option value="Health & Beauty" {{ old('category', $product->category) == 'Health & Beauty' ? 'selected' : '' }}>Health & Beauty</option>
                            <option value="Toys & Games" {{ old('category', $product->category) == 'Toys & Games' ? 'selected' : '' }}>Toys & Games</option>
                            <option value="Food & Beverages" {{ old('category', $product->category) == 'Food & Beverages' ? 'selected' : '' }}>Food & Beverages</option>
                            <option value="Other" {{ old('category', $product->category) == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                </div>

                <div class="mt-6">
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" 
                               value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                               class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-900">
                            Product is active and available for sale
                        </label>
                    </div>
                </div>

                <div class="mt-8 flex justify-end space-x-4">
                    <a href="{{ route('administrator.products') }}" 
                       class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors">
                        Update Product
                    </button>
                </div>
            </form>
        </div>

        <!-- Product Information -->
        <div class="mt-8 bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Product Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm font-medium text-gray-500">Product ID</p>
                    <p class="text-sm text-gray-900">{{ $product->id }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Current Status</p>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                        {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Sales</p>
                    <p class="text-sm text-gray-900">{{ $product->saleItems()->count() }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Created</p>
                    <p class="text-sm text-gray-900">{{ $product->created_at->format('M d, Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Last Updated</p>
                    <p class="text-sm text-gray-900">{{ $product->updated_at->format('M d, Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Discount</p>
                    <p class="text-sm text-gray-900">
                        @if($product->discount_percentage > 0)
                            {{ $product->discount_percentage }}%
                        @else
                            No discount
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 