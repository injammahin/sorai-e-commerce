@extends('layouts.store')

@section('title', 'Secure Checkout | AATCHALA')

@section('description', 'Complete your AATCHALA order securely.')


@section('content')


<style>

    /* =========================================================
       CHECKOUT PAGE
    ========================================================= */

    .checkout-page {
        width: 100%;
        max-width: 1380px;

        margin: 0 auto;

        padding:
            clamp(2rem, 5vw, 4.5rem)
            clamp(1.25rem, 4vw, 4rem)
            clamp(4rem, 7vw, 7rem);
    }


    /* =========================================================
       PAGE HEADER
    ========================================================= */

    .checkout-page-header {
        margin-bottom:
            clamp(2rem, 4vw, 3.5rem);

        padding-bottom:
            clamp(1.5rem, 3vw, 2.25rem);

        border-bottom: 1px solid #ddd6cd;
    }


    .checkout-eyebrow {
        margin-bottom: .55rem;

        color: #8c857d;

        font-size: 9px;
        font-weight: 600;

        letter-spacing: .2em;

        text-transform: uppercase;
    }


    .checkout-title {
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


    .checkout-subtitle {
        max-width: 620px;

        margin-top: 1rem;

        color: #77716a;

        font-size: 13px;

        line-height: 1.7;
    }


    /* =========================================================
       MAIN LAYOUT
    ========================================================= */

    .checkout-layout-premium {
        display: grid;

        grid-template-columns:
            minmax(0, 1fr)
            minmax(330px, 400px);

        gap:
            clamp(
                2.5rem,
                5vw,
                5rem
            );

        align-items: start;
    }


    /* =========================================================
       SECTION
    ========================================================= */

    .checkout-section {
        padding:
            clamp(
                1.5rem,
                3vw,
                2.25rem
            );

        border:
            1px solid
            #ddd6cd;

        background: #faf8f5;
    }


    .checkout-section + .checkout-section {
        margin-top: 1.5rem;
    }


    .checkout-section-header {
        margin-bottom: 1.5rem;
    }


    .checkout-section-number {
        display: inline-flex;

        align-items: center;
        justify-content: center;

        width: 28px;
        height: 28px;

        margin-bottom: .8rem;

        border-radius: 50%;

        background: #173d32;

        color: #fff;

        font-size: 10px;

        font-weight: 600;
    }


    .checkout-section-title {
        font-family:
            'Cormorant Garamond',
            Georgia,
            serif;

        font-size:
            clamp(
                2rem,
                3vw,
                2.65rem
            );

        font-weight: 400;

        line-height: 1;
    }


    .checkout-section-description {
        margin-top: .5rem;

        color: #827b74;

        font-size: 11px;

        line-height: 1.6;
    }


    /* =========================================================
       FORM GRID
    ========================================================= */

    .checkout-form-grid {
        display: grid;

        grid-template-columns:
            repeat(
                2,
                minmax(0, 1fr)
            );

        gap:
            1.1rem
            1rem;
    }


    .checkout-field-full {
        grid-column: 1 / -1;
    }


    /* =========================================================
       FIELD
    ========================================================= */

    .checkout-field-label {
        display: block;

        margin-bottom: .45rem;

        color: #35312d;

        font-size: 9px;
        font-weight: 600;

        letter-spacing: .12em;

        text-transform: uppercase;
    }


    .checkout-field {
        width: 100%;
        min-height: 47px;

        padding:
            .8rem
            .9rem;

        border:
            1px solid
            #d6cfc6;

        background: #fff;

        color: #171512;

        font-size: 13px;

        outline: none;

        transition:
            border-color .25s ease,
            box-shadow .25s ease,
            background .25s ease;
    }


    .checkout-field::placeholder {
        color: #aaa39b;
    }


    .checkout-field:focus {
        border-color: #173d32;

        box-shadow:
            0
            0
            0
            2px
            rgba(23, 61, 50, .07);
    }


    textarea.checkout-field {
        min-height: 105px;

        resize: vertical;
    }


    /* =========================================================
       VALIDATION
    ========================================================= */

    .checkout-field-error {
        display: block;

        margin-top: .4rem;

        color: #a43f32;

        font-size: 10px;

        line-height: 1.4;
    }


    .checkout-error-box {
        margin-bottom: 1.5rem;

        padding:
            1rem
            1.1rem;

        border-left:
            3px solid
            #a43f32;

        background: #fff5f3;

        color: #743128;

        font-size: 11px;

        line-height: 1.6;
    }


    /* =========================================================
       PAYMENT
    ========================================================= */

    .checkout-payment-card {
        position: relative;

        display: flex;

        align-items: flex-start;

        gap: .9rem;

        padding:
            1rem;

        border:
            1px solid
            #173d32;

        background: #f5f8f6;
    }


    .checkout-payment-radio {
        margin-top: .15rem;

        accent-color: #173d32;
    }


    .checkout-payment-icon {
        display: grid;

        place-items: center;

        width: 38px;
        height: 38px;

        flex: 0 0 38px;

        border:
            1px solid
            #ced9d2;

        background: #fff;

        color: #173d32;
    }


    .checkout-payment-content {
        min-width: 0;
    }


    .checkout-payment-title {
        display: block;

        color: #171512;

        font-size: 13px;

        font-weight: 600;
    }


    .checkout-payment-text {
        display: block;

        margin-top: .25rem;

        color: #77716a;

        font-size: 11px;

        line-height: 1.55;
    }


    .checkout-payment-badge {
        display: inline-flex;

        align-items: center;

        gap: .35rem;

        margin-top: .55rem;

        color: #2c6b4b;

        font-size: 9px;

        font-weight: 600;

        letter-spacing: .08em;

        text-transform: uppercase;
    }


    /* =========================================================
       SUMMARY
    ========================================================= */

    .checkout-summary {
        position: sticky;

        top: 125px;

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


    .checkout-summary-eyebrow {
        color: #8d867e;

        font-size: 9px;

        font-weight: 600;

        letter-spacing: .18em;

        text-transform: uppercase;
    }


    .checkout-summary-title {
        margin-top: .45rem;

        font-family:
            'Cormorant Garamond',
            Georgia,
            serif;

        font-size: 2.4rem;

        font-weight: 400;

        line-height: 1;
    }


    /* =========================================================
       ORDER ITEMS
    ========================================================= */

    .checkout-order-items {
        display: grid;

        gap: 1rem;

        margin-top: 1.7rem;
    }


    .checkout-order-item {
        display: grid;

        grid-template-columns:
            68px
            minmax(0, 1fr)
            auto;

        gap: .8rem;

        align-items: start;
    }


    .checkout-order-image {
        width: 68px;
        height: 84px;

        overflow: hidden;

        background: #eee8df;
    }


    .checkout-order-image img {
        display: block;

        width: 100%;
        height: 100%;

        object-fit: cover;
        object-position: center;
    }


    .checkout-order-content {
        min-width: 0;
    }


    .checkout-order-name {
        color: #27231f;

        font-size: 12px;

        line-height: 1.4;
    }


    .checkout-order-options {
        display: block;

        margin-top: .3rem;

        color: #8a837b;

        font-size: 9px;

        line-height: 1.4;
    }


    .checkout-order-price {
        font-size: 11px;

        font-weight: 600;

        white-space: nowrap;
    }


    /* =========================================================
       TOTALS
    ========================================================= */

    .checkout-totals {
        display: grid;

        gap: .8rem;

        margin-top: 1.6rem;

        padding-top: 1.3rem;

        border-top:
            1px solid
            #d8d1c8;
    }


    .checkout-total-row {
        display: flex;

        align-items: center;
        justify-content: space-between;

        gap: 1rem;

        color: #686159;

        font-size: 11px;
    }


    .checkout-total-row strong {
        color: #171512;

        font-weight: 600;
    }


    .checkout-total-final {
        margin-top: .4rem;

        padding-top: 1rem;

        border-top:
            1px solid
            #d8d1c8;

        color: #171512;

        font-size: 15px;
    }


    .checkout-total-final strong {
        font-size: 17px;
    }


    /* =========================================================
       PLACE ORDER
    ========================================================= */

    .checkout-submit {
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

        border:
            1px solid
            #173d32;

        background: #173d32;

        color: #fff;

        font-size: 9px;

        font-weight: 600;

        letter-spacing: .14em;

        text-transform: uppercase;

        cursor: pointer;

        transition:
            background .25s ease,
            border-color .25s ease,
            transform .25s ease;
    }


    .checkout-submit:hover {
        background: #c4622f;

        border-color: #c4622f;

        transform:
            translateY(-1px);
    }


    .checkout-submit i {
        transition:
            transform .25s ease;
    }


    .checkout-submit:hover i {
        transform:
            translateX(4px);
    }


    /* =========================================================
       SECURITY
    ========================================================= */

    .checkout-security {
        display: flex;

        align-items: center;
        justify-content: center;

        gap: .45rem;

        margin-top: .9rem;

        color: #827b74;

        font-size: 9px;

        line-height: 1.5;

        text-align: center;
    }


    .checkout-security i {
        color: #173d32;
    }


    /* =========================================================
       TRUST ROWS
    ========================================================= */

    .checkout-trust {
        display: grid;

        gap: .65rem;

        margin-top: 1.4rem;

        padding-top: 1.2rem;

        border-top:
            1px solid
            #ddd6cd;
    }


    .checkout-trust-item {
        display: flex;

        align-items: center;

        gap: .6rem;

        color: #77716a;

        font-size: 9px;

        line-height: 1.45;
    }


    .checkout-trust-item i {
        width: 16px;

        color: #173d32;

        text-align: center;
    }


    /* =========================================================
       TABLET
    ========================================================= */

    @media (max-width: 1000px) {

        .checkout-layout-premium {
            grid-template-columns:
                minmax(0, 1fr)
                320px;

            gap: 2rem;
        }


        .checkout-form-grid {
            grid-template-columns: 1fr;
        }


        .checkout-field-full {
            grid-column: auto;
        }

    }


    /* =========================================================
       MOBILE
    ========================================================= */

    @media (max-width: 760px) {

        .checkout-page {
            padding:
                2rem
                1rem
                4rem;
        }


        .checkout-page-header {
            margin-bottom: 1.4rem;

            padding-bottom: 1.4rem;
        }


        .checkout-title {
            font-size: 3rem;
        }


        .checkout-subtitle {
            font-size: 11px;
        }


        .checkout-layout-premium {
            display: flex;

            flex-direction: column;

            gap: 1.3rem;
        }


        .checkout-form-column {
            width: 100%;
        }


        .checkout-section {
            width: 100%;

            padding: 1.1rem;
        }


        .checkout-section + .checkout-section {
            margin-top: 1rem;
        }


        .checkout-form-grid {
            grid-template-columns: 1fr;

            gap: .9rem;
        }


        .checkout-field-full {
            grid-column: auto;
        }


        .checkout-field {
            min-height: 45px;

            font-size: 12px;
        }


        .checkout-summary {
            position: static;

            width: 100%;

            margin: 0;

            padding: 1.1rem;
        }


        .checkout-summary-title {
            font-size: 2rem;
        }


        .checkout-order-item {
            grid-template-columns:
                58px
                minmax(0, 1fr)
                auto;

            gap: .65rem;
        }


        .checkout-order-image {
            width: 58px;
            height: 72px;
        }


        .checkout-order-name {
            font-size: 11px;
        }


        .checkout-order-price {
            font-size: 10px;
        }


        .checkout-submit {
            min-height: 48px;
        }

    }


    /* =========================================================
       SMALL MOBILE
    ========================================================= */

    @media (max-width: 390px) {

        .checkout-page {
            padding-left: .8rem;
            padding-right: .8rem;
        }


        .checkout-section,
        .checkout-summary {
            padding: 1rem;
        }


        .checkout-order-item {
            grid-template-columns:
                52px
                minmax(0, 1fr);

            gap: .6rem;
        }


        .checkout-order-image {
            width: 52px;
            height: 66px;
        }


        .checkout-order-price {
            grid-column: 2;

            margin-top: -.25rem;
        }

    }

</style>



<div class="checkout-page">


    {{-- ========================================================
        PAGE HEADER
    ======================================================== --}}

    <header class="checkout-page-header">


        <p class="checkout-eyebrow">

            Secure checkout

        </p>


        <h1 class="checkout-title">

            Checkout

        </h1>


        <p class="checkout-subtitle">

            Complete your delivery information and place your
            AATCHALA order securely.

        </p>


    </header>



    {{-- ========================================================
        VALIDATION SUMMARY
    ======================================================== --}}

    @if($errors->any())

        <div
            class="checkout-error-box"
            role="alert"
        >

            <strong>
                Please check the information below.
            </strong>


            <div class="mt-1">

                {{ $errors->first() }}

            </div>

        </div>

    @endif



    {{-- ========================================================
        CHECKOUT FORM
    ======================================================== --}}

    <form
        method="POST"
        action="{{ route('checkout.store') }}"
        class="checkout-layout-premium"
    >

        @csrf


        {{-- IMPORTANT:
             Only COD is currently enabled.
        --}}

        <input
            type="hidden"
            name="payment_method"
            value="cod"
        >



        {{-- ====================================================
            LEFT COLUMN
        ==================================================== --}}

        <div class="checkout-form-column">


            {{-- =================================================
                DELIVERY DETAILS
            ================================================== --}}

            <section class="checkout-section">


                <div class="checkout-section-header">


                    <span class="checkout-section-number">

                        1

                    </span>


                    <h2 class="checkout-section-title">

                        Delivery details

                    </h2>


                    <p class="checkout-section-description">

                        Please enter the information required
                        to deliver your order.

                    </p>


                </div>



                <div class="checkout-form-grid">


                    {{-- FULL NAME --}}

                    <label>


                        <span class="checkout-field-label">

                            Full name *

                        </span>


                        <input
                            class="checkout-field"
                            type="text"
                            name="name"
                            required
                            autocomplete="name"
                            value="{{ old('name', auth()->user()?->name) }}"
                            placeholder="Your full name"
                        >


                        @error('name')

                            <span class="checkout-field-error">

                                {{ $message }}

                            </span>

                        @enderror


                    </label>



                    {{-- EMAIL --}}

                    <label>


                        <span class="checkout-field-label">

                            Email *

                        </span>


                        <input
                            class="checkout-field"
                            type="email"
                            name="email"
                            required
                            autocomplete="email"
                            value="{{ old('email', auth()->user()?->email) }}"
                            placeholder="you@example.com"
                        >


                        @error('email')

                            <span class="checkout-field-error">

                                {{ $message }}

                            </span>

                        @enderror


                    </label>



                    {{-- PHONE --}}

                    <label>


                        <span class="checkout-field-label">

                            Phone *

                        </span>


                        <input
                            class="checkout-field"
                            type="tel"
                            name="phone"
                            required
                            autocomplete="tel"
                            inputmode="tel"
                            value="{{ old('phone', auth()->user()?->phone) }}"
                            placeholder="01XXXXXXXXX"
                        >


                        @error('phone')

                            <span class="checkout-field-error">

                                {{ $message }}

                            </span>

                        @enderror


                    </label>



                    {{-- DISTRICT --}}

                    <label>


                        <span class="checkout-field-label">

                            District *

                        </span>


                        <input
                            class="checkout-field"
                            type="text"
                            name="district"
                            required
                            value="{{ old('district') }}"
                            placeholder="e.g. Dhaka"
                        >


                        @error('district')

                            <span class="checkout-field-error">

                                {{ $message }}

                            </span>

                        @enderror


                    </label>



                    {{-- ADDRESS --}}

                    <label class="checkout-field-full">


                        <span class="checkout-field-label">

                            Address *

                        </span>


                        <input
                            class="checkout-field"
                            type="text"
                            name="address_line_1"
                            required
                            autocomplete="street-address"
                            value="{{ old('address_line_1') }}"
                            placeholder="House, road, village or street address"
                        >


                        @error('address_line_1')

                            <span class="checkout-field-error">

                                {{ $message }}

                            </span>

                        @enderror


                    </label>



                    {{-- AREA --}}

                    <label>


                        <span class="checkout-field-label">

                            Area / Thana

                        </span>


                        <input
                            class="checkout-field"
                            type="text"
                            name="area"
                            value="{{ old('area') }}"
                            placeholder="Area or thana"
                        >


                        @error('area')

                            <span class="checkout-field-error">

                                {{ $message }}

                            </span>

                        @enderror


                    </label>



                    {{-- CITY --}}

                    <label>


                        <span class="checkout-field-label">

                            City *

                        </span>


                        <input
                            class="checkout-field"
                            type="text"
                            name="city"
                            required
                            autocomplete="address-level2"
                            value="{{ old('city', 'Dhaka') }}"
                            placeholder="City"
                        >


                        @error('city')

                            <span class="checkout-field-error">

                                {{ $message }}

                            </span>

                        @enderror


                    </label>



                    {{-- POSTAL CODE --}}

                    <label>


                        <span class="checkout-field-label">

                            Postal code

                        </span>


                        <input
                            class="checkout-field"
                            type="text"
                            name="postal_code"
                            autocomplete="postal-code"
                            inputmode="numeric"
                            value="{{ old('postal_code') }}"
                            placeholder="Postal code"
                        >


                        @error('postal_code')

                            <span class="checkout-field-error">

                                {{ $message }}

                            </span>

                        @enderror


                    </label>



                    {{-- ORDER NOTE --}}

                    <label class="checkout-field-full">


                        <span class="checkout-field-label">

                            Order note

                        </span>


                        <textarea
                            class="checkout-field"
                            name="customer_note"
                            rows="4"
                            placeholder="Delivery instructions or anything we should know"
                        >{{ old('customer_note') }}</textarea>


                        @error('customer_note')

                            <span class="checkout-field-error">

                                {{ $message }}

                            </span>

                        @enderror


                    </label>


                </div>


            </section>



            {{-- =================================================
                PAYMENT
            ================================================== --}}

            <section class="checkout-section">


                <div class="checkout-section-header">


                    <span class="checkout-section-number">

                        2

                    </span>


                    <h2 class="checkout-section-title">

                        Payment

                    </h2>


                    <p class="checkout-section-description">

                        Cash on delivery is currently available
                        for AATCHALA orders.

                    </p>


                </div>



                {{-- ONLY CASH ON DELIVERY --}}

                <div class="checkout-payment-card">


                    <input
                        class="checkout-payment-radio"
                        type="radio"
                        checked
                        disabled
                        aria-label="Cash on delivery selected"
                    >



                    <span class="checkout-payment-icon">

                        <i class="fa-solid fa-money-bill-wave"></i>

                    </span>



                    <div class="checkout-payment-content">


                        <strong class="checkout-payment-title">

                            Cash on delivery

                        </strong>


                        <span class="checkout-payment-text">

                            Pay when your order arrives at your
                            delivery address.

                        </span>


                        <span class="checkout-payment-badge">

                            <i class="fa-solid fa-circle-check"></i>

                            Selected

                        </span>


                    </div>


                </div>


            </section>


        </div>



        {{-- ====================================================
            ORDER SUMMARY
        ==================================================== --}}

        <aside class="checkout-summary">


            <p class="checkout-summary-eyebrow">

                Your selection

            </p>


            <h2 class="checkout-summary-title">

                Your order

            </h2>



            {{-- =================================================
                ITEMS
            ================================================== --}}

            <div class="checkout-order-items">


                @foreach($items as $item)


                    @php

                        $primaryImage =
                            optional(
                                $item->product->primaryImage
                            )->path
                            ?: 'images/placeholder.webp';


                        $options =
                            collect([
                                $item->color,
                                $item->size,
                            ])
                            ->filter()
                            ->implode(' · ');

                    @endphp



                    <div class="checkout-order-item">


                        <a
                            class="checkout-order-image"
                            href="{{ route('products.show', $item->product) }}"
                        >

                            <img
                                src="{{ asset($primaryImage) }}"
                                alt="{{ $item->product->name }}"
                                loading="lazy"
                            >

                        </a>



                        <div class="checkout-order-content">


                            <p class="checkout-order-name">

                                {{ $item->product->name }}

                                × {{ $item->quantity }}

                            </p>



                            @if($options)

                                <small class="checkout-order-options">

                                    {{ $options }}

                                </small>

                            @endif


                        </div>



                        <strong class="checkout-order-price">

                            ৳{{ number_format((float) $item->line_total) }}

                        </strong>


                    </div>


                @endforeach


            </div>



            {{-- =================================================
                TOTALS
            ================================================== --}}

            <div class="checkout-totals">


                <p class="checkout-total-row">


                    <span>

                        Subtotal

                    </span>


                    <strong>

                        ৳{{ number_format((float) $totals['subtotal']) }}

                    </strong>


                </p>



                {{-- DISCOUNT --}}

                @if(($totals['discount'] ?? 0) > 0)

                    <p class="checkout-total-row">


                        <span>

                            Discount

                        </span>


                        <strong
                            style="color:#2f7354;"
                        >

                            −৳{{ number_format((float) $totals['discount']) }}

                        </strong>


                    </p>

                @endif



                {{-- DELIVERY --}}

                <p class="checkout-total-row">


                    <span>

                        Delivery

                    </span>


                    <strong>

                        @if(($totals['shipping'] ?? 0) > 0)

                            ৳{{ number_format((float) $totals['shipping']) }}

                        @else

                            Complimentary

                        @endif

                    </strong>


                </p>



                {{-- TAX --}}

                @if(($totals['tax'] ?? 0) > 0)

                    <p class="checkout-total-row">


                        <span>

                            Tax

                        </span>


                        <strong>

                            ৳{{ number_format((float) $totals['tax']) }}

                        </strong>


                    </p>

                @endif



                {{-- FINAL TOTAL --}}

                <p
                    class="
                        checkout-total-row
                        checkout-total-final
                    "
                >


                    <strong>

                        Total

                    </strong>


                    <strong>

                        ৳{{ number_format((float) $totals['total']) }}

                    </strong>


                </p>


            </div>



            {{-- =================================================
                PLACE ORDER
            ================================================== --}}

            <button
                class="checkout-submit"
                type="submit"
            >

                Place order securely

                <i class="fa-solid fa-arrow-right-long"></i>

            </button>



            <p class="checkout-security">

                <i class="fa-solid fa-lock"></i>

                Your details are encrypted in transit.

            </p>



            {{-- =================================================
                TRUST
            ================================================== --}}

            <div class="checkout-trust">


                <p class="checkout-trust-item">

                    <i class="fa-solid fa-money-bill-wave"></i>

                    Pay only when your order arrives

                </p>


                <p class="checkout-trust-item">

                    <i class="fa-solid fa-box"></i>

                    Carefully packed for delivery

                </p>


                <p class="checkout-trust-item">

                    <i class="fa-solid fa-shield-halved"></i>

                    Secure order information

                </p>


            </div>


        </aside>


    </form>


</div>


@endsection