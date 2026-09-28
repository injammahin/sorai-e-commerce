@extends('layouts.store')

@section('title', 'My Account | AATCHALA')


@push('head')

<style>

    /* =========================================================
       ACCOUNT PAGE
    ========================================================= */

    .account-page {
        width: 100%;
        max-width: 1380px;

        margin: 0 auto;

        padding:
            clamp(2rem, 5vw, 4.5rem)
            clamp(1.25rem, 4vw, 4rem)
            clamp(4rem, 7vw, 7rem);
    }


    /* =========================================================
       HEADER
    ========================================================= */

    .account-header {
        display: flex;

        align-items: flex-end;
        justify-content: space-between;

        gap: 1.5rem;

        margin-bottom:
            clamp(2rem, 4vw, 3.5rem);

        padding-bottom:
            clamp(1.5rem, 3vw, 2rem);

        border-bottom: 1px solid #ded7cf;
    }


    .account-eyebrow {
        margin-bottom: .45rem;

        color: #8d867e;

        font-size: 9px;

        font-weight: 600;

        letter-spacing: .2em;

        text-transform: uppercase;
    }


    .account-title {
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


    .account-signout {
        min-height: 45px;

        display: inline-flex;

        align-items: center;
        justify-content: center;

        gap: .5rem;

        padding:
            0
            1.35rem;

        border: 1px solid #171512;

        background: transparent;

        color: #171512;

        font-size: 9px;

        font-weight: 600;

        letter-spacing: .14em;

        text-transform: uppercase;

        transition:
            background .25s ease,
            color .25s ease,
            transform .25s ease;
    }


    .account-signout:hover {
        background: #171512;

        color: #fff;

        transform: translateY(-1px);
    }


    /* =========================================================
       MAIN GRID
    ========================================================= */

    .account-layout {
        display: grid;

        grid-template-columns:
            minmax(300px, 360px)
            minmax(0, 1fr);

        gap:
            clamp(2rem, 5vw, 5rem);

        align-items: start;
    }


    .account-sidebar {
        display: grid;

        gap: 1.25rem;
    }


    /* =========================================================
       CARD
    ========================================================= */

    .account-card {
        padding:
            clamp(
                1.35rem,
                3vw,
                2rem
            );

        border: 1px solid #ddd6cd;

        background: #faf8f5;
    }


    .account-card-title {
        margin-bottom: 1.4rem;

        font-family:
            'Cormorant Garamond',
            Georgia,
            serif;

        font-size: 2.2rem;

        font-weight: 400;

        line-height: 1;
    }


    /* =========================================================
       FORM FIELD
    ========================================================= */

    .account-field-group + .account-field-group {
        margin-top: 1rem;
    }


    .account-label {
        display: block;

        margin-bottom: .45rem;

        color: #3e3934;

        font-size: 9px;

        font-weight: 600;

        letter-spacing: .13em;

        text-transform: uppercase;
    }


    .account-field {
        width: 100%;
        min-height: 46px;

        padding:
            .75rem
            .85rem;

        border: 1px solid #d6cfc6;

        background: #fff;

        color: #171512;

        font-size: 13px;

        outline: none;

        transition:
            border-color .25s ease,
            box-shadow .25s ease;
    }


    .account-field:focus {
        border-color: #173d32;

        box-shadow:
            0
            0
            0
            2px
            rgba(23, 61, 50, .07);
    }


    .account-email {
        margin-top: 1rem;

        color: #77716a;

        font-size: 10px;

        line-height: 1.5;
    }


    /* =========================================================
       PASSWORD INPUT
    ========================================================= */

    .password-field-wrap {
        position: relative;
    }


    .password-field-wrap
    .account-field {
        padding-right: 46px;
    }


    .password-toggle {
        position: absolute;

        top: 50%;
        right: 0;

        width: 44px;
        height: 44px;

        display: grid;

        place-items: center;

        padding: 0;

        border: 0;

        background: transparent;

        color: #665f58;

        cursor: pointer;

        transform: translateY(-50%);

        transition:
            color .2s ease,
            background .2s ease;
    }


    .password-toggle:hover {
        color: #c4622f;
    }


    .password-toggle:focus-visible {
        outline: 1px solid #173d32;

        outline-offset: -4px;
    }


    .password-toggle i {
        font-size: 13px;

        pointer-events: none;
    }


    /* =========================================================
       BUTTONS
    ========================================================= */

    .account-primary-button,
    .account-secondary-button {
        min-height: 44px;

        display: inline-flex;

        align-items: center;
        justify-content: center;

        gap: .5rem;

        margin-top: 1.25rem;

        padding:
            0
            1.2rem;

        font-size: 9px;

        font-weight: 600;

        letter-spacing: .12em;

        text-transform: uppercase;

        transition:
            background .25s ease,
            color .25s ease,
            border-color .25s ease,
            transform .25s ease;
    }


    .account-primary-button {
        border: 1px solid #173d32;

        background: #173d32;

        color: #fff;
    }


    .account-primary-button:hover {
        border-color: #c4622f;

        background: #c4622f;

        transform: translateY(-1px);
    }


    .account-secondary-button {
        border: 1px solid #171512;

        background: transparent;

        color: #171512;
    }


    .account-secondary-button:hover {
        background: #171512;

        color: #fff;

        transform: translateY(-1px);
    }


    /* =========================================================
       VALIDATION
    ========================================================= */

    .account-error {
        display: block;

        margin-top: .4rem;

        color: #a43d32;

        font-size: 10px;

        line-height: 1.4;
    }


    /* =========================================================
       ORDERS
    ========================================================= */

    .account-orders-title {
        margin-bottom: 1.2rem;

        font-family:
            'Cormorant Garamond',
            Georgia,
            serif;

        font-size: 2.4rem;

        font-weight: 400;

        line-height: 1;
    }


    .account-orders {
        display: grid;

        gap: .75rem;
    }


    .account-order-card {
        display: flex;

        align-items: center;
        justify-content: space-between;

        gap: 1rem;

        padding: 1.25rem;

        border: 1px solid #ddd6cd;

        background: #faf8f5;

        transition:
            border-color .25s ease,
            transform .25s ease,
            box-shadow .25s ease;
    }


    .account-order-card:hover {
        border-color: #bfb6ac;

        transform: translateY(-2px);

        box-shadow:
            0
            12px
            28px
            rgba(0, 0, 0, .05);
    }


    .account-order-number {
        font-size: 13px;

        font-weight: 600;
    }


    .account-order-meta {
        margin-top: .3rem;

        color: #7e776f;

        font-size: 10px;

        line-height: 1.4;
    }


    .account-order-right {
        text-align: right;
    }


    .account-order-price {
        font-size: 13px;

        font-weight: 600;
    }


    .account-order-status {
        margin-top: .3rem;

        color: #625c55;

        font-size: 10px;

        text-transform: capitalize;
    }


    .account-empty {
        padding:
            2.5rem
            1.5rem;

        border: 1px solid #ddd6cd;

        background: #faf8f5;

        color: #77716a;

        text-align: center;
    }


    /* =========================================================
       TABLET
    ========================================================= */

    @media (max-width: 1024px) {

        .account-layout {
            grid-template-columns:
                minmax(270px, 320px)
                minmax(0, 1fr);

            gap: 2rem;
        }

    }


    /* =========================================================
       MOBILE + TABLET PORTRAIT
    ========================================================= */

    @media (max-width: 768px) {

        .account-page {
            padding:
                2rem
                1rem
                4rem;
        }


        .account-header {
            align-items: center;

            margin-bottom: 1.5rem;

            padding-bottom: 1.5rem;
        }


        .account-title {
            font-size: 2.8rem;
        }


        .account-signout {
            min-height: 40px;

            padding:
                0
                .95rem;

            font-size: 8px;
        }


        .account-layout {
            display: flex;

            flex-direction: column;

            gap: 1.5rem;
        }


        .account-sidebar,
        .account-orders-column {
            width: 100%;
        }


        .account-card {
            width: 100%;

            padding: 1.15rem;
        }


        .account-card-title,
        .account-orders-title {
            font-size: 2rem;
        }


        .account-field {
            min-height: 44px;

            font-size: 12px;
        }


        .account-primary-button,
        .account-secondary-button {
            width: 100%;
        }


        .account-order-card {
            padding: 1rem;
        }

    }


    /* =========================================================
       SMALL MOBILE
    ========================================================= */

    @media (max-width: 480px) {

        .account-page {
            padding:
                1.5rem
                .8rem
                3.5rem;
        }


        .account-header {
            align-items: flex-start;

            gap: 1rem;
        }


        .account-title {
            font-size: 2.45rem;
        }


        .account-signout {
            min-height: 38px;

            padding:
                0
                .8rem;

            white-space: nowrap;
        }


        .account-card {
            padding: 1rem;
        }


        .account-order-card {
            align-items: flex-start;

            gap: .75rem;
        }


        .account-order-right {
            min-width: 90px;
        }

    }


</style>

@endpush



@section('content')


<div class="account-page">


    {{-- ========================================================
        PAGE HEADER
    ======================================================== --}}

    <header class="account-header">


        <div>


            <p class="account-eyebrow">

                Welcome back

            </p>


            <h1 class="account-title">

                {{ auth()->user()->name }}

            </h1>


        </div>



        <form
            method="POST"
            action="{{ route('logout') }}"
        >

            @csrf


            <button
                type="submit"
                class="account-signout"
            >

                <i class="fa-solid fa-arrow-right-from-bracket"></i>

                Sign out

            </button>


        </form>


    </header>



    {{-- ========================================================
        MAIN
    ======================================================== --}}

    <div class="account-layout">


        {{-- ====================================================
            LEFT COLUMN
        ==================================================== --}}

        <aside class="account-sidebar">


            {{-- =================================================
                PROFILE
            ================================================== --}}

            <form
                class="account-card"
                action="{{ route('account.update') }}"
                method="POST"
            >

                @csrf
                @method('PATCH')


                <h2 class="account-card-title">

                    Profile

                </h2>



                {{-- NAME --}}

                <div class="account-field-group">


                    <label
                        class="account-label"
                        for="account-name"
                    >

                        Name

                    </label>


                    <input
                        id="account-name"
                        class="account-field"
                        type="text"
                        name="name"
                        value="{{ old('name', auth()->user()->name) }}"
                        autocomplete="name"
                        required
                    >


                    @error('name')

                        <span class="account-error">

                            {{ $message }}

                        </span>

                    @enderror


                </div>



                {{-- PHONE --}}

                <div class="account-field-group">


                    <label
                        class="account-label"
                        for="account-phone"
                    >

                        Phone

                    </label>


                    <input
                        id="account-phone"
                        class="account-field"
                        type="tel"
                        name="phone"
                        value="{{ old('phone', auth()->user()->phone) }}"
                        autocomplete="tel"
                    >


                    @error('phone')

                        <span class="account-error">

                            {{ $message }}

                        </span>

                    @enderror


                </div>



                {{-- EMAIL --}}

                <p class="account-email">

                    <i class="fa-regular fa-envelope mr-1"></i>

                    {{ auth()->user()->email }}

                </p>



                <button
                    type="submit"
                    class="account-primary-button"
                >

                    <i class="fa-solid fa-floppy-disk"></i>

                    Save profile

                </button>


            </form>



            {{-- =================================================
                PASSWORD
            ================================================== --}}

            <form
                class="account-card"
                action="{{ route('account.password') }}"
                method="POST"
            >

                @csrf
                @method('PUT')


                <h2 class="account-card-title">

                    Password

                </h2>



                {{-- CURRENT PASSWORD --}}

                <div class="account-field-group">


                    <label
                        class="account-label"
                        for="current-password"
                    >

                        Current password

                    </label>


                    <div class="password-field-wrap">


                        <input
                            id="current-password"
                            class="account-field"
                            type="password"
                            name="current_password"
                            autocomplete="current-password"
                            required
                        >


                        <button
                            type="button"
                            class="password-toggle"
                            data-password-toggle
                            data-password-target="current-password"
                            aria-label="Show current password"
                            aria-pressed="false"
                        >

                            <i class="fa-regular fa-eye"></i>

                        </button>


                    </div>


                    @error('current_password')

                        <span class="account-error">

                            {{ $message }}

                        </span>

                    @enderror


                </div>



                {{-- NEW PASSWORD --}}

                <div class="account-field-group">


                    <label
                        class="account-label"
                        for="new-password"
                    >

                        New password

                    </label>


                    <div class="password-field-wrap">


                        <input
                            id="new-password"
                            class="account-field"
                            type="password"
                            name="password"
                            autocomplete="new-password"
                            required
                        >


                        <button
                            type="button"
                            class="password-toggle"
                            data-password-toggle
                            data-password-target="new-password"
                            aria-label="Show new password"
                            aria-pressed="false"
                        >

                            <i class="fa-regular fa-eye"></i>

                        </button>


                    </div>


                    @error('password')

                        <span class="account-error">

                            {{ $message }}

                        </span>

                    @enderror


                </div>



                {{-- CONFIRM PASSWORD --}}

                <div class="account-field-group">


                    <label
                        class="account-label"
                        for="confirm-password"
                    >

                        Confirm new password

                    </label>


                    <div class="password-field-wrap">


                        <input
                            id="confirm-password"
                            class="account-field"
                            type="password"
                            name="password_confirmation"
                            autocomplete="new-password"
                            required
                        >


                        <button
                            type="button"
                            class="password-toggle"
                            data-password-toggle
                            data-password-target="confirm-password"
                            aria-label="Show password confirmation"
                            aria-pressed="false"
                        >

                            <i class="fa-regular fa-eye"></i>

                        </button>


                    </div>


                </div>



                <button
                    type="submit"
                    class="account-secondary-button"
                >

                    <i class="fa-solid fa-key"></i>

                    Change password

                </button>


            </form>


        </aside>



        {{-- ====================================================
            ORDERS
        ==================================================== --}}

        <section class="account-orders-column">


            <h2 class="account-orders-title">

                Orders

            </h2>



            <div class="account-orders">


                @forelse($orders as $order)


                    <a
                        class="account-order-card"
                        href="{{ route('order.thank-you', $order) }}"
                    >


                        <div>


                            <strong class="account-order-number">

                                {{ $order->order_number }}

                            </strong>


                            <p class="account-order-meta">

                                {{ $order->created_at->format('d M Y') }}

                                ·

                                {{ $order->items->sum('quantity') }}

                                {{
                                    $order->items->sum('quantity') === 1
                                        ? 'item'
                                        : 'items'
                                }}

                            </p>


                        </div>



                        <div class="account-order-right">


                            <strong class="account-order-price">

                                ৳{{ number_format((float) $order->total) }}

                            </strong>


                            <p class="account-order-status">

                                {{ ucfirst($order->status) }}

                            </p>


                        </div>


                    </a>


                @empty


                    <div class="account-empty">

                        <i class="fa-solid fa-bag-shopping mb-3"></i>

                        <p>

                            You have not placed an order yet.

                        </p>

                    </div>


                @endforelse


            </div>



            @if($orders->hasPages())

                <div class="mt-8">

                    {{ $orders->links() }}

                </div>

            @endif


        </section>


    </div>


</div>


@endsection



@push('scripts')

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {


        document
            .querySelectorAll(
                '[data-password-toggle]'
            )
            .forEach(
                function (button) {


                    button.addEventListener(
                        'click',
                        function () {


                            const targetId =
                                button.getAttribute(
                                    'data-password-target'
                                );


                            const input =
                                document.getElementById(
                                    targetId
                                );


                            if (!input) {
                                return;
                            }


                            const isPassword =
                                input.type ===
                                'password';


                            /*
                            |--------------------------------------------------------------------------
                            | SHOW / HIDE
                            |--------------------------------------------------------------------------
                            */

                            input.type =
                                isPassword
                                    ? 'text'
                                    : 'password';



                            /*
                            |--------------------------------------------------------------------------
                            | ICON
                            |--------------------------------------------------------------------------
                            */

                            const icon =
                                button.querySelector(
                                    'i'
                                );


                            if (icon) {

                                icon.classList.toggle(
                                    'fa-eye',
                                    !isPassword
                                );


                                icon.classList.toggle(
                                    'fa-eye-slash',
                                    isPassword
                                );

                            }



                            /*
                            |--------------------------------------------------------------------------
                            | ACCESSIBILITY
                            |--------------------------------------------------------------------------
                            */

                            button.setAttribute(
                                'aria-pressed',
                                isPassword
                                    ? 'true'
                                    : 'false'
                            );


                            button.setAttribute(
                                'aria-label',

                                isPassword
                                    ? 'Hide password'
                                    : 'Show password'
                            );


                        }
                    );


                }
            );


    }
);

</script>

@endpush