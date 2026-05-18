<div class="prod-grid" id="prodGrid">
    @foreach($products as $i => $p)
        <div class="p-card" style="opacity:0;transform:translateY(20px);" data-index="{{ $i }}">
            <a href="{{ route('frontend.products.show', $p->slug) }}" style="text-decoration:none; color:inherit;">
                <div class="p-img">
                    @if($p->is_featured)<span class="p-badge top">Top</span>@endif
                    <div class="p-side">
                        @php
                            $inWishlist = auth()->check() && auth()->user()->wishlists()->where('product_id', $p->id)->exists();
                        @endphp
                        <button class="p-side-btn {{ $inWishlist ? 'active' : '' }}" aria-label="Wishlist" onclick="event.preventDefault(); event.stopPropagation(); toggleWishlist(this, {{ $p->id }});">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                            </svg>
                        </button>
                    </div>
                    <img src="{{ $p->primaryImage ? $p->primaryImage->url : null }}@if(!$p->primaryImage)@placeholder($p->id)@endif" alt="{{ $p->name }}" loading="{{ $i < 4 ? 'eager' : 'lazy' }}">
                    <button class="p-add" onclick="event.preventDefault(); window.location='{{ route('frontend.products.show', $p->slug) }}'">+ Add to Bag</button>
                </div>
                <div class="p-info">
                    <p class="p-brand">{{ $p->brand->name ?? 'Karbnzol' }}</p>
                    <p class="p-name">{{ $p->name }}</p>
                    <div class="p-price-row">
                        @php
                            $defaultVariant = $p->variants->where('is_default', true)->first() ?? $p->variants->first();
                            $displayPrice = $defaultVariant ? ($defaultVariant->sale_price ?? $defaultVariant->price) : $p->base_price;
                            $originalPrice = ($defaultVariant && $defaultVariant->sale_price) ? $defaultVariant->price : null;
                        @endphp
                        <span class="p-price">@price($displayPrice)</span>
                        @if($originalPrice)
                            <span class="p-was">@price($originalPrice)</span>
                        @endif
                    </div>
                    <div class="p-swatches">
                        @php
                            $varColors = $p->variants->flatMap(function($var) {
                                return $var->attributeValues->filter(function($av) {
                                    return optional($av->attribute)->slug === 'color';
                                });
                            })->unique('id');
                        @endphp
                        @foreach($varColors as $c)
                            <span class="p-sw" style="background:{{ $c->value ?? '#ccc' }};" title="{{ $c->value }}"></span>
                        @endforeach
                    </div>
                    <span class="list-cta">
                        View Product
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14M12 5l7 7-7 7" />
                        </svg>
                    </span>
                </div>
            </a>
        </div>
    @endforeach
</div>

{{-- Pagination --}}
<nav class="custom-pagination-wrapper" aria-label="Page navigation" style="display:block; text-align:center;">
    {{ $products->links('pagination::bootstrap-4') }}
</nav>
