<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice->invoice_number }}</title>

    <style>
        @page {
            size: A4 portrait;
            margin: 12mm 10mm 15mm 10mm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 13px;
            color: #222;
            line-height: 1.5;
            margin: 0;
            padding: 0;
            background: white;
        }

        .container {
            max-width: 210mm;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            border-bottom: 3px solid #2563eb;
            padding-bottom: 16px;
            margin-bottom: 24px;
            display: flex;
            justify-content: space-between;
        }

        .clinic h1 {
            margin: 0;
            font-size: 26px;
            color: #1e40af;
        }

        .meta {
            text-align: right;
        }

        .meta h2 {
            margin: 0 0 6px;
            font-size: 28px;
            color: #2563eb;
        }

        table.meta td {
            padding: 3px 0;
            font-size: 12px;
        }

        .two-col {
            display: flex;
            gap: 20px;
            margin-bottom: 28px;
        }

        .info-box {
            flex: 1;
            background: #f8fafc;
            padding: 16px;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
        }

        .info-box h3 {
            margin: 0 0 10px;
            font-size: 12px;
            color: #2563eb;
            text-transform: uppercase;
        }

        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }

        table.items th {
            background: #2563eb;
            color: white;
            padding: 10px 8px;
            font-size: 12px;
        }

        table.items th.center {
            text-align: center;
        }

        table.items th.right {
            text-align: right;
        }

        table.items td {
            padding: 10px 8px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 13px;
        }

        .totals {
            width: 340px;
            margin-left: auto;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            font-size: 13.5px;
        }

        .total-row.grand {
            font-size: 15px;
            font-weight: bold;
            border-top: 2px solid #2563eb;
            padding-top: 10px;
            margin-top: 8px;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            color: #64748b;
            font-size: 12px;
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
        }

        @media print {
            body {
                padding: 0;
                margin: 0;
            }

            .no-print {
                display: none !important;
            }
        }
    </style>
</head>

