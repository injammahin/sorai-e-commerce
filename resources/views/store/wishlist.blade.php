@extends('layouts.store')

@section('title', 'Your Wishlist | AATCHALA')

@section(
    'description',
    'Your saved AATCHALA pieces.'
)


@section('content')


@php
    $wishlistCount = $products->total();
@endphp


<style>

    /* =========================================================
       PAGE
    ========================================================= */

    .wishlist-page {
        width: 100%;
        max-width: 1440px;

        margin: 0 auto;

        padding:
            clamp(2rem, 5vw, 5rem)
            clamp(1.25rem, 4vw, 4rem)
            clamp(4rem, 7vw, 7rem);
    }


    /* =========================================================
       PAGE HEADER
    ========================================================= */

    .wishlist-page-header {
        display: flex;

        align-items: flex-end;
        justify-content: space-between;

        gap: 2rem;

        margin-bottom:
            clamp(2rem, 4vw, 3.5rem);

        padding-bottom:
            clamp(1.5rem, 3vw, 2.5rem);

        border-bottom:
            1px solid #ddd6cd;
    }


    .wishlist-eyebrow {
        margin-bottom: .55rem;

        color: #8c857d;

        font-size: 9px;
        font-weight: 600;

        letter-spacing: .22em;

        text-transform: uppercase;
    }


    .wishlist-title {
        font-family:
            'Cormorant Garamond',
            Georgia,
            serif;

        font-size:
            clamp(
                3rem,
                5vw,
                5rem
            );

        font-weight: 400;

        line-height: .95;
    }


    .wishlist-header-meta {
        margin-top: .9rem;

        color: #77716a;

        font-size: 12px;
    }


    /* =========================================================
       CONTINUE SHOPPING
    ========================================================= */

    .wishlist-continue {
        display: inline-flex;

        align-items: center;

        gap: .6rem;

        padding-bottom: .35rem;

        border-bottom:
            1px solid #171512;

        font-size: 9px;
        font-weight: 600;

        letter-spacing: .14em;

        text-transform: uppercase;

        white-space: nowrap;

        transition:
            color .25s ease,
            border-color .25s ease;
    }


    .wishlist-continue i {
        transition:
            transform .3s ease;
    }


    .wishlist-continue:hover {
        color: #c4622f;

        border-color: #c4622f;
    }


    .wishlist-continue:hover i {
        transform:
            translateX(4px);
    }


    /* =========================================================
       PRODUCT GRID
    ========================================================= */

    .wishlist-grid {
        display: grid;

        grid-template-columns:
            repeat(
                4,
                minmax(0, 1fr)
            );

        gap:
            clamp(2rem, 4vw, 3.5rem)
            clamp(1rem, 2vw, 1.5rem);
    }


    /* =========================================================
       CARD
    ========================================================= */

    .wishlist-card {
        position: relative;

        min-width: 0;

        background: #faf8f5;
    }


    /* =========================================================
       IMAGE
    ========================================================= */

    .wishlist-card-media {
        position: relative;

        width: 100%;

        aspect-ratio: 4 / 5;

        overflow: hidden;

        background: #eee8df;
    }


    .wishlist-card-image-link {
        display: block;

        width: 100%;
        height: 100%;
    }


    .wishlist-card-image {
        display: block;

        width: 100%;
        height: 100%;

        object-fit: contain;
        object-position: center;

        transition:
            transform .65s
            cubic-bezier(.22, 1, .36, 1);
    }


    .wishlist-card:hover
    .wishlist-card-image {

        transform:
            scale(1.025);
    }


    /* =========================================================
       BADGE
    ========================================================= */

    .wishlist-badge {
        position: absolute;

        top: .7rem;
        left: .7rem;

        z-index: 5;

        padding:
            .35rem
            .5rem;

        background: #171512;

        color: #ffffff;

        font-size: 8px;
        font-weight: 600;

        letter-spacing: .1em;

        text-transform: uppercase;
    }


    /* =========================================================
       REMOVE HEART
    ========================================================= */

    .wishlist-remove-form {
        position: absolute;

        top: .7rem;
        right: .7rem;

        z-index: 10;
    }


    .wishlist-remove-button {
        width: 39px;
        height: 39px;

        display: grid;

        place-items: center;

        padding: 0;

        border:
            1px solid #c4622f;

        border-radius: 50%;

        background: #c4622f;

        color: #ffffff;

        font-size: 14px;

        cursor: pointer;

        box-shadow:
            0
            5px
            18px
            rgba(196, 98, 47, .20);

        transition:
            background .25s ease,
            border-color .25s ease,
            transform .3s ease;
    }


    .wishlist-remove-button:hover {
        background: #171512;

        border-color: #171512;

        transform:
            scale(1.06);
    }


    /* =========================================================
       PRODUCT INFORMATION
    ========================================================= */

    .wishlist-card-body {
        padding-top: 1rem;
    }


    .wishlist-card-name {
        display: block;

        font-family:
            'Cormorant Garamond',
            Georgia,
            serif;

        font-size: 20px;

        line-height: 1.15;

        transition:
            color .25s ease;
    }


    .wishlist-card-name:hover {
        color: #c4622f;
    }


    .wishlist-material {
        margin-top: .35rem;

        color: #746e67;

        font-size: 11px;

        line-height: 1.5;
    }


    /* =========================================================
       PRICE
    ========================================================= */

    .wishlist-price {
        display: flex;

        align-items: center;
        flex-wrap: wrap;

        gap: .5rem;

        margin-top: .65rem;
    }


    .wishlist-current-price {
        color: #171512;

        font-size: 13px;

        font-weight: 600;
    }


    .wishlist-current-price.is-sale {
        color: #c4622f;
    }


    .wishlist-old-price {
        color: #918a82;

        font-size: 11px;

        text-decoration: line-through;
    }


    /* =========================================================
       STOCK
    ========================================================= */

    .wishlist-stock {
        display: flex;

        align-items: center;

        gap: .45rem;

        min-height: 22px;

        margin-top: .65rem;

        color: #6f6962;

        font-size: 10px;

        line-height: 1.4;
    }


    .wishlist-stock-dot {
        width: 6px;
        height: 6px;

        flex: 0 0 6px;

        border-radius: 50%;

        background: #2f7354;
    }


    .wishlist-stock.is-out
    .wishlist-stock-dot {

        background: #a44a3c;
    }


    /* =========================================================
       ACTIONS
    ========================================================= */

    .wishlist-actions {
        display: grid;

        /*
        |--------------------------------------------------------------------------
        | Premium layout:
        | Add to Bag takes available width
        | View is compact square button
        |--------------------------------------------------------------------------
        */

        grid-template-columns:
            minmax(0, 1fr)
            44px;

        gap: .5rem;

        margin-top: 1rem;
    }


    .wishlist-add-form {
        min-width: 0;
    }


    /* =========================================================
       ADD TO BAG
    ========================================================= */

    .wishlist-add-button {
        width: 100%;
        height: 44px;

        display: flex;

        align-items: center;
        justify-content: center;

        gap: .55rem;

        padding:
            0
            .8rem;

        border:
            1px solid #173d32;

        background: #173d32;

        color: #ffffff;

        font-size: 9px;
        font-weight: 600;

        letter-spacing: .12em;

        text-transform: uppercase;

        cursor: pointer;

        transition:
            background .25s ease,
            border-color .25s ease,
            transform .25s ease;
    }


    .wishlist-add-button:hover {
        background: #c4622f;

        border-color: #c4622f;

        transform:
            translateY(-1px);
    }


    .wishlist-add-button:disabled {
        background: #98a9a2;

        border-color: #98a9a2;

        color: #ffffff;

        cursor: not-allowed;

        opacity: 1;

        transform: none;
    }


    /* =========================================================
       VIEW BUTTON
    ========================================================= */

    .wishlist-view-button {
        width: 44px;
        height: 44px;

        display: grid;

        place-items: center;

        padding: 0;

        border:
            1px solid #d6cfc6;

        background: #faf8f5;

        color: #171512;

        font-size: 13px;

        cursor: pointer;

        transition:
            color .25s ease,
            background .25s ease,
            border-color .25s ease,
            transform .25s ease;
    }


    .wishlist-view-button:hover {
        border-color: #171512;

        background: #171512;

        color: #ffffff;

        transform:
            translateY(-1px);
    }


    /* =========================================================
       EMPTY WISHLIST
    ========================================================= */

    .wishlist-empty {
        min-height: 430px;

        display: flex;

        flex-direction: column;

        align-items: center;
        justify-content: center;

        padding:
            3rem
            1.5rem;

        border:
            1px solid #ddd6cd;

        background: #faf8f5;

        text-align: center;
    }


    .wishlist-empty-icon {
        width: 66px;
        height: 66px;

        display: grid;

        place-items: center;

        margin-bottom: 1.5rem;

        border:
            1px solid #d8d1c8;

        border-radius: 50%;

        color: #c4622f;

        font-size: 24px;
    }


    .wishlist-empty h2 {
        font-family:
            'Cormorant Garamond',
            Georgia,
            serif;

        font-size:
            clamp(
                2.4rem,
                5vw,
                4rem
            );

        font-weight: 400;
    }


    .wishlist-empty p {
        max-width: 460px;

        margin-top: .75rem;

        color: #77716a;

        font-size: 13px;

        line-height: 1.7;
    }


    .wishlist-empty-button {
        min-height: 46px;

        display: inline-flex;

        align-items: center;
        justify-content: center;

        gap: .6rem;

        margin-top: 1.7rem;

        padding:
            0
            1.4rem;

        background: #171512;

        color: #ffffff;

        font-size: 9px;
        font-weight: 600;

        letter-spacing: .14em;

        text-transform: uppercase;
    }


    /* =========================================================
       PAGINATION
    ========================================================= */

    .wishlist-pagination {
        margin-top: 3.5rem;
    }


    /* =========================================================
       TABLET
    ========================================================= */

    @media (max-width: 1100px) {

        .wishlist-grid {
            grid-template-columns:
                repeat(
                    3,
                    minmax(0, 1fr)
                );
        }

    }


    /* =========================================================
       MOBILE / TABLET
    ========================================================= */

    @media (max-width: 760px) {

        .wishlist-page {
            padding:
                2.1rem
                1rem
                4rem;
        }


        /* -----------------------------------------------------
           HEADER
        ----------------------------------------------------- */

        .wishlist-page-header {
            display: block;

            margin-bottom: 1.5rem;

            padding-bottom: 1.5rem;
        }


        .wishlist-title {
            font-size: 3rem;
        }


        .wishlist-header-meta {
            margin-top: .6rem;

            font-size: 11px;
        }


        .wishlist-continue {
            margin-top: 1.2rem;
        }


        /* -----------------------------------------------------
           TWO PRODUCTS
        ----------------------------------------------------- */

        .wishlist-grid {
            grid-template-columns:
                repeat(
                    2,
                    minmax(0, 1fr)
                );

            gap:
                2rem
                .65rem;
        }


        /* -----------------------------------------------------
           CARD
        ----------------------------------------------------- */

        .wishlist-card-body {
            padding-top: .7rem;
        }


        .wishlist-card-name {
            font-size: 17px;

            line-height: 1.12;
        }


        .wishlist-material {
            margin-top: .3rem;

            font-size: 9.5px;

            line-height: 1.45;
        }


        .wishlist-price {
            gap: .4rem;

            margin-top: .5rem;
        }


        .wishlist-current-price {
            font-size: 12px;
        }


        .wishlist-old-price {
            font-size: 9.5px;
        }


        /* -----------------------------------------------------
           HEART
        ----------------------------------------------------- */

        .wishlist-remove-form {
            top: .42rem;
            right: .42rem;
        }


        .wishlist-remove-button {
            width: 31px;
            height: 31px;

            font-size: 11px;
        }


        /* -----------------------------------------------------
           BADGE
        ----------------------------------------------------- */

        .wishlist-badge {
            top: .42rem;
            left: .42rem;

            padding:
                .28rem
                .38rem;

            font-size: 7px;
        }


        /* -----------------------------------------------------
           STOCK
        ----------------------------------------------------- */

        .wishlist-stock {
            gap: .35rem;

            min-height: 32px;

            margin-top: .45rem;

            font-size: 8.5px;

            line-height: 1.35;
        }


        .wishlist-stock-dot {
            width: 5px;
            height: 5px;

            flex-basis: 5px;
        }


        /* -----------------------------------------------------
           IMPORTANT MOBILE ACTION FIX
        ----------------------------------------------------- */

        .wishlist-actions {
            /*
            |--------------------------------------------------------------------------
            | NO full-width VIEW button.
            |
            | Add to Bag | eye
            |--------------------------------------------------------------------------
            */

            grid-template-columns:
                minmax(0, 1fr)
                36px;

            gap: .35rem;

            margin-top: .7rem;
        }


        .wishlist-add-button {
            height: 36px;

            min-height: 36px;

            gap: .3rem;

            padding:
                0
                .35rem;

            font-size: 7px;

            letter-spacing: .05em;
        }


        .wishlist-add-button i {
            font-size: 8px;
        }


        .wishlist-view-button {
            width: 36px;
            height: 36px;

            border-color: #d1cac1;

            background: #faf8f5;

            font-size: 11px;
        }


        .wishlist-view-button:hover {
            background: #171512;

            color: #ffffff;
        }

    }


    /* =========================================================
       VERY SMALL MOBILE
    ========================================================= */

    @media (max-width: 390px) {

        .wishlist-page {
            padding-left: .8rem;
            padding-right: .8rem;
        }


        .wishlist-grid {
            gap:
                1.7rem
                .5rem;
        }


        .wishlist-card-name {
            font-size: 16px;
        }


        .wishlist-actions {
            grid-template-columns:
                minmax(0, 1fr)
                34px;

            gap: .3rem;
        }


        .wishlist-add-button {
            height: 34px;

            min-height: 34px;

            font-size: 6.5px;
        }


        .wishlist-view-button {
            width: 34px;
            height: 34px;

            font-size: 10px;
        }

    }

