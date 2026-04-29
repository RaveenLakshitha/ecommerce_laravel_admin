@extends('frontend.layouts.app')

@section('title', __('file.about_us') ?? 'About Us')

@section('content')
<div class="pg-container" style="max-width: 800px; margin: 80px auto; padding: 20px;">
    <h1 style="color: var(--white); font-family: var(--font-display); font-size: 3rem; margin-bottom: 30px;">{{ __('file.about_us') ?? 'About Us' }}</h1>
    <div style="color: var(--silver); line-height: 1.8; font-size: 1.1rem; background: var(--bg-2); padding: 40px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
        {!! nl2br(e($storefront->storefront_about_us ?? 'Information about our store.')) !!}
    </div>
</div>
@endsection
