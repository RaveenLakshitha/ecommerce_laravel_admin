@extends('frontend.layouts.app')

@section('title', __('file.contact_us') ?? 'Contact Us')

@section('content')
<div class="pg-container" style="max-width: 800px; margin: 80px auto; padding: 20px;">
    <h1 style="color: var(--white); font-family: var(--font-display); font-size: 3rem; margin-bottom: 30px;">{{ __('file.contact_us') ?? 'Contact Us' }}</h1>
    <div style="color: var(--silver); line-height: 1.8; font-size: 1.1rem; background: var(--bg-2); padding: 40px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
        <div style="margin-bottom: 20px;">
            <p style="margin-bottom: 15px;"><strong style="color: var(--white);">Email:</strong> <br><a href="mailto:{{ $storefront->email ?? 'hello@karbnzol.com' }}" style="color: var(--gold); text-decoration: none; font-size: 1.25rem;">{{ $storefront->email ?? 'hello@karbnzol.com' }}</a></p>
            <p style="margin-bottom: 15px;"><strong style="color: var(--white);">Phone:</strong> <br><a href="tel:{{ $storefront->phone ?? '+94112345678' }}" style="color: var(--gold); text-decoration: none; font-size: 1.25rem;">{{ $storefront->phone ?? '+94 11 234 5678' }}</a></p>
            <p><strong style="color: var(--white);">Hours:</strong> <br>Mon – Sat, 9am – 6pm</p>
        </div>
        <p style="margin-top: 30px; font-style: italic; color: var(--dim);">Please reach out to us with any questions or concerns. We will get back to you as soon as possible.</p>
    </div>
</div>
@endsection
