<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - Order {{ $order->order_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
            @page { margin: 1cm; }
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-900 font-sans p-4 sm:p-8">

    <div class="max-w-4xl mx-auto bg-white p-8 sm:p-12 shadow-sm rounded-lg border border-gray-200 print:shadow-none print:border-none print:p-0">
        
        <!-- Action Buttons -->
        <div class="flex justify-end gap-3 mb-8 no-print border-b pb-4">
            <button onclick="window.print()" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 flex items-center gap-2 text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Print Invoice
            </button>
            <a href="{{ route('orders.show', $order->id) }}" class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-50 text-gray-700 text-sm font-medium">
                Back to Order
            </a>
        </div>

        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start gap-6 border-b pb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">INVOICE</h1>
                <p class="text-gray-500 mt-1 uppercase text-sm font-semibold tracking-wider">#{{ $order->order_number }}</p>
            </div>
            <div class="text-left sm:text-right">
                <div class="text-xl font-bold text-gray-900">{{ config('app.name', 'Laravel') }}</div>
                <div class="text-gray-500 text-sm mt-1">
                    <p>123 Store Address, City</p>
                    <p>contact@store.com | +1 234 567 890</p>
                </div>
            </div>
        </div>

        <!-- Details -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 mt-8 border-b pb-8">
            <div>
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Billed To</h3>
                <div class="text-sm">
                    @if($order->billingAddress)
                        <p class="font-bold text-gray-900">{{ $order->billingAddress->first_name }} {{ $order->billingAddress->last_name }}</p>
                        <p class="text-gray-600 mt-1">{{ $order->billingAddress->address_line_1 }}</p>
                        @if($order->billingAddress->address_line_2)
                            <p class="text-gray-600">{{ $order->billingAddress->address_line_2 }}</p>
                        @endif
                        <p class="text-gray-600">{{ $order->billingAddress->city }}, {{ $order->billingAddress->state }} {{ $order->billingAddress->postal_code }}</p>
                        <p class="text-gray-600">{{ $order->billingAddress->country }}</p>
                    @else
                        <p class="font-bold text-gray-900">{{ $order->customer_name }}</p>
                        <p class="text-gray-600 mt-1">{{ $order->customer_email }}</p>
                        @if($order->customer_phone)
                            <p class="text-gray-600">{{ $order->customer_phone }}</p>
                        @endif
                    @endif
                </div>
            </div>

            <div>
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Shipped To</h3>
                <div class="text-sm">
                    @if($order->shippingAddress)
                        <p class="font-bold text-gray-900">{{ $order->shippingAddress->first_name }} {{ $order->shippingAddress->last_name }}</p>
                        <p class="text-gray-600 mt-1">{{ $order->shippingAddress->address_line_1 }}</p>
                        @if($order->shippingAddress->address_line_2)
                            <p class="text-gray-600">{{ $order->shippingAddress->address_line_2 }}</p>
                        @endif
                        <p class="text-gray-600">{{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }} {{ $order->shippingAddress->postal_code }}</p>
                        <p class="text-gray-600">{{ $order->shippingAddress->country }}</p>
                    @else
                        <p class="text-gray-500 italic">Same as Billing / Not provided</p>
                    @endif
                </div>
            </div>

            <div class="sm:text-right">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Invoice Info</h3>
                <div class="text-sm space-y-1">
                    <p><span class="text-gray-500 mr-2">Invoice Date:</span> <span class="font-medium text-gray-900">{{ now()->format('M d, Y') }}</span></p>
                    <p><span class="text-gray-500 mr-2">Order Date:</span> <span class="font-medium text-gray-900">{{ $order->placed_at ? $order->placed_at->format('M d, Y') : $order->created_at->format('M d, Y') }}</span></p>
                    <p><span class="text-gray-500 mr-2">Payment Method:</span> <span class="font-medium text-gray-900 uppercase">{{ $order->payment_method ?: 'N/A' }}</span></p>
                </div>
            </div>
        </div>

        <!-- Items -->
        <div class="mt-8">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b-2 border-gray-800">
                        <th class="py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">Item Details</th>
                        <th class="py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider text-right">Unit Price</th>
                        <th class="py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider text-center">Qty</th>
                        <th class="py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider text-right">Line Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($order->items as $item)
                        <tr>
                            <td class="py-4">
                                <div class="font-medium text-gray-900">{{ $item->product_name_snapshot }}</div>
                                @if($item->variant_attributes)
                                    <div class="text-xs text-gray-500 mt-1">
                                        @foreach(json_decode($item->variant_attributes, true) ?? [] as $key => $val)
                                            <span class="mr-2">{{ ucfirst($key) }}: {{ $val }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                            <td class="py-4 text-right text-gray-600">{{ $order->currency }} {{ number_format($item->unit_price, 2) }}</td>
                            <td class="py-4 text-center text-gray-900 font-medium">x{{ $item->quantity }}</td>
                            <td class="py-4 text-right font-medium text-gray-900">{{ $order->currency }} {{ number_format($item->total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Summary -->
        <div class="mt-8 flex justify-end">
            <div class="w-full sm:w-1/3">
                <table class="w-full text-right text-sm">
                    <tr>
                        <td class="py-2 text-gray-500">Subtotal</td>
                        <td class="py-2 font-medium text-gray-900">{{ $order->currency }} {{ number_format($order->subtotal, 2) }}</td>
                    </tr>
                    @if($order->discount_amount > 0)
                    <tr>
                        <td class="py-2 text-gray-500">Discount</td>
                        <td class="py-2 font-medium text-red-600">-{{ $order->currency }} {{ number_format($order->discount_amount, 2) }}</td>
                    </tr>
                    @endif
                    @if($order->shipping_amount > 0)
                    <tr>
                        <td class="py-2 text-gray-500">Shipping</td>
                        <td class="py-2 font-medium text-gray-900">{{ $order->currency }} {{ number_format($order->shipping_amount, 2) }}</td>
                    </tr>
                    @endif
                    @if($order->tax_amount > 0)
                    <tr>
                        <td class="py-2 text-gray-500">Tax</td>
                        <td class="py-2 font-medium text-gray-900">{{ $order->currency }} {{ number_format($order->tax_amount, 2) }}</td>
                    </tr>
                    @endif
                    <tr class="border-t-2 border-gray-800">
                        <td class="py-3 text-base font-bold text-gray-900">Total</td>
                        <td class="py-3 text-lg font-bold text-gray-900">{{ $order->currency }} {{ number_format($order->total_amount, 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Footer Notes -->
        <div class="mt-16 text-center text-sm text-gray-500 border-t pt-8">
            <p>Thank you for your business!</p>
            <p class="mt-1">If you have any questions about this invoice, please contact support.</p>
        </div>

    </div>

</body>
</html>

