<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Price Override - Supervisor</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-blue-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-semibold">Price Override</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span>Welcome, {{ session('username', 'Supervisor') }}</span>
                    <a href="{{ route('supervisor.index') }}" class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg transition-colors">Dashboard</a>
                    <a href="{{ route('supervisor.products') }}" class="bg-green-600 hover:bg-green-700 px-4 py-2 rounded-lg transition-colors">Products</a>
                    <a href="/logout" class="bg-blue-700 hover:bg-blue-800 px-4 py-2 rounded-lg transition-colors">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900">Price Override</h2>
            <p class="text-gray-600">Adjust product prices and apply discounts</p>
        </div>



        <!-- Products Table -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Product Pricing</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Discount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Final Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($products as $product)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                    <div class="text-sm text-gray-500">{{ Str::limit($product->description, 50) }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $product->sku }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $product->category }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                ₱{{ number_format($product->price, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($product->discount_percentage > 0)
                                    <span class="text-red-600 font-medium">{{ $product->discount_percentage }}%</span>
                                @else
                                    <span class="text-gray-500">0%</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                @php
                                    $finalPrice = $product->price * (1 - $product->discount_percentage / 100);
                                @endphp
                                ₱{{ number_format($finalPrice, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick="openPriceModal({{ $product->id }}, '{{ $product->name }}', {{ $product->price }}, {{ $product->discount_percentage }})" 
                                        class="text-blue-600 hover:text-blue-900">Edit Price</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Price Update Modal -->
    <div id="priceModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Update Price</h3>
                <form id="priceForm" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Product</label>
                        <p id="productName" class="text-sm text-gray-900 font-medium"></p>
                    </div>
                    
                    <div class="mb-4">
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Base Price</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">₱</span>
                            </div>
                            <input type="number" name="price" id="price" step="0.01" min="0" required
                                   class="pl-7 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="discount_percentage" class="block text-sm font-medium text-gray-700 mb-2">Discount Percentage</label>
                        <div class="relative">
                            <input type="number" name="discount_percentage" id="discount_percentage" step="0.01" min="0" max="100"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">%</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Final Price Preview</label>
                        <p id="finalPricePreview" class="text-lg font-bold text-green-600">₱0.00</p>
                    </div>
                    
                    <div class="flex space-x-4">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                            Update Price
                        </button>
                        <button type="button" onclick="closePriceModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('components.notifications')
    
    <script>
        function openPriceModal(productId, productName, currentPrice, currentDiscount) {
            document.getElementById('productName').textContent = productName;
            document.getElementById('price').value = currentPrice;
            document.getElementById('discount_percentage').value = currentDiscount;
            document.getElementById('priceForm').action = '{{ url("supervisor/products") }}/' + productId + '/price';
            updateFinalPricePreview();
            document.getElementById('priceModal').classList.remove('hidden');
        }

        function closePriceModal() {
            document.getElementById('priceModal').classList.add('hidden');
        }

        function updateFinalPricePreview() {
            const price = parseFloat(document.getElementById('price').value) || 0;
            const discount = parseFloat(document.getElementById('discount_percentage').value) || 0;
            const finalPrice = price * (1 - discount / 100);
            document.getElementById('finalPricePreview').textContent = '₱' + finalPrice.toFixed(2);
        }

        // Update preview when price or discount changes
        document.addEventListener('DOMContentLoaded', function() {
            const priceInput = document.getElementById('price');
            const discountInput = document.getElementById('discount_percentage');
            
            if (priceInput && discountInput) {
                priceInput.addEventListener('input', updateFinalPricePreview);
                discountInput.addEventListener('input', updateFinalPricePreview);
            }
        });

        // Close modal when clicking outside
        document.getElementById('priceModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closePriceModal();
            }
        });
    </script>
</body>
</html> 