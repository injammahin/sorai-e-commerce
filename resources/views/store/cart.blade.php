@extends('layouts.store')

@section('title', 'Your Shopping Bag | AATCHALA')

@section(
    'description',
    'Review your AATCHALA shopping bag and continue to checkout.'
)


@section('content')


@php

    $cartService =
        app(\App\Services\CartService::class);

    $coupon =
        $cartService->coupon();

    $cartQuantity =
        $items->sum('quantity');

@endphp


<style>

    /* =========================================================
       CART PAGE
    ========================================================= */

    .premium-cart-page {
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

    .cart-page-header {
        display: flex;

        align-items: flex-end;
        justify-content: space-between;

        gap: 2rem;

        margin-bottom:
            clamp(2rem, 4vw, 3.5rem);

        padding-bottom:
            clamp(1.5rem, 3vw, 2.5rem);

        border-bottom:
            1px solid
            #ddd6cd;
    }


    .cart-eyebrow {
        margin-bottom: .55rem;

        color: #8c857d;

        font-size: 9px;

        font-weight: 600;

        letter-spacing: .22em;

        text-transform: uppercase;
    }


    .cart-page-title {
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


    .cart-page-meta {
        margin-top: .9rem;

        color: #77716a;

        font-size: 12px;
    }


    .cart-continue-link {
        display: inline-flex;

        align-items: center;

        gap: .6rem;

        padding-bottom: .35rem;

        border-bottom: 1px solid #171512;

        font-size: 9px;

        font-weight: 600;

        letter-spacing: .14em;

        text-transform: uppercase;

        white-space: nowrap;

        transition:
            color .25s ease,
            border-color .25s ease;
    }


    .cart-continue-link:hover {
        color: #c4622f;

        border-color: #c4622f;
    }


    /* =========================================================
       MAIN CART LAYOUT
    ========================================================= */

    .premium-cart-layout {
        display: grid;

        grid-template-columns:
            minmax(0, 1fr)
            minmax(330px, 400px);

        gap:
            clamp(2rem, 5vw, 5rem);

        align-items: start;
    }


    /* =========================================================
       CART ITEMS
    ========================================================= */

    .premium-cart-items {
        min-width: 0;

        border-top: 1px solid #ddd6cd;
    }


    .premium-cart-item {
        display: grid;

        grid-template-columns:
            140px
            minmax(0, 1fr)
            auto;

        gap: 1.5rem;

        align-items: start;

        padding:
            1.5rem
            0;

        border-bottom:
            1px solid
            #ddd6cd;
    }


    /* =========================================================
       PRODUCT IMAGE
    ========================================================= */

    .cart-product-image {
        display: block;

        width: 140px;

        aspect-ratio: 4 / 5;

        overflow: hidden;

        background: #eee8df;
    }


    .cart-product-image img {
        display: block;

        width: 100%;
        height: 100%;

        object-fit: contain;
        object-position: center;

        transition:
            transform .55s
            cubic-bezier(.22, 1, .36, 1);
    }


    .cart-product-image:hover img {
        transform: scale(1.025);
    }


    /* =========================================================
       PRODUCT DETAILS
    ========================================================= */

    .cart-product-content {
        min-width: 0;
    }


    .cart-product-name {
        display: inline-block;

        font-family:
            'Cormorant Garamond',
            Georgia,
            serif;

        font-size:
            clamp(
                1.45rem,
                2vw,
                1.9rem
            );

        line-height: 1.1;

        transition: color .25s ease;
    }


    .cart-product-name:hover {
        color: #c4622f;
    }


    .cart-product-options {
        margin-top: .45rem;

        color: #79726b;

        font-size: 11px;

        line-height: 1.5;
    }


    .cart-unit-price {
        margin-top: .6rem;

        font-size: 13px;

        font-weight: 600;
    }


    /* =========================================================
       QUANTITY
    ========================================================= */

    .cart-quantity-form {
        display: flex;

        align-items: center;

        flex-wrap: wrap;

        gap: .7rem;

        margin-top: 1.25rem;
    }


    .cart-quantity-control {
        height: 42px;

        display: grid;

        grid-template-columns:
            36px
            42px
            36px;

        border:
            1px solid
            #d6cfc6;

        background: #ffffff;
    }


    .cart-quantity-button {
        display: grid;

        place-items: center;

        border: 0;

        background: transparent;

        color: #171512;

        cursor: pointer;

        font-size: 13px;

        transition:
            background .2s ease,
            color .2s ease;
    }


    .cart-quantity-button:hover {
        background: #173d32;

        color: #ffffff;
    }


    .cart-quantity-input {
        width: 42px;

        padding: 0;

        border: 0;

        border-left:
            1px solid
            #e2dcd4;

        border-right:
            1px solid
            #e2dcd4;

        background: transparent;

        text-align: center;

        font-size: 12px;

        outline: 0;

        -moz-appearance: textfield;
    }


    .cart-quantity-input::-webkit-inner-spin-button,
    .cart-quantity-input::-webkit-outer-spin-button {

        margin: 0;

        -webkit-appearance: none;
    }


    .cart-update-button {
        min-height: 42px;

        padding:
            0
            1rem;

        border:
            1px solid
            #d6cfc6;

        background: transparent;

        font-size: 8px;

        font-weight: 600;

        letter-spacing: .12em;

        text-transform: uppercase;

        transition:
            color .25s ease,
            border-color .25s ease,
            background .25s ease;
    }


    .cart-update-button:hover {
        border-color: #171512;

        background: #171512;

        color: #ffffff;
    }


    /* =========================================================
       ITEM TOTAL / REMOVE
    ========================================================= */

    .cart-line-actions {
        min-width: 100px;

        text-align: right;
    }


    .cart-line-total {
        display: block;

        font-size: 14px;

        font-weight: 600;
    }


    .cart-remove-form {
        margin-top: 1rem;
    }


    .cart-remove-button {
        display: inline-flex;

        align-items: center;

        gap: .4rem;

        color: #777069;

        font-size: 9px;

        letter-spacing: .08em;

        text-transform: uppercase;

        transition: color .25s ease;
    }


    .cart-remove-button:hover {
        color: #c4622f;
    }


    /* =========================================================
       ORDER SUMMARY
    ========================================================= */

    .premium-cart-summary {
        position: sticky;

        top: 130px;

        padding:
            clamp(
                1.4rem,
                3vw,
                2rem
            );

        border:
            1px solid
            #dcd5cc;

        background: #faf8f5;
    }


    .cart-summary-eyebrow {
        color: #8d867e;

        font-size: 9px;

        font-weight: 600;

        letter-spacing: .18em;

        text-transform: uppercase;
    }


    .cart-summary-title {
        margin-top: .4rem;

        font-family:
            'Cormorant Garamond',
            Georgia,
            serif;

        font-size: 2.3rem;

        font-weight: 400;
    }


    .cart-summary-lines {
        display: grid;

        gap: .9rem;

        margin-top: 1.8rem;
    }


    .cart-summary-line {
        display: flex;

        align-items: center;
        justify-content: space-between;

        gap: 1rem;

        color: #625c56;

        font-size: 12px;
    }


    .cart-summary-line strong {
        color: #171512;

        font-weight: 600;
    }


    .cart-summary-discount,
    .cart-summary-discount strong {
        color: #2e7353;
    }


    .cart-summary-total {
        margin-top: .4rem;

        padding-top: 1.1rem;

        border-top:
            1px solid
            #d8d1c8;

        color: #171512;

        font-size: 16px;
    }


    .cart-summary-total strong {
        font-size: 18px;
    }


    /* =========================================================
       COUPON
    ========================================================= */

    .cart-coupon-section {
        margin-top: 1.7rem;

        padding-top: 1.5rem;

        border-top:
            1px solid
            #ddd6cd;
    }


    .cart-coupon-title {
        margin-bottom: .7rem;

        font-size: 9px;

        font-weight: 600;

        letter-spacing: .15em;

        text-transform: uppercase;
    }


    .cart-coupon-form {
        display: grid;

        grid-template-columns:
            minmax(0, 1fr)
            auto;
    }


    .cart-coupon-input {
        min-width: 0;
        height: 44px;

        padding:
            0
            .9rem;

        border:
            1px solid
            #d5cec5;

        border-right: 0;

        background: #ffffff;

        font-size: 11px;

        outline: none;
    }


    .cart-coupon-input:focus {
        border-color: #173d32;
    }


    .cart-coupon-button {
        min-width: 78px;

        padding:
            0
            .8rem;

        border:
            1px solid
            #171512;

        background: transparent;

        font-size: 8px;

        font-weight: 600;

        letter-spacing: .12em;

        text-transform: uppercase;

        transition:
            background .25s ease,
            color .25s ease;
    }


    .cart-coupon-button:hover {
        background: #171512;

        color: #ffffff;
    }


    /* =========================================================
       ACTIVE COUPON
    ========================================================= */

    .cart-active-coupon {
        display: flex;

        align-items: center;
        justify-content: space-between;

        gap: 1rem;

        padding: .8rem;

        border:
            1px solid
            #cddbd3;

        background: #f4f8f5;

        color: #2d674c;

        font-size: 10px;
    }


    .cart-active-coupon strong {
        letter-spacing: .1em;

        text-transform: uppercase;
    }


    .cart-remove-coupon {
        color: #765f57;

        font-size: 9px;

        text-decoration: underline;
    }


    /* =========================================================
       CHECKOUT
    ========================================================= */

    .cart-checkout-button {
        width: 100%;
        min-height: 50px;

        display: flex;

        align-items: center;
        justify-content: center;

        gap: .7rem;

        margin-top: 1.5rem;

        padding:
            .7rem
            1rem;

        background: #173d32;

        color: #ffffff;

        font-size: 9px;

        font-weight: 600;

        letter-spacing: .14em;

        text-transform: uppercase;

        transition:
            background .25s ease,
            transform .25s ease;
    }


    .cart-checkout-button:hover {
        background: #c4622f;

        transform: translateY(-1px);
    }


    .cart-checkout-button i {
        transition: transform .25s ease;
    }


    .cart-checkout-button:hover i {
        transform: translateX(4px);
    }


    .cart-summary-continue {
        display: block;

        margin-top: 1rem;

        color: #68625b;

        font-size: 9px;

        text-align: center;

        text-decoration: underline;
    }


    /* =========================================================
       TRUST AREA
    ========================================================= */

    .cart-trust {
        display: grid;

        gap: .75rem;

        margin-top: 1.5rem;

        padding-top: 1.4rem;

        border-top:
            1px solid
            #ddd6cd;
    }


    .cart-trust-item {
        display: flex;

        align-items: center;

        gap: .65rem;

        color: #79726a;

        font-size: 10px;

        line-height: 1.5;
    }


    .cart-trust-item i {
        width: 18px;

        color: #173d32;

        text-align: center;
    }


    /* =========================================================
       EMPTY CART
    ========================================================= */

    .premium-cart-empty {
        min-height: 450px;

        display: flex;

        flex-direction: column;

        align-items: center;
        justify-content: center;

        padding:
            3rem
            1.5rem;

        border:
            1px solid
            #ddd6cd;

        text-align: center;

        background: #faf8f5;
    }


    .cart-empty-icon {
        width: 68px;
        height: 68px;

        display: grid;

        place-items: center;

        margin-bottom: 1.4rem;

        border:
            1px solid
            #d8d1c8;

        border-radius: 50%;

        color: #173d32;

        font-size: 24px;
    }


    .premium-cart-empty h2 {
        font-family:
            'Cormorant Garamond',
            Georgia,
            serif;

        font-size:
            clamp(
                2.5rem,
                5vw,
                4rem
            );

        font-weight: 400;
    }


    .premium-cart-empty p {
        max-width: 460px;

        margin-top: .7rem;

        color: #77716a;

        font-size: 13px;

        line-height: 1.7;
    }


    .cart-empty-shop {
        min-height: 46px;

        display: inline-flex;

        align-items: center;

        gap: .6rem;

        margin-top: 1.6rem;

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
       TABLET
    ========================================================= */

    @media (max-width: 1000px) {

        .premium-cart-layout {
            grid-template-columns:
                minmax(0, 1fr)
                310px;

            gap: 2rem;
        }


        .premium-cart-item {
            grid-template-columns:
                115px
                minmax(0, 1fr);

            gap: 1.2rem;
        }


        .cart-product-image {
            width: 115px;
        }


        .cart-line-actions {
            grid-column: 2;

            display: flex;

            align-items: center;
            justify-content: space-between;

            min-width: 0;

            text-align: left;
        }


        .cart-remove-form {
            margin-top: 0;
        }

    }


    /* =========================================================
       MOBILE
    ========================================================= */

    @media (max-width: 760px) {

        .premium-cart-page {
            padding:
                2.2rem
                1rem
                4rem;
        }


        .cart-page-header {
            display: block;

            margin-bottom: 1.5rem;

            padding-bottom: 1.5rem;
        }


        .cart-page-title {
            font-size: 3rem;
        }


        .cart-continue-link {
            margin-top: 1.3rem;
        }


        .premium-cart-layout {
            display: block;
        }


        .premium-cart-item {
            grid-template-columns:
                92px
                minmax(0, 1fr);

            gap: 1rem;

            padding:
                1rem
                0;
        }


        .cart-product-image {
            width: 92px;
        }


        .cart-product-name {
            font-size: 1.35rem;
        }


        .cart-product-options {
            font-size: 9px;
        }


        .cart-unit-price {
            margin-top: .45rem;

            font-size: 12px;
        }


        .cart-quantity-form {
            gap: .45rem;

            margin-top: .8rem;
        }


        .cart-quantity-control {
            height: 36px;

            grid-template-columns:
                30px
                34px
                30px;
        }


        .cart-quantity-input {
            width: 34px;

            font-size: 11px;
        }


        .cart-update-button {
            min-height: 36px;

            padding:
                0
                .65rem;

            font-size: 7px;
        }


        .cart-line-actions {
            grid-column:
                1 / -1;

            margin-left: 108px;

            padding-top: .35rem;
        }


        .cart-line-total {
            font-size: 12px;
        }


        .cart-remove-button {
            font-size: 8px;
        }


        .premium-cart-summary {
            position: static;

            margin-top: 2rem;

            padding: 1.25rem;
        }


        .cart-summary-title {
            font-size: 2rem;
        }


        .cart-checkout-button {
            min-height: 48px;
        }

    }


    /* =========================================================
       SMALL PHONE
    ========================================================= */

    @media (max-width: 390px) {

        .premium-cart-page {
            padding-left: .8rem;
            padding-right: .8rem;
        }


        .premium-cart-item {
            grid-template-columns:
                82px
                minmax(0, 1fr);

            gap: .8rem;
        }


        .cart-product-image {
            width: 82px;
        }


        .cart-line-actions {
            margin-left: 94px;
        }


        .cart-product-name {
            font-size: 1.23rem;
        }


        .cart-update-button {
            padding:
                0
                .5rem;
        }

    }

</style>



<div class="premium-cart-page">


    {{-- ========================================================
        PAGE HEADER
    ======================================================== --}}

    <header class="cart-page-header">


        <div>


            <p class="cart-eyebrow">

                Your selection

            </p>


            <h1 class="cart-page-title">

                Your bag

            </h1>


            @if(!$items->isEmpty())

                <p class="cart-page-meta">

                    {{ $cartQuantity }}

                    {{ $cartQuantity === 1 ? 'item' : 'items' }}

                    in your bag

                </p>

            @endif


        </div>



        @if(!$items->isEmpty())

            <a
                class="cart-continue-link"
                href="{{ route('new-arrivals') }}"
            >

                Continue shopping

                <i class="fa-solid fa-arrow-right-long"></i>

            </a>

        @endif


    </header>



    {{-- ========================================================
        EMPTY CART
    ======================================================== --}}

    @if($items->isEmpty())


        <div class="premium-cart-empty">


            <span class="cart-empty-icon">

                <i class="fa-solid fa-bag-shopping"></i>

            </span>


            <h2>

                Your bag is waiting.

            </h2>


            <p>

                Discover handcrafted pieces from AATCHALA
                and add something made with a story.

            </p>


            <a
                class="cart-empty-shop"
                href="{{ route('new-arrivals') }}"
            >

                Explore new arrivals

                <i class="fa-solid fa-arrow-right-long"></i>

            </a>


        </div>


    @else


        {{-- ====================================================
            CART
        ==================================================== --}}

        <div class="premium-cart-layout">


            {{-- =================================================
                ITEMS
            ================================================== --}}

            <section class="premium-cart-items">


                @foreach($items as $item)

                    @php

                        $productImage =
                            optional(
                                $item->product->primaryImage
                            )->path
                            ?: 'images/placeholder.webp';

                        $options = collect([
                            $item->color,
                            $item->size,
                        ])->filter()->implode(' · ');

                    @endphp


                    <article class="premium-cart-item">


                        {{-- IMAGE --}}

                        <a
                            class="cart-product-image"
                            href="{{ route('products.show', $item->product) }}"
                        >

                            <img
                                src="{{ asset($productImage) }}"
                                alt="{{ $item->product->name }}"
                                loading="lazy"
                            >

                        </a>



                        {{-- PRODUCT INFO --}}

                        <div class="cart-product-content">


                            <a
                                class="cart-product-name"
                                href="{{ route('products.show', $item->product) }}"
                            >

                                {{ $item->product->name }}

                            </a>



                            @if($options)

                                <p class="cart-product-options">

                                    {{ $options }}

                                </p>

                            @endif



                            @if($item->product->material)

                                <p class="cart-product-options">

                                    {{ $item->product->material }}

                                </p>

                            @endif



                            <p class="cart-unit-price">

                                ৳{{ number_format((float) $item->price) }}

                                <span class="muted">

                                    each

                                </span>

                            </p>



                            {{-- QUANTITY --}}

                            <form
                                class="cart-quantity-form"
                                method="POST"
                                action="{{ route('cart.update', $item->key) }}"
                            >

                                @csrf

                                @method('PATCH')



                                <div
                                    class="cart-quantity-control"
                                    data-cart-quantity
                                >


                                    <button
                                        type="button"
                                        class="cart-quantity-button"
                                        data-qty-minus
                                        aria-label="Decrease quantity"
                                    >

                                        <i class="fa-solid fa-minus"></i>

                                    </button>



                                    <input
                                        class="cart-quantity-input"
                                        type="number"
                                        name="quantity"
                                        value="{{ $item->quantity }}"
                                        min="1"
                                        max="10"
                                        inputmode="numeric"
                                        data-qty-input
                                        aria-label="Quantity for {{ $item->product->name }}"
                                    >



                                    <button
                                        type="button"
                                        class="cart-quantity-button"
                                        data-qty-plus
                                        aria-label="Increase quantity"
                                    >

                                        <i class="fa-solid fa-plus"></i>

                                    </button>


                                </div>



                                <button
                                    type="submit"
                                    class="cart-update-button"
                                >

                                    Update

                                </button>


                            </form>


                        </div>



                        {{-- PRICE / REMOVE --}}

                        <div class="cart-line-actions">


                            <strong class="cart-line-total">

                                ৳{{ number_format((float) $item->line_total) }}

                            </strong>



                            <form
                                class="cart-remove-form"
                                method="POST"
                                action="{{ route('cart.destroy', $item->key) }}"
                            >

                                @csrf

                                @method('DELETE')


                                <button
                                    type="submit"
                                    class="cart-remove-button"
                                >

                                    <i class="fa-regular fa-trash-can"></i>

                                    Remove

                                </button>


                            </form>


                        </div>


                    </article>


                @endforeach


            </section>



            {{-- =================================================
                SUMMARY
            ================================================== --}}

            <aside class="premium-cart-summary">


                <p class="cart-summary-eyebrow">

                    Checkout

                </p>


                <h2 class="cart-summary-title">

                    Order summary

                </h2>



                <div class="cart-summary-lines">


                    {{-- SUBTOTAL --}}

                    <p class="cart-summary-line">

                        <span>

                            Subtotal

                        </span>


                        <strong>

                            ৳{{ number_format((float) $totals['subtotal']) }}

                        </strong>

                    </p>



                    {{-- DISCOUNT --}}

                    @if($totals['discount'] > 0)

                        <p
                            class="
                                cart-summary-line
                                cart-summary-discount
                            "
                        >

                            <span>

                                Discount

                            </span>


                            <strong>

                                −৳{{ number_format((float) $totals['discount']) }}

                            </strong>

                        </p>

                    @endif



                    {{-- DELIVERY --}}

                    <p class="cart-summary-line">

                        <span>

                            Delivery

                        </span>


                        <strong>

                            @if($totals['shipping'] > 0)

                                ৳{{ number_format((float) $totals['shipping']) }}

                            @else

                                Complimentary

                            @endif

                        </strong>

                    </p>



                    {{-- TAX --}}

                    @if(($totals['tax'] ?? 0) > 0)

                        <p class="cart-summary-line">

                            <span>

                                Tax

                            </span>


                            <strong>

                                ৳{{ number_format((float) $totals['tax']) }}

                            </strong>

                        </p>

                    @endif



                    {{-- TOTAL --}}

                    <p
                        class="
                            cart-summary-line
                            cart-summary-total
                        "
                    >

                        <span>

                            Total

                        </span>


                        <strong>

                            ৳{{ number_format((float) $totals['total']) }}

                        </strong>

                    </p>


                </div>



                {{-- =================================================
                    COUPON
                ================================================== --}}

                <div class="cart-coupon-section">


                    <p class="cart-coupon-title">

                        Promo code

                    </p>



                    @if($coupon)


                        <div class="cart-active-coupon">


                            <div>

                                <i class="fa-solid fa-tag mr-1"></i>

                                <strong>

                                    {{ $coupon->code }}

                                </strong>

                            </div>



                            <form
                                method="POST"
                                action="{{ route('coupon.remove') }}"
                            >

                                @csrf

                                @method('DELETE')


                                <button
                                    type="submit"
                                    class="cart-remove-coupon"
                                >

                                    Remove

                                </button>


                            </form>


                        </div>


                    @else


                        <form
                            class="cart-coupon-form"
                            method="POST"
                            action="{{ route('coupon.apply') }}"
                        >

                            @csrf


                            <input
                                class="cart-coupon-input"
                                type="text"
                                name="code"
                                placeholder="Enter code"
                                autocomplete="off"
                            >


                            <button
                                class="cart-coupon-button"
                                type="submit"
                            >

                                Apply

                            </button>


                        </form>


                    @endif


                </div>



                {{-- =================================================
                    CHECKOUT
                ================================================== --}}

                <a
                    class="cart-checkout-button"
                    href="{{ route('checkout') }}"
                >

                    Proceed to Checkout

                    <i class="fa-solid fa-arrow-right-long"></i>

                </a>



                <a
                    class="cart-summary-continue"
                    href="{{ route('new-arrivals') }}"
                >

                    Continue shopping

                </a>



                {{-- =================================================
                    TRUST
                ================================================== --}}

                <div class="cart-trust">


                    <p class="cart-trust-item">

                        <i class="fa-solid fa-lock"></i>

                        Secure checkout

                    </p>


                    <p class="cart-trust-item">

                        <i class="fa-solid fa-box"></i>

                        Carefully packed for delivery

                    </p>


                    <p class="cart-trust-item">

                        <i class="fa-regular fa-heart"></i>

                        Authentic Bangladeshi craft

                    </p>


                </div>


            </aside>


        </div>


    @endif


</div>



{{-- ============================================================
    QUANTITY CONTROLS
============================================================ --}}

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {


        document
            .querySelectorAll(
                '[data-cart-quantity]'
            )
            .forEach(
                function (control) {


                    const input =
                        control.querySelector(
                            '[data-qty-input]'
                        );


                    const minus =
                        control.querySelector(
                            '[data-qty-minus]'
                        );


                    const plus =
                        control.querySelector(
                            '[data-qty-plus]'
                        );


                    if (
                        !input ||
                        !minus ||
                        !plus
                    ) {
                        return;
                    }



                    /*
                    |--------------------------------------------------------------------------
                    | DECREASE
                    |--------------------------------------------------------------------------
                    */

                    minus.addEventListener(
                        'click',
                        function () {


                            const current =
                                parseInt(
                                    input.value || '1',
                                    10
                                );


                            const minimum =
                                parseInt(
                                    input.min || '1',
                                    10
                                );


                            input.value =
                                Math.max(
                                    minimum,
                                    current - 1
                                );


                        }
                    );



                    /*
                    |--------------------------------------------------------------------------
                    | INCREASE
                    |--------------------------------------------------------------------------
                    */

                    plus.addEventListener(
                        'click',
                        function () {


                            const current =
                                parseInt(
                                    input.value || '1',
                                    10
                                );


                            const maximum =
                                parseInt(
                                    input.max || '10',
                                    10
                                );


                            input.value =
                                Math.min(
                                    maximum,
                                    current + 1
                                );


                        }
                    );



                    /*
                    |--------------------------------------------------------------------------
                    | CLEAN MANUAL INPUT
                    |--------------------------------------------------------------------------
                    */

                    input.addEventListener(
                        'change',
                        function () {


                            let value =
                                parseInt(
                                    input.value || '1',
                                    10
                                );


                            const minimum =
                                parseInt(
                                    input.min || '1',
                                    10
                                );


                            const maximum =
                                parseInt(
                                    input.max || '10',
                                    10
                                );


                            if (
                                Number.isNaN(value)
                            ) {

                                value =
                                    minimum;

                            }


                            input.value =
                                Math.min(
                                    maximum,
                                    Math.max(
                                        minimum,
                                        value
                                    )
                                );


                        }
                    );


                }
            );


    }
);

</script>


@endsection