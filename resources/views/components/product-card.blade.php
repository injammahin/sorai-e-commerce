@props([
    'product',
    'quickView' => false,
])

@php
    $cardImages = $product->relationLoaded('images')
        ? $product->images
        : $product->images()->get();

    $primary = $cardImages->firstWhere('is_primary', true)
        ?: $cardImages->first();

    $second = $cardImages
        ->where('id', '!=', optional($primary)->id)
        ->first();

    $primaryImage = asset(
        optional($primary)->path ?: 'images/placeholder.webp'
    );

    $description = \Illuminate\Support\Str::limit(
        trim(strip_tags(
            $product->short_description
            ?: $product->description
            ?: ''
        )),
        180
    );

    $badgeText = '';

    if ($product->discount_percent) {
        $badgeText = '-' . $product->discount_percent . '%';
    } elseif ($product->is_new) {
        $badgeText = 'New';
    }

    /*
    |--------------------------------------------------------------------------
    | Request-scoped wishlist cache
    |--------------------------------------------------------------------------
    */

    $wishlistProductIds = collect();

    if (auth()->check()) {
        if (!request()->attributes->has('aatchala_wishlist_product_ids')) {
            request()->attributes->set(
                'aatchala_wishlist_product_ids',
                auth()->user()
                    ->wishlistProducts()
                    ->pluck('products.id')
            );
        }

        $wishlistProductIds = request()->attributes->get(
            'aatchala_wishlist_product_ids',
            collect()
        );
    }

    $isFavourite = auth()->check()
        && $wishlistProductIds->contains($product->id);
@endphp


<article class="product-card reveal group premium-product-card">

    <div class="product-media premium-product-media">

        <a
            href="{{ route('products.show', $product) }}"
            class="premium-product-image-link"
            aria-label="View {{ $product->name }}"
        >

            @if($product->discount_percent)

                <span class="badge premium-product-badge">

                    -{{ $product->discount_percent }}%

                </span>

            @elseif($product->is_new)

                <span class="badge premium-product-badge">

                    NEW

                </span>

            @endif


            <img
                src="{{ $primaryImage }}"
                alt="{{ optional($primary)->alt_text ?: $product->name }}"
                loading="lazy"
                width="640"
                height="800"
            >


            @if($second)

                <img
                    src="{{ asset($second->path) }}"
                    alt="{{ $second->alt_text ?: $product->name . ' alternate view' }}"
                    loading="lazy"
                    width="640"
                    height="800"
                >

            @endif

        </a>



        @if($quickView)

            {{-- =================================================
                FAVOURITE BUTTON
            ================================================== --}}

            <div class="product-favourite-action">

                @auth

                   <form
                        action="{{ route('wishlist.toggle', $product) }}"
                        method="POST"

                        data-ajax-wishlist

                        data-product-id="{{ $product->id }}"
                    >

                        @csrf


                        <button
                            type="submit"
                            class="product-favourite-button {{ $isFavourite ? 'is-favourite' : '' }}"
                            aria-label="{{ $isFavourite ? 'Remove ' . $product->name . ' from favourites' : 'Add ' . $product->name . ' to favourites' }}"
                            title="{{ $isFavourite ? 'Remove from favourites' : 'Add to favourites' }}"
                            aria-pressed="{{ $isFavourite ? 'true' : 'false' }}"
                        >

                            <i class="{{ $isFavourite ? 'fa-solid' : 'fa-regular' }} fa-heart"></i>

                        </button>

                    </form>

                @else

                    <a
                        href="{{ route('login') }}"
                        class="product-favourite-button"
                        aria-label="Login to add {{ $product->name }} to favourites"
                        title="Add to favourites"
                    >

                        <i class="fa-regular fa-heart"></i>

                    </a>

                @endauth

            </div>



            {{-- =================================================
                PRODUCT HOVER ACTIONS
            ================================================== --}}

            <div class="product-hover-actions">


                {{-- ADD TO BAG --}}

            <form
                method="POST"
                action="{{ route('cart.store', $product) }}"

                class="product-hover-form"

                data-ajax-cart
                data-product-id="{{ $product->id }}"
            >
                @csrf

                <input
                    type="hidden"
                    name="quantity"
                    value="1"
                >

                <button
                    type="submit"
                    class="product-hover-button product-hover-add"

                    @disabled($product->stock < 1)
                >
                    <i class="fa-solid fa-bag-shopping"></i>

                    <span>
                        {{ $product->stock > 0 ? 'Add to Bag' : 'Sold Out' }}
                    </span>
                </button>
            </form>



                {{-- QUICK VIEW --}}

                <button
                    type="button"
                    class="product-hover-button product-hover-quick js-quick-view"

                    data-qv-name="{{ $product->name }}"

                    data-qv-url="{{ route('products.show', $product) }}"

                    data-qv-image="{{ $primaryImage }}"

                    data-qv-material="{{ $product->material ?? '' }}"

                    data-qv-price="৳{{ number_format((float) $product->price) }}"

                    data-qv-compare="{{ $product->compare_price ? '৳' . number_format((float) $product->compare_price) : '' }}"

                    data-qv-description="{{ $description }}"

                    data-qv-stock="{{ $product->stock > 0 ? $product->stock . ' available' : 'Out of stock' }}"

                    data-qv-in-stock="{{ $product->stock > 0 ? '1' : '0' }}"

                    data-qv-badge="{{ $badgeText }}"

                    data-qv-cart-url="{{ route('cart.store', $product) }}"

                    aria-label="Quick view {{ $product->name }}"
                >

                    <i class="fa-regular fa-eye"></i>


                    <span>

                        Quick View

                    </span>

                </button>


            </div>

        @endif

    </div>



    {{-- ========================================================
        PRODUCT DETAILS
    ======================================================== --}}

    <div class="premium-product-info pt-3">


        <a
            href="{{ route('products.show', $product) }}"
            class="product-name"
        >

            {{ $product->name }}

        </a>



        @if($product->material)

            <p class="text-xs muted mt-1">

                {{ $product->material }}

            </p>

        @endif



        <p class="price mt-2">


            <strong class="{{ $product->compare_price ? 'price-sale' : '' }}">

                ৳{{ number_format((float) $product->price) }}

            </strong>



            @if($product->compare_price)

                <span class="price-old">

                    ৳{{ number_format((float) $product->compare_price) }}

                </span>

            @endif


        </p>


    </div>

</article>