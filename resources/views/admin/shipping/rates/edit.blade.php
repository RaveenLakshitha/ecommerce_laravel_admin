@extends('layouts.app')

@section('title', __('file.edit_shipping_rate') ?? 'Edit Shipping Rate')

@section('content')
    <div class="admin-page animate-fade-in-up">
        <div class="admin-page-inner">
            <nav class="admin-breadcrumb mt-6" aria-label="Breadcrumb">
                <a href="{{ route('admin.dashboard') }}">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    {{ __('file.dashboard') }}
                </a>
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
                <a href="{{ route('shipping.rates.index') }}">{{ __('file.shipping_rates') }}</a>
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
                <span class="active">{{ __('file.edit_shipping_rate') }}</span>
            </nav>

            <div class="admin-page-header">
                <div>
                    <h1 class="admin-page-title">{{ __('file.edit_shipping_rate') }}</h1>
                    <p class="admin-page-subtitle">{{ __('file.update_rate_details') }}</p>
                </div>
            </div>

            @if ($errors->any())
                <div class="admin-alert-error mb-6 animate-fade-in-scale">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="max-w-3xl">
                <div class="admin-card p-8">
                    @include('admin.shipping.rates.partials.form', ['rate' => $rate, 'zones' => $zones, 'couriers' => $couriers])
                </div>
            </div>
        </div>
    </div>
@endsection
