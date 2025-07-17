<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Receipt - {{ $sale->transaction_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none; }
            body { margin: 0; padding: 20px; }
        }
    </style>
</head>
<body class="bg-white">
    <div class="max-w-md mx-auto p-6">
        <!-- Print Button -->
        <div class="no-print mb-4">
            <button onclick="window.print()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                Print Receipt
            </button>
            <button onclick="window.close()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg ml-2">
                Close
            </button>
        </div>

        <!-- Receipt Header -->
        <div class="text-center border-b-2 border-gray-300 pb-4 mb-4">
            <h1 class="text-2xl font-bold text-gray-900">POS SYSTEM</h1>
            <p class="text-gray-600">123 Main Street, City, State 12345</p>
            <p class="text-gray-600">Phone: (555) 123-4567</p>
            <p class="text-gray-600">Email: info@possystem.com</p>
        </div>

        <!-- Transaction Details -->
        <div class="mb-4">
            <div class="flex justify-between items-center mb-2">
                <span class="font-medium">Transaction #:</span>
                <span class="font-bold">{{ $sale->transaction_number }}</span>
            </div>
            <div class="flex justify-between items-center mb-2">
                <span class="font-medium">Date:</span>
                <span>{{ $sale->created_at->format('M d, Y g:i A') }}</span>
            </div>
            <div class="flex justify-between items-center mb-2">
                <span class="font-medium">Cashier:</span>
                <span>{{ $sale->user->username }}</span>
            </div>
            <div class="flex justify-between items-center mb-2">
                <span class="font-medium">Payment Method:</span>
                <span class="uppercase">{{ $sale->payment_method }}</span>
            </div>
        </div>

        <!-- Items -->
        <div class="border-t-2 border-gray-300 pt-4 mb-4">
            <h2 class="font-bold text-lg mb-2">ITEMS</h2>
            @foreach($sale->saleItems as $item)
            <div class="flex justify-between items-start mb-2">
                <div class="flex-1">
                    <p class="font-medium">{{ $item->product->name }}</p>
                    <p class="text-sm text-gray-600">{{ $item->product->sku }}</p>
                </div>
                <div class="text-right">
                    <p class="font-medium">${{ number_format($item->unit_price, 2) }} x {{ $item->quantity }}</p>
                    <p class="font-bold">${{ number_format($item->total_price, 2) }}</p>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Totals -->
        <div class="border-t-2 border-gray-300 pt-4 mb-4">
            <div class="flex justify-between mb-2">
                <span>Subtotal:</span>
                <span>${{ number_format($sale->total_amount, 2) }}</span>
            </div>
            <div class="flex justify-between mb-2">
                <span>Tax (10%):</span>
                <span>${{ number_format($sale->tax_amount, 2) }}</span>
            </div>
            @if($sale->discount_amount > 0)
            <div class="flex justify-between mb-2">
                <span>Discount:</span>
                <span>-${{ number_format($sale->discount_amount, 2) }}</span>
            </div>
            @endif
            <div class="flex justify-between text-lg font-bold border-t pt-2">
                <span>TOTAL:</span>
                <span>${{ number_format($sale->final_amount, 2) }}</span>
            </div>
        </div>

        <!-- Notes -->
        @if($sale->notes)
        <div class="border-t-2 border-gray-300 pt-4 mb-4">
            <h3 class="font-bold mb-2">Notes:</h3>
            <p class="text-gray-700">{{ $sale->notes }}</p>
        </div>
        @endif

        <!-- Footer -->
        <div class="text-center border-t-2 border-gray-300 pt-4">
            <p class="text-gray-600 mb-2">Thank you for your purchase!</p>
            <p class="text-sm text-gray-500">Please keep this receipt for your records</p>
            <p class="text-sm text-gray-500 mt-2">Return policy: 30 days with original receipt</p>
            <p class="text-sm text-gray-500">For questions, call (555) 123-4567</p>
        </div>

        <!-- Barcode/QR Code Placeholder -->
        <div class="text-center mt-6">
            <div class="inline-block border-2 border-gray-300 p-4">
                <p class="text-xs text-gray-500">BARCODE</p>
                <p class="text-xs font-mono">{{ $sale->transaction_number }}</p>
            </div>
        </div>
    </div>

    <script>
        // Auto-print when page loads
        window.onload = function() {
            // Uncomment the line below to auto-print
            // window.print();
        };
    </script>
</body>
</html> 