</style>



<div class="wishlist-page">


    {{-- ========================================================
        HEADER
    ======================================================== --}}

    <header class="wishlist-page-header">


        <div>


            <p class="wishlist-eyebrow">

                Your saved pieces

            </p>


            <h1 class="wishlist-title">

                Your wishlist

            </h1>


            <p class="wishlist-header-meta">

                {{ $wishlistCount }}

                {{ $wishlistCount === 1 ? 'piece' : 'pieces' }}

                saved

            </p>


        </div>



        <a
            class="wishlist-continue"
            href="{{ route('new-arrivals') }}"
        >

            Continue shopping

            <i class="fa-solid fa-arrow-right-long"></i>

        </a>


    </header>



    {{-- ========================================================
        PRODUCTS
    ======================================================== --}}

    @if($products->count())


        <div class="wishlist-grid">


            @foreach($products as $product)


                @php

                    $images = $product->images;


                    $primaryImage =
                        $images->firstWhere(
                            'is_primary',
                            true
                        )
                        ?: $images->first();


                    $imagePath =
                        optional(
                            $primaryImage
                        )->path
                        ?: 'images/placeholder.webp';

                @endphp



                <article class="wishlist-card">


                    {{-- =================================================
                        IMAGE
                    ================================================== --}}

                    <div class="wishlist-card-media">


                        <a
                            class="wishlist-card-image-link"
                            href="{{ route('products.show', $product) }}"
                            aria-label="View {{ $product->name }}"
                        >

                            <img
                                class="wishlist-card-image"
                                src="{{ asset($imagePath) }}"
                                alt="{{ optional($primaryImage)->alt_text ?: $product->name }}"
                                loading="lazy"
                            >

                        </a>



                        {{-- BADGE --}}

                        @if($product->discount_percent)

                            <span class="wishlist-badge">

                                -{{ $product->discount_percent }}%

                            </span>

                        @elseif($product->is_new)

                            <span class="wishlist-badge">

                                New

                            </span>

                        @endif



                        {{-- =================================================
                            REMOVE FAVOURITE
                        ================================================== --}}

                        <form
                            class="wishlist-remove-form"
                             action="{{ route('wishlist.toggle', $product) }}"
                            method="POST"

                            data-ajax-wishlist

                            data-product-id="{{ $product->id }}"
                        >

                            @csrf


                            <button
                                type="submit"
                                class="wishlist-remove-button"
                                aria-label="Remove {{ $product->name }} from favourites"
                                title="Remove from favourites"
                            >

                                <i class="fa-solid fa-heart"></i>

                            </button>


                        </form>


                    </div>



                    {{-- =================================================
                        INFORMATION
                    ================================================== --}}

                    <div class="wishlist-card-body">


                        <a
                            class="wishlist-card-name"
                            href="{{ route('products.show', $product) }}"
                        >

                            {{ $product->name }}

                        </a>



                        @if($product->material)

                            <p class="wishlist-material">

                                {{ $product->material }}

                            </p>

                        @endif



                        {{-- PRICE --}}

                        <div class="wishlist-price">


                            <strong
                                class="
                                    wishlist-current-price
                                    {{ $product->compare_price ? 'is-sale' : '' }}
                                "
                            >

                                ৳{{ number_format((float) $product->price) }}

                            </strong>



                            @if($product->compare_price)

                                <span class="wishlist-old-price">

                                    ৳{{ number_format((float) $product->compare_price) }}

                                </span>

                            @endif


                        </div>



                        {{-- =================================================
                            STOCK
                        ================================================== --}}

                        <div
                            class="
                                wishlist-stock
                                {{ $product->stock <= 0 ? 'is-out' : '' }}
                            "
                        >

                            <span class="wishlist-stock-dot"></span>


                            <span>

                                @if($product->stock > 0)

                                    In stock · {{ $product->stock }} available

                                @else

                                    Currently unavailable

                                @endif

                            </span>


                        </div>



                        {{-- =================================================
                            ACTIONS
                        ================================================== --}}

                        <div class="wishlist-actions">


                            {{-- ADD TO BAG --}}
                                <form
                                    action="{{ route('cart.store', $product) }}"
                                    method="POST"

                                    class="product-hover-form"

                                    data-ajax-cart
                                >

                                @csrf


                                <input
                                    type="hidden"
                                    name="quantity"
                                    value="1"
                                >


                                <button
                                    type="submit"
                                    class="wishlist-add-button"
                                    @disabled($product->stock <= 0)
                                >

                                    <i class="fa-solid fa-bag-shopping"></i>


                                    <span>

                                        {{
                                            $product->stock > 0
                                                ? 'Add to Bag'
                                                : 'Sold Out'
                                        }}

                                    </span>


                                </button>


                            </form>



                            {{-- VIEW PRODUCT --}}

                            <a
                                class="wishlist-view-button"
                                href="{{ route('products.show', $product) }}"
                                aria-label="View {{ $product->name }}"
                                title="View product"
                            >

                                <i class="fa-regular fa-eye"></i>

                            </a>


                        </div>


                    </div>


                </article>


            @endforeach


        </div>



        {{-- ====================================================
            PAGINATION
        ==================================================== --}}

        @if($products->hasPages())

            <div class="wishlist-pagination">

                {{ $products->onEachSide(1)->links() }}

            </div>

        @endif


    @else


        {{-- ====================================================
            EMPTY STATE
        ==================================================== --}}

        <div class="wishlist-empty">


            <span class="wishlist-empty-icon">

                <i class="fa-regular fa-heart"></i>

            </span>


            <h2>

                Save the pieces you love.

            </h2>


            <p>

                Keep your favourite AATCHALA pieces together
                and return whenever you're ready.

            </p>


            <a
                class="wishlist-empty-button"
                href="{{ route('new-arrivals') }}"
            >

                Explore New Arrivals

                <i class="fa-solid fa-arrow-right-long"></i>

            </a>


        </div>


    @endif


</div>


@endsection