@extends('frontend.layouts.app')

@section('title', __('file.order_details') . ' - #' . ($order->order_number ?? $order->id))
@section('body_class', 'light-page')

@section('content')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Barlow:wght@300;400;500;600;700;800;900&family=Oswald:wght@200;300;400;500;600;700&display=swap');

        :root {
            --gold: #c8a96e;
            --gold-light: #dfcc9c;
            --bg-creamy: #fdfbf7;
            --bg-dark: #1a1a1a;
            --font-display: 'Oswald', sans-serif;
            --font-body: 'Barlow', sans-serif;
            --ease-out: cubic-bezier(0.33, 1, 0.68, 1);
        }

        .order-details-page {
            font-family: var(--font-body);
            background-color: var(--bg-creamy);
            min-height: 100vh;
            color: #1a1a1a;
            padding-bottom: 5rem;
        }

        .premium-card {
            background: #fff;
            border-radius: 1.25rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.03);
            border: 1px solid rgba(0, 0, 0, 0.04);
            overflow: hidden;
        }

        .font-display { font-family: var(--font-display); }

        .status-badge {
            font-family: var(--font-display);
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.35rem 0.75rem;
            border-radius: 0.5rem;
        }

        .status-delivered { background: #dcfce7; color: #166534; }
        .status-pending { background: #fef9c3; color: #854d0e; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }
        .status-processing { background: #dbeafe; color: #1e40af; }

        .btn-gold {
            background: var(--bg-dark);
            color: #fff;
            font-family: var(--font-display);
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-gold:hover {
            background: var(--gold);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(200, 169, 110, 0.2);
        }

        .btn-outline {
            background: transparent;
            color: #1a1a1a;
            border: 1px solid #e5e7eb;
            font-family: var(--font-display);
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-outline:hover {
            border-color: var(--gold);
            color: var(--gold);
        }

        .progress-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #e5e7eb;
            position: relative;
            z-index: 10;
        }

        .progress-dot.active { background: var(--gold); box-shadow: 0 0 0 4px rgba(200, 169, 110, 0.2); }
        .progress-dot.completed { background: var(--bg-dark); }

        .progress-line {
            position: absolute;
            top: 50%;
            left: 0;
            height: 2px;
            background: #e5e7eb;
            width: 100%;
            transform: translateY(-50%);
            z-index: 0;
        }

        .progress-line-fill {
            height: 100%;
            background: var(--gold);
            transition: width 1s ease-in-out;
        }
    </style>

    <div class="order-details-page pt-10">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            
            
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
                <div>
                    <nav class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-[0.15em] text-gray-400 mb-4">
                        <a href="{{ route('account.dashboard') }}" class="hover:text-gold transition-colors">{{ __('file.my_account') }}</a>
                        <i data-feather="chevron-right" class="w-3 h-3"></i>
                        <a href="{{ route('account.dashboard', ['tab' => 'orders']) }}" class="hover:text-gold transition-colors">{{ __('file.orders') }}</a>
                        <i data-feather="chevron-right" class="w-3 h-3"></i>
                        <span class="text-gray-900">#{{ $order->order_number ?? $order->id }}</span>
                    </nav>
                    <h1 class="text-4xl font-black text-gray-900 font-display uppercase tracking-tight">{{ __('file.order_details') }}</h1>
                    <p class="text-sm text-gray-500 mt-2">
                        {{ __('file.placed_on') }} <span class="font-bold text-gray-700">{{ $order->placed_at ? $order->placed_at->format('F j, Y') : $order->created_at->format('F j, Y') }}</span>
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <button onclick="window.print()" class="btn-outline">
                        <i data-feather="printer" class="w-4 h-4"></i> {{ __('file.print_receipt') }}
                    </button>
                    @if($order->canBeRefunded())
                        <button class="btn-gold !bg-red-500 hover:!bg-red-600">
                             {{ __('file.request_return') }}
                        </button>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                
                <div class="lg:col-span-8 space-y-8">
                    
                    
                    <div class="premium-card p-8">
                        <div class="flex items-center justify-between mb-10">
                            <h2 class="text-xs font-black text-gray-900 uppercase tracking-[0.2em] font-display">{{ __('file.shipment_status') }}</h2>
                            <span class="status-badge status-{{ strtolower($order->status) }}">
                                {{ $order->status }}
                            </span>
                        </div>

                        <div class="relative px-4">
                            <div class="progress-line">
                                @php
                                    $progress = 10; // Placed
                                    if(strtolower($order->status) == 'processing') $progress = 40;
                                    if(strtolower($order->status) == 'shipped') $progress = 70;
                                    if(strtolower($order->status) == 'delivered') $progress = 100;
                                    if(in_array(strtolower($order->status), ['cancelled', 'failed'])) $progress = 0;
                                @endphp
                                <div class="progress-line-fill" style="width: {{ $progress }}%"></div>
                            </div>
                            <div class="flex justify-between items-center relative">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="progress-dot {{ $progress >= 10 ? 'completed' : '' }}"></div>
                                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">{{ __('file.placed') }}</span>
                                </div>
                                <div class="flex flex-col items-center gap-3">
                                    <div class="progress-dot {{ $progress >= 40 ? ($progress == 40 ? 'active' : 'completed') : '' }}"></div>
                                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">{{ __('file.processing') }}</span>
                                </div>
                                <div class="flex flex-col items-center gap-3">
                                    <div class="progress-dot {{ $progress >= 70 ? ($progress == 70 ? 'active' : 'completed') : '' }}"></div>
                                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">{{ __('file.shipped') }}</span>
                                </div>
                                <div class="flex flex-col items-center gap-3">
                                    <div class="progress-dot {{ $progress >= 100 ? 'completed' : '' }}"></div>
                                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">{{ __('file.delivered') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="premium-card">
                        <div class="px-8 py-6 border-b border-gray-50 flex items-center justify-between">
                            <h2 class="text-xs font-black text-gray-900 uppercase tracking-[0.2em] font-display">{{ __('file.items_ordered') }}</h2>
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $order->items->count() }} {{ __('file.items') }}</span>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @foreach($order->items as $item)
                                <div class="p-8 flex items-center gap-6 group">
                                    <div class="h-24 w-24 rounded-2xl overflow-hidden bg-gray-50 border border-gray-100 flex-shrink-0 relative">
                                        @php
                                            $image = $item->variant->product->primary_image_url ?? $item->variant->product->image ?? 'https://via.placeholder.com/200';
                                        @endphp
                                        <img src="{{ $image }}" alt="{{ $item->product_name_snapshot }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex justify-between items-start mb-2">
                                            <div>
                                                <h4 class="text-base font-black text-gray-900 font-display uppercase tracking-tight leading-tight">
                                                    @if($item->variant && $item->variant->product)
                                                        <a href="{{ route('frontend.products.show', $item->variant->product->slug) }}" class="hover:text-gold transition-colors">{{ $item->product_name_snapshot }}</a>
                                                    @else
                                                        {{ $item->product_name_snapshot }}
                                                    @endif
                                                </h4>
                                                @if(is_array($item->variant_attributes))
                                                    <div class="flex flex-wrap gap-2 mt-2">
                                                        @foreach($item->variant_attributes as $key => $val)
                                                            @if(!in_array($key, ['image', 'slug']))
                                                                <span class="text-[10px] font-bold text-gray-400 uppercase bg-gray-50 px-2 py-1 rounded border border-gray-100">{{ $key }}: {{ $val }}</span>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="text-right">
                                                <p class="text-sm font-black text-gray-900 font-display">@price($item->total, $order->currency)</p>
                                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">@price($item->unit_price, $order->currency) x {{ $item->quantity }}</p>
                                                
                                                @if(strtolower($order->status) === 'delivered' && $item->variant && $item->variant->product)
                                                    <div class="mt-4">
                                                        <a href="{{ route('frontend.products.show', $item->variant->product->slug) }}#write-review" class="text-[10px] font-bold text-gold hover:underline uppercase tracking-widest">
                                                            {{ __('file.write_review') }}
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                
                <div class="lg:col-span-4 space-y-8">
                    
                    
                    <div class="premium-card p-8">
                        <h2 class="text-xs font-black text-gray-900 uppercase tracking-[0.2em] font-display mb-8">{{ __('file.order_summary') }}</h2>
                        <div class="space-y-4">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-400 font-bold uppercase tracking-widest text-[10px]">{{ __('file.subtotal') }}</span>
                                <span class="font-black text-gray-900 font-display">@price($order->subtotal, $order->currency)</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-400 font-bold uppercase tracking-widest text-[10px]">{{ __('file.shipping') }}</span>
                                <span class="font-black text-gray-900 font-display">@price($order->shipping_amount, $order->currency)</span>
                            </div>
                            @if($order->discount_amount > 0)
                                <div class="flex justify-between text-sm">
                                    <span class="text-red-400 font-bold uppercase tracking-widest text-[10px]">{{ __('file.discount') }}</span>
                                    <span class="font-black text-red-500 font-display">-@price($order->discount_amount, $order->currency)</span>
                                </div>
                            @endif
                            <div class="pt-6 mt-6 border-t border-gray-100 flex justify-between items-end">
                                <span class="text-xs font-black text-gray-900 uppercase tracking-widest">{{ __('file.total') }}</span>
                                <span class="text-2xl font-black text-gold font-display leading-none">@price($order->total_amount, $order->currency)</span>
                            </div>
                        </div>
                    </div>

                    
                    <div class="premium-card p-8 space-y-10">
                        <div>
                            <h2 class="text-xs font-black text-gray-900 uppercase tracking-[0.2em] font-display mb-6">{{ __('file.delivery_information') }}</h2>
                            @if($order->shippingAddress)
                                <address class="not-italic">
                                    <p class="text-sm font-black text-gray-900 mb-2 uppercase font-display">{{ $order->shippingAddress->first_name }} {{ $order->shippingAddress->last_name }}</p>
                                    <div class="text-[12px] text-gray-500 leading-relaxed space-y-1">
                                        <p>{{ $order->shippingAddress->address_line1 }}</p>
                                        @if($order->shippingAddress->address_line2) <p>{{ $order->shippingAddress->address_line2 }}</p> @endif
                                        <p>{{ $order->shippingAddress->city }}, {{ $order->shippingAddress->province }} {{ $order->shippingAddress->postal_code }}</p>
                                        <p>{{ $order->shippingAddress->country }}</p>
                                    </div>
                                    <div class="mt-4 pt-4 border-t border-gray-50 flex items-center gap-2">
                                        <i data-feather="phone" class="w-3 h-3 text-gold"></i>
                                        <span class="text-[10px] font-bold text-gray-700 uppercase tracking-widest">{{ $order->shippingAddress->phone ?? $order->customer_phone }}</span>
                                    </div>
                                </address>
                            @endif
                        </div>

                        <div>
                            <h2 class="text-xs font-black text-gray-900 uppercase tracking-[0.2em] font-display mb-6">{{ __('file.payment_method') }}</h2>
                            <div class="flex items-center justify-between p-4 rounded-xl bg-gray-50 border border-gray-100">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-white rounded-lg shadow-sm">
                                        <i data-feather="credit-card" class="w-4 h-4 text-gold"></i>
                                    </div>
                                    <span class="text-[10px] font-black text-gray-900 uppercase tracking-widest">{{ str_replace('_', ' ', $order->payment_method) }}</span>
                                </div>
                                <span class="status-badge status-{{ strtolower($order->payment_status) }} !text-[8px] !px-2">
                                    {{ $order->payment_status }}
                                </span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        });
    </script>
@endsection

