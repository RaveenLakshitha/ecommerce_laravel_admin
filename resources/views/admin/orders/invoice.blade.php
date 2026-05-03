<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google" content="notranslate">
    <title>{{ __('file.invoice_title') }} - {{ __('file.order') }} {{ $order->order_number }}</title>
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
                {{ __('file.print_invoice') }}
            </button>
            <a href="{{ route('orders.show', $order->id) }}" class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-50 text-gray-700 text-sm font-medium">
                {{ __('file.back_to_orders') }}
            </a>
        </div>

        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start gap-6 border-b pb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">{{ __('file.invoice_title') }}</h1>
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
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">{{ __('file.billed_to') }}</h3>
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
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">{{ __('file.shipped_to') }}</h3>
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
                        <p class="text-gray-500 italic">{{ __('file.same_as_billing_not_provided') }}</p>
                    @endif
                </div>
            </div>

            <div class="sm:text-right">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">{{ __('file.invoice_info') }}</h3>
                <div class="text-sm space-y-1">
                    <p><span class="text-gray-500 mr-2">{{ __('file.invoice_date') }}:</span> <span class="font-medium text-gray-900">{{ now()->format('M d, Y') }}</span></p>
                    <p><span class="text-gray-500 mr-2">{{ __('file.order_date') }}:</span> <span class="font-medium text-gray-900">{{ $order->placed_at ? $order->placed_at->format('M d, Y') : $order->created_at->format('M d, Y') }}</span></p>
                    <p><span class="text-gray-500 mr-2">{{ __('file.payment_method') }}:</span> <span class="font-medium text-gray-900 uppercase">{{ $order->payment_method ?: 'N/A' }}</span></p>
                </div>
            </div>
        </div>

        <!-- Items -->
        <div class="mt-8">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b-2 border-gray-800">
                        <th class="py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('file.item_details') }}</th>
                        <th class="py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider text-right">{{ __('file.unit_price') }}</th>
                        <th class="py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider text-center">{{ __('file.qty') }}</th>
                        <th class="py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider text-right">{{ __('file.line_total') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($order->items as $item)
                        <tr>
                            <td class="py-4">
                                <div class="font-medium text-gray-900">{{ $item->product_name_snapshot }}</div>
                                @if($item->variant_attributes)
                                    <div class="text-xs text-gray-500 mt-1">
                                        @foreach(($item->variant_attributes ?? []) as $key => $val)
                                            <span class="mr-2">{{ ucfirst($key) }}: {{ $val }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                            <td class="py-4 text-right text-gray-600">@price($item->unit_price)</td>
                            <td class="py-4 text-center text-gray-900 font-medium">x{{ $item->quantity }}</td>
                            <td class="py-4 text-right font-medium text-gray-900">@price($item->total)</td>
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
                        <td class="py-2 text-gray-500">{{ __('file.subtotal') }}</td>
                        <td class="py-2 font-medium text-gray-900">@price($order->subtotal)</td>
                    </tr>
                    @if($order->discount_amount > 0)
                    <tr>
                        <td class="py-2 text-gray-500">{{ __('file.discount') }}</td>
                        <td class="py-2 font-medium text-red-600">-@price($order->discount_amount)</td>
                    </tr>
                    @endif
                    @if($order->shipping_amount > 0)
                    <tr>
                        <td class="py-2 text-gray-500">{{ __('file.shipping') }}</td>
                        <td class="py-2 font-medium text-gray-900">@price($order->shipping_amount)</td>
                    </tr>
                    @endif
                    @if($order->tax_amount > 0)
                    <tr>
                        <td class="py-2 text-gray-500">{{ __('file.tax') }}</td>
                        <td class="py-2 font-medium text-gray-900">@price($order->tax_amount)</td>
                    </tr>
                    @endif
                    <tr class="border-t-2 border-gray-800">
                        <td class="py-3 text-base font-bold text-gray-900">{{ __('file.total') }}</td>
                        <td class="py-3 text-lg font-bold text-gray-900">@price($order->total_amount)</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Footer Notes -->
        <div class="mt-16 text-center text-sm text-gray-500 border-t pt-8">
            <p>{{ __('file.thank_you_for_your_business') }}</p>
            <p class="mt-1">{{ __('file.invoice_questions_contact_support') }}</p>
        </div>

    </div>

</body>
</html>

