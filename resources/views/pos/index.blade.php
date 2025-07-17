@extends('layouts.app')

@section('title', 'POS System - Process Sales')

@section('styles')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endsection

@section('content')
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-green-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-semibold">POS System - Process Sales</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span>Welcome, {{ session('username', 'Guest') }}</span>
                    <a href="{{ route('pos.inventory') }}" class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg transition-colors">Inventory</a>
                    <a href="{{ route('pos.sales') }}" class="bg-purple-600 hover:bg-purple-700 px-4 py-2 rounded-lg transition-colors">Sales</a>
                    <a href="/logout" class="bg-green-700 hover:bg-green-800 px-4 py-2 rounded-lg transition-colors">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Product Search and Selection -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Product Search</h2>
                    
                    <!-- Search Bar -->
                    <div class="mb-6">
                        <input type="text" id="productSearch" placeholder="Search products by name, SKU, or category..." 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <!-- Product Grid -->
                    <div id="productGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($products as $product)
                        <div class="product-card border border-gray-200 rounded-lg p-4 cursor-pointer hover:shadow-md transition-shadow" 
                             data-product-id="{{ $product->id }}" 
                             data-product-name="{{ $product->name }}" 
                             data-product-price="{{ $product->price * (1 - $product->discount_percentage / 100) }}" 
                             data-product-stock="{{ $product->stock_quantity }}">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="font-medium text-gray-900">{{ $product->name }}</h3>
                                <span class="text-sm text-gray-500">{{ $product->sku }}</span>
                            </div>
                            <!-- Product Image -->
                            <div class="mb-2 flex justify-center">
                                @if($product->image)
                                    <img src="/{{ $product->image }}" alt="{{ $product->name }}" class="h-20 w-20 object-contain rounded shadow" />
                                @else
                                    <div class="h-20 w-20 flex items-center justify-center bg-gray-100 text-gray-400 rounded shadow">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a4 4 0 004 4h10a4 4 0 004-4V7a4 4 0 00-4-4H7a4 4 0 00-4 4z" /></svg>
                                    </div>
                                @endif
                            </div>
                            <p class="text-sm text-gray-600 mb-2">{{ Str::limit($product->description, 50) }}</p>
                            <div class="flex items-center justify-between">
                                <div>
                                    @php
                                        $finalPrice = $product->price * (1 - $product->discount_percentage / 100);
                                    @endphp
                                    <span class="text-lg font-bold text-green-600">₱{{ number_format($finalPrice, 2) }}</span>
                                    @if($product->discount_percentage > 0)
                                        <div class="text-sm text-red-600 line-through">₱{{ number_format($product->price, 2) }}</div>
                                    @endif
                                </div>
                                <span class="text-sm text-gray-500">Stock: {{ $product->stock_quantity }}</span>
                            </div>
                            <div class="mt-2">
                                <span class="inline-block bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded">{{ $product->category }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Shopping Cart -->
            <div class="lg:col-span-1">
                <div class="bg-white shadow rounded-lg p-6 sticky top-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Shopping Cart</h2>
                    
                    <div id="cartItems" class="space-y-3 mb-6">
                        <!-- Cart items will be dynamically added here -->
                    </div>

                    <div class="border-t pt-4">
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">Subtotal:</span>
                            <span id="subtotal" class="font-medium">₱0.00</span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">Tax (10%):</span>
                            <span id="tax" class="font-medium">₱0.00</span>
                        </div>
                        <div class="flex justify-between mb-4">
                            <span class="text-lg font-bold">Total:</span>
                            <span id="total" class="text-lg font-bold text-green-600">₱0.00</span>
                        </div>

                        <!-- Payment Method -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                            <select id="paymentMethod" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="cash">Cash</option>
                                <option value="card">Card</option>
                                <option value="mobile_payment">Mobile Payment</option>
                            </select>
                        </div>
                        <!-- Card Number (only for card) -->
                        <div class="mb-4" id="cardNumberDiv" style="display:none;">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Card Number</label>
                            <input type="text" id="cardNumber" maxlength="19" placeholder="Card Number" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" />
                        </div>
                        <!-- Mobile Payment Reference (only for mobile payment) -->
                        <div class="mb-4" id="mobilePaymentRefDiv" style="display:none;">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Mobile Payment Reference</label>
                            <input type="text" id="mobilePaymentRef" maxlength="32" placeholder="Reference Number" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" />
                        </div>
                        <!-- Amount Tendered (only for cash) -->
                        <div class="mb-4" id="amountTenderedDiv" style="display:none;">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Amount Tendered</label>
                            <input type="number" min="0" step="0.01" id="amountTendered" placeholder="Enter amount tendered" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" />
                        </div>
                        <!-- Change (only for cash) -->
                        <div class="mb-4" id="changeDiv" style="display:none;">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Change</label>
                            <input type="text" id="change" readonly class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100" />
                        </div>

                        <!-- Notes -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                            <textarea id="notes" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="Optional notes..."></textarea>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-2">
                            <button id="processSale" class="w-full bg-green-600 hover:bg-green-700 text-white py-3 px-4 rounded-lg font-medium transition-colors">
                                Process Sale
                            </button>
                            <button id="clearCart" class="w-full bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-lg transition-colors">
                                Clear Cart
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Sales -->
        <div class="mt-8">
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Recent Sales</h2>
                <div class="space-y-3">
                    @foreach($recentSales as $sale)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <span class="text-green-600 text-sm font-medium">✓</span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $sale->transaction_number }}</p>
                                <p class="text-xs text-gray-500">{{ $sale->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900">₱{{ number_format($sale->final_amount, 2) }}</p>
                            <p class="text-xs text-gray-500">{{ ucfirst($sale->payment_method) }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let cart = [];
        let products = @json($products);

        // Helper functions for showing messages
        function showError(message) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: message,
                showConfirmButton: false,
                timer: 2500,
                timerProgressBar: true
            });
        }
        function showSuccess(message) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: message,
                showConfirmButton: false,
                timer: 1800,
                timerProgressBar: true
            });
        }

        // Product search functionality
        $('#productSearch').on('input', function() {
            const query = $(this).val().toLowerCase();
            $('.product-card').each(function() {
                const name = $(this).data('product-name').toLowerCase();
                const sku = $(this).find('.text-gray-500').text().toLowerCase();
                const category = $(this).find('.bg-gray-100').text().toLowerCase();
                
                if (name.includes(query) || sku.includes(query) || category.includes(query)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        // Add product to cart
        $('.product-card').click(function() {
            const productId = $(this).data('product-id');
            const productName = $(this).data('product-name');
            const productPrice = parseFloat($(this).data('product-price'));
            const productStock = parseInt($(this).data('product-stock'));

            // Check if product is already in cart
            const existingItem = cart.find(item => item.product_id === productId);
            
            if (existingItem) {
                if (existingItem.quantity < productStock) {
                    existingItem.quantity++;
                } else {
                    showError('Cannot add more items. Stock limit reached.');
                    return;
                }
            } else {
                cart.push({
                    product_id: productId,
                    name: productName,
                    price: productPrice,
                    quantity: 1,
                    stock: productStock
                });
            }

            updateCartDisplay();
        });

        // Update cart display
        function updateCartDisplay() {
            const cartContainer = $('#cartItems');
            cartContainer.empty();

            cart.forEach((item, index) => {
                const itemHtml = `
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900">${item.name}</h4>
                            <p class="text-sm text-gray-600">₱${item.price.toFixed(2)} x ${item.quantity}</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button class="quantity-btn bg-gray-200 hover:bg-gray-300 w-6 h-6 rounded flex items-center justify-center" data-index="${index}">-</button>
                            <span class="text-sm font-medium">${item.quantity}</span>
                            <button class="quantity-btn bg-gray-200 hover:bg-gray-300 w-6 h-6 rounded flex items-center justify-center" data-index="${index}">+</button>
                            <button class="remove-btn text-red-500 hover:text-red-700 ml-2" data-index="${index}">×</button>
                        </div>
                    </div>
                `;
                cartContainer.append(itemHtml);
            });

            updateTotals();
        }

        // Quantity buttons
        $(document).on('click', '.quantity-btn', function() {
            const index = $(this).data('index');
            const item = cart[index];
            const isIncrease = $(this).text() === '+';

            if (isIncrease && item.quantity < item.stock) {
                item.quantity++;
            } else if (!isIncrease && item.quantity > 1) {
                item.quantity--;
            }

            updateCartDisplay();
        });

        // Remove item
        $(document).on('click', '.remove-btn', function() {
            const index = $(this).data('index');
            cart.splice(index, 1);
            updateCartDisplay();
        });

        // Update totals
        function updateTotals() {
            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const tax = subtotal * 0.1;
            const total = subtotal + tax;

            $('#subtotal').text('₱' + subtotal.toFixed(2));
            $('#tax').text('₱' + tax.toFixed(2));
            $('#total').text('₱' + total.toFixed(2));
        }

        // Clear cart
        $('#clearCart').click(function() {
            cart = [];
            updateCartDisplay();
        });

        // Process sale
        $('#processSale').click(function() {
            if (cart.length === 0) {
                showError('Please add items to cart before processing sale.');
                return;
            }

            const paymentMethod = $('#paymentMethod').val();
            if (paymentMethod === 'card' && !$('#cardNumber').val()) {
                showError('Please enter a card number.');
                return;
            }
            if (paymentMethod === 'mobile_payment' && !$('#mobilePaymentRef').val()) {
                showError('Please enter a mobile payment reference number.');
                return;
            }
            if (paymentMethod === 'cash') {
                const total = parseFloat($('#total').text().replace('₱','').replace(/,/g, '')) || 0;
                const tendered = parseFloat($('#amountTendered').val()) || 0;
                if (!tendered || tendered < total) {
                    showError('Amount tendered is insufficient.');
                    return;
                }
            }

            const saleData = {
                items: cart.map(item => ({
                    product_id: item.product_id,
                    quantity: item.quantity
                })),
                payment_method: paymentMethod,
                notes: $('#notes').val()
            };

            // Add card number or mobile payment reference if applicable
            if (paymentMethod === 'card') {
                saleData.card_number = $('#cardNumber').val();
            } else if (paymentMethod === 'mobile_payment') {
                saleData.mobile_payment_reference = $('#mobilePaymentRef').val();
            } else if (paymentMethod === 'cash') {
                saleData.amount_tendered = $('#amountTendered').val();
            }

            $.ajax({
                url: '{{ route("pos.process-sale") }}',
                method: 'POST',
                data: saleData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        showSuccess('Sale processed successfully! Transaction: ' + response.transaction_number);
                        cart = [];
                        updateCartDisplay();
                        $('#notes').val('');
                        
                        // Redirect to receipt
                        window.open('{{ url("pos/receipt") }}/' + response.sale_id, '_blank');
                        
                        // Reload page to update recent sales
                        setTimeout(() => location.reload(), 1000);
                    }
                },
                error: function(xhr) {
                    showError('Error processing sale: ' + xhr.responseJSON.message);
                }
            });
        });

        // Show/hide payment fields based on method
        $('#paymentMethod').on('change', function() {
            const method = $(this).val();
            if (method === 'card') {
                $('#cardNumberDiv').show();
                $('#mobilePaymentRefDiv').hide();
                $('#amountTenderedDiv').hide();
                $('#changeDiv').hide();
            } else if (method === 'mobile_payment') {
                $('#cardNumberDiv').hide();
                $('#mobilePaymentRefDiv').show();
                $('#amountTenderedDiv').hide();
                $('#changeDiv').hide();
            } else if (method === 'cash') {
                $('#cardNumberDiv').hide();
                $('#mobilePaymentRefDiv').hide();
                $('#amountTenderedDiv').show();
                $('#changeDiv').show();
            } else {
                $('#cardNumberDiv').hide();
                $('#mobilePaymentRefDiv').hide();
                $('#amountTenderedDiv').hide();
                $('#changeDiv').hide();
            }
            updateChange();
        });
        // Calculate and update change for cash
        function updateChange() {
            const paymentMethod = $('#paymentMethod').val();
            if (paymentMethod !== 'cash') return;
            const total = parseFloat($('#total').text().replace('₱','').replace(/,/g, '')) || 0;
            const tendered = parseFloat($('#amountTendered').val()) || 0;
            const change = tendered - total;
            $('#change').val('₱' + (change >= 0 ? change.toFixed(2) : '0.00'));
        }
        $('#amountTendered').on('input', updateChange);
        // Initialize on page load
        $('#paymentMethod').trigger('change');

        // Initialize
        updateCartDisplay();
    </script>
@endsection 