<body>

    <div class="container">

        <div class="header">
            <div class="clinic">
                <h1>{{ $clinic_name }}</h1>
                <div>{{ __('file.med_center_pharmacy') }}</div>
                <div class="text-sm text-gray-500 italic">
                    {{ $clinic_address }}<br>
                    {{ __('file.phone') }}: {{ $clinic_phone }}<br>
                    {{ __('file.email') }}: {{ $clinic_email }}
                </div>
            </div>

            <div class="meta">
                <h2>{{ __('file.invoice_label') }}</h2>
                <table class="meta">
                    <tr>
                        <td><strong>{{ __('file.invoice_no') }}:</strong></td>
                        <td>{{ $invoice->invoice_number }}</td>
                    </tr>
                    <tr>
                        <td><strong>{{ __('file.date') }}:</strong></td>
                        <td>{{ $invoice->invoice_date->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <td><strong>{{ __('file.status') }}:</strong></td>
                        <td>{{ __('file.status_' . strtolower($invoice->status)) }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="two-col">
            <div class="info-box">
                <h3>{{ __('file.bill_to') }}</h3>
                <strong>{{ $invoice->patient->first_name }} {{ $invoice->patient->last_name }}</strong><br>
                {{ $invoice->patient->phone ?? '' }}<br>
                {{ __('file.medical_record_number') }}: {{ $invoice->patient->medical_record_number ?? '—' }}
            </div>

            <div class="info-box">
                <h3>{{ __('file.invoice_details') }}</h3>
                {{ __('file.due_date') }}: {{ $invoice->due_date?->format('d M Y') ?? '—' }}<br>
                {{ __('file.recorded_by') }}: {{ auth()->user()->name ?? '—' }}
            </div>
        </div>

        <table class="items">
            <thead>
                <tr>
                    <th class="px-4 py-2 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">#</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('file.item') }}</th>
                    <th class="px-4 py-2 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('file.qty') }}</th>
                    <th class="px-4 py-2 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('file.price') }}</th>
                    <th class="px-4 py-2 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('file.total') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $index => $item)
                    <tr>
                        <td class="center">{{ $index + 1 }}</td>
                        <td>{{ $item->description }}</td>
                        <td class="center">{{ $item->quantity }}</td>
                        <td class="right">{{ $currency_code }}{{ number_format($item->unit_price, 2) }}</td>
                        <td class="right">{{ $currency_code }}{{ number_format($item->total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Payment History -->
        @if($invoice->payments->isNotEmpty())
        <div style="margin-bottom: 24px;">
            <h3 style="font-size: 14px; color: #1e40af; text-transform: uppercase; margin-bottom: 12px; border-bottom: 1px solid #e5e7eb; padding-bottom: 6px;">
                {{ __('file.payment_history') }}
            </h3>
            <table class="items">
                <thead>
                    <tr>
                        <th style="background: #64748b; text-align: left;">{{ __('file.date') }}</th>
                        <th style="background: #64748b; text-align: left;">{{ __('file.method') }}</th>
                        <th style="background: #64748b; text-align: left;">{{ __('file.reference') }}</th>
                        <th style="background: #64748b; text-align: right;">{{ __('file.amount') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->payments as $payment)
                    <tr>
                        <td>{{ $payment->payment_date->format('d M Y') }}</td>
                        <td>{{ ucfirst($payment->method) }}</td>
                        <td>{{ $payment->reference ?? '—' }}</td>
                        <td style="text-align: right;">{{ $currency_code }}{{ number_format($payment->amount, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <div class="totals">
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-500">{{ __('file.subtotal') }}</span>
                <span class="font-medium text-gray-900">{{ $currency_code }}{{ number_format($invoice->subtotal, 2) }}</span>
            </div>
            @if($invoice->tax_amount > 0)
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-500">{{ __('file.tax') }}</span>
                <span class="font-medium text-gray-900">{{ $currency_code }}{{ number_format($invoice->tax_amount, 2) }}</span>
            </div>
            @endif
            @if($invoice->discount_amount > 0)
            <div class="flex justify-between items-center text-sm text-red-600">
                <span>{{ __('file.discount') }}</span>
                <span class="font-medium">-{{ $currency_code }}{{ number_format($invoice->discount_amount, 2) }}</span>
            </div>
            @endif

            <div class="total-row grand">
                <div>{{ __('file.total') }}</div>
                <div>{{ $currency_code }}{{ number_format($invoice->total, 2) }}</div>
            </div>

            @if($invoice->balance_due > 0)
                <div class="total-row" style="margin-top:12px; font-weight:bold; color:#c2410c;">
                    <div>{{ __('file.balance_due_label') }}</div>
                    <div>{{ $currency_code }}{{ number_format($invoice->balance_due, 2) }}</div>
                </div>
            @endif
        </div>

        <div class="footer">
            <div style="font-weight:600; color:#2563eb; font-size:15px; margin-bottom:8px;">
                {{ __('file.thank_you_choosing') }}
            </div>
            <div>{{ __('file.printed_on') }} {{ now()->format('d M Y h:i A') }}</div>
        </div>

    </div>

    <div class="no-print" style="position:fixed; bottom:24px; right:32px;">
        <button onclick="doPrintAndRedirect()"
            style="padding:12px 28px; background:#2563eb; color:white; border:none; border-radius:6px; font-size:15px; cursor:pointer; box-shadow:0 4px 12px rgba(37,99,235,0.3);">
            {{ __('file.print_return_pos') }}
        </button>
    </div>

    <script type="text/javascript">
        function doPrintAndRedirect() {
            window.print();

            // Only redirect if NOT in an iframe
            if (window.self === window.top) {
                setTimeout(function () {
                    @if($redirect === 'pos')
                        window.location.href = "{{ route('invoices.pos') }}";
                    @else
                        window.location.href = "{{ route('invoices.index') }}";
                    @endif
                }, 1800);
            }
        }

        // Auto print after page loads (like your reference code)
        window.onload = function () {
            setTimeout(doPrintAndRedirect, 900); // slight delay for better UX
        };
    </script>

</body>

</html>