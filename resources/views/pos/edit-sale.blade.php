<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Sale - POS System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-green-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-semibold">POS System - Edit Sale</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span>Welcome, {{ session('username', 'Guest') }}</span>
                    <a href="{{ route('pos.index') }}" class="bg-green-600 hover:bg-green-700 px-4 py-2 rounded-lg transition-colors">POS</a>
                    <a href="{{ route('pos.sales') }}" class="bg-purple-600 hover:bg-purple-700 px-4 py-2 rounded-lg transition-colors">Sales</a>
                    <a href="/logout" class="bg-green-700 hover:bg-green-800 px-4 py-2 rounded-lg transition-colors">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900">Edit Sale</h2>
            <p class="text-gray-600">Transaction: {{ $sale->transaction_number }}</p>
        </div>



        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Sale Details Form -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Sale Information</h3>
                
                <form action="{{ route('pos.update-sale', $sale->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Transaction Number</label>
                            <input type="text" value="{{ $sale->transaction_number }}" disabled 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                            <input type="text" value="{{ $sale->created_at->format('M d, Y g:i A') }}" disabled 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Cashier</label>
                            <input type="text" value="{{ $sale->user->username }}" disabled 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="completed" {{ $sale->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="pending" {{ $sale->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="cancelled" {{ $sale->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                            <select name="payment_method" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="cash" {{ $sale->payment_method === 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="card" {{ $sale->payment_method === 'card' ? 'selected' : '' }}>Card</option>
                                <option value="mobile_payment" {{ $sale->payment_method === 'mobile_payment' ? 'selected' : '' }}>Mobile Payment</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                            <textarea name="notes" rows="3" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                      placeholder="Add any notes about this sale...">{{ $sale->notes }}</textarea>
                        </div>

                        <div class="flex space-x-4">
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                                Update Sale
                            </button>
                            <a href="{{ route('pos.sales') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                                Cancel
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Sale Items -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Sale Items</h3>
                
                <div class="space-y-3">
                    @foreach($sale->saleItems as $item)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900">{{ $item->product->name }}</h4>
                                <p class="text-sm text-gray-600">{{ $item->product->sku }}</p>
                                <p class="text-sm text-gray-500">Quantity: {{ $item->quantity }}</p>
                            </div>
                            <div class="text-right">
                                                        <p class="text-sm text-gray-600">₱{{ number_format($item->unit_price, 2) }} each</p>
                        <p class="font-medium text-gray-900">₱{{ number_format($item->total_price, 2) }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Totals -->
                <div class="border-t pt-4 mt-4">
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal:</span>
                            <span class="font-medium">₱{{ number_format($sale->total_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tax (10%):</span>
                            <span class="font-medium">₱{{ number_format($sale->tax_amount, 2) }}</span>
                        </div>
                        @if($sale->discount_amount > 0)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Discount:</span>
                            <span class="font-medium">-₱{{ number_format($sale->discount_amount, 2) }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between text-lg font-bold border-t pt-2">
                            <span>Total:</span>
                            <span class="text-green-600">₱{{ number_format($sale->final_amount, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-6 space-y-2">
                    <a href="{{ route('pos.receipt', $sale->id) }}" target="_blank" 
                       class="block w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-center transition-colors">
                        Print Receipt
                    </a>
                </div>
            </div>
        </div>
    </div>
    @include('components.notifications')
    
    <script>
        // Enhanced edit sale notifications
        document.addEventListener('DOMContentLoaded', function() {
            showInfo('Editing sale: {{ $sale->transaction_number }}');
        });
        
        // Enhanced form submission
        document.getElementById('editSaleForm').addEventListener('submit', function(e) {
            showInfo('Updating sale...');
        });
        
        // Enhanced delete item functionality
        function confirmDeleteItem(itemId, itemName) {
            confirmDelete('Delete Item', `Are you sure you want to remove ${itemName} from this sale?`, function() {
                showInfo('Removing item...');
                window.location.href = `{{ url('pos/sale') }}/{{ $sale->id }}/item/${itemId}/delete`;
            });
        }
    </script>
</body>
</html> 