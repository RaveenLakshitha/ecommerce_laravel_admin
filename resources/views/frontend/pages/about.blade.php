@extends('frontend.layouts.app')

@section('title', __('file.about_us') ?? 'About Us')

@section('content')
<style>
    @media (max-width: 768px) {
        .pg-container > div:first-of-type {
            grid-template-columns: 1fr !important;
        }
    }
</style>
<div class="pg-container" style="max-width: 1000px; margin: 80px auto; padding: 20px;">
    <h1 style="color: var(--white); font-family: var(--font-display); font-size: 3.5rem; margin-bottom: 30px; text-transform: uppercase; letter-spacing: 0.05em;">
        {{ $storefront->storefront_our_story_title ?? __('file.about_us') ?? 'About Us' }}
    </h1>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; align-items: start; margin-bottom: 60px;">
        @php
            $ourStoryImg = $storefront->storefront_our_story_image 
                ? asset('storage/' . $storefront->storefront_our_story_image) 
                : 'https://images.unsplash.com/photo-1487222477894-8943e31ef7b2?w=900&q=80';
        @endphp
        <div style="position: relative; border-radius: 12px; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.3);">
            <img src="{{ $ourStoryImg }}" alt="Our Story" style="width: 100%; height: auto; display: block; filter: brightness(0.9);">
        </div>
        
        <div style="color: var(--silver); line-height: 1.8; font-size: 1.15rem; background: var(--bg-2); padding: 40px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05); height: 100%;">
            {!! nl2br(e($storefront->storefront_our_story_content ?? $storefront->storefront_about_us_content ?? $storefront->storefront_about_us ?? 'Information about our store.')) !!}
        </div>
    </div>

    <div id="contact-info" style="margin-top: 40px; color: var(--silver); line-height: 1.8; font-size: 1.1rem; background: var(--bg-2); padding: 40px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
        <h2 style="color: var(--white); font-family: var(--font-display); font-size: 2rem; margin-bottom: 20px;">{{ __('file.contact_us') ?? 'Get in Touch' }}</h2>
        <div style="margin-bottom: 20px;">
            <p style="margin-bottom: 15px;"><strong style="color: var(--white);">Email:</strong> <br><a href="mailto:{{ $storefront->email ?? 'hello@karbnzol.com' }}" style="color: var(--gold); text-decoration: none; font-size: 1.25rem;">{{ $storefront->email ?? 'hello@karbnzol.com' }}</a></p>
            <p style="margin-bottom: 15px;"><strong style="color: var(--white);">Phone:</strong> <br><a href="tel:{{ $storefront->phone ?? '+94112345678' }}" style="color: var(--gold); text-decoration: none; font-size: 1.25rem;">{{ $storefront->phone ?? '+94 11 234 5678' }}</a></p>
            <p><strong style="color: var(--white);">Hours:</strong> <br>Mon – Sat, 9am – 6pm</p>
        </div>
        <p style="margin-top: 30px; font-style: italic; color: var(--dim);">Please reach out to us with any questions or concerns. We will get back to you as soon as possible.</p>
    </div>
</div>
@endsection
