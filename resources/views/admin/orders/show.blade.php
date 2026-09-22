@extends('admin.layouts.app')

@section('title', $order->order_number)

@section(
    'page_title',
    'Order '.$order->order_number
)


@section('content')


@php

    $statusClasses = [

        'pending' =>
            'border-amber-200 bg-amber-50 text-amber-700',

        'confirmed' =>
            'border-blue-200 bg-blue-50 text-blue-700',

        'processing' =>
            'border-violet-200 bg-violet-50 text-violet-700',

        'shipped' =>
            'border-sky-200 bg-sky-50 text-sky-700',

        'delivered' =>
            'border-emerald-200 bg-emerald-50 text-emerald-700',

        'cancelled' =>
            'border-red-200 bg-red-50 text-red-700',

        'refunded' =>
            'border-slate-300 bg-slate-100 text-slate-700',

    ];


    $paymentClasses = [

        'unpaid' =>
            'border-red-200 bg-red-50 text-red-700',

        'pending' =>
            'border-amber-200 bg-amber-50 text-amber-700',

        'paid' =>
            'border-emerald-200 bg-emerald-50 text-emerald-700',

        'failed' =>
            'border-red-200 bg-red-50 text-red-700',

        'refunded' =>
            'border-slate-300 bg-slate-100 text-slate-700',

    ];


    $shipping =
        $order->shipping_address
        ??
        [];


    $billing =
        $order->billing_address
        ??
        [];

@endphp



<div class="space-y-5">


    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <div
        class="
            flex
            flex-col
            gap-4

            lg:flex-row
            lg:items-center
            lg:justify-between
        "
    >


        <div>


            <a
                href="{{ route('admin.orders.index') }}"
                class="
                    inline-flex
                    items-center
                    gap-2
                    text-xs
                    font-semibold
                    text-slate-500
                    transition

                    hover:text-copper-600
                "
            >

                <i class="fa-solid fa-arrow-left"></i>

                Back to orders

            </a>



            <div
                class="
                    mt-3
                    flex
                    flex-wrap
                    items-center
                    gap-2
                "
            >


                <h2
                    class="
                        text-2xl
                        font-semibold
                        text-slate-900
                    "
                >
                    {{ $order->order_number }}
                </h2>



                <span
                    class="
                        inline-flex
                        items-center
                        rounded-full
                        border
                        px-2.5
                        py-1
                        text-[10px]
                        font-bold
                        uppercase
                        tracking-wide

                        {{
                            $statusClasses[$order->status]
                            ??
                            'border-slate-200 bg-slate-50 text-slate-600'
                        }}
                    "
                >
                    {{ $order->status }}
                </span>



                <span
                    class="
                        inline-flex
                        items-center
                        rounded-full
                        border
                        px-2.5
                        py-1
                        text-[10px]
                        font-bold
                        uppercase
                        tracking-wide

                        {{
                            $paymentClasses[
                                $order->payment_status
                            ]
                            ??
                            'border-slate-200 bg-slate-50 text-slate-600'
                        }}
                    "
                >
                    {{ $order->payment_status }}
                </span>


            </div>



            <p
                class="
                    mt-1
                    text-sm
                    text-slate-500
                "
            >

                Placed

                {{
                    $order
                        ->created_at
                        ->format(
                            'd M Y, h:i A'
                        )
                }}

                ·

                {{
                    $order
                        ->items
                        ->sum(
                            'quantity'
                        )
                }}

                item(s)

            </p>


        </div>



        <div
            class="
                flex
                flex-wrap
                gap-2
            "
        >


            @if(data_get($shipping, 'email'))

                <a
                    href="mailto:{{ data_get($shipping, 'email') }}"
                    class="
                        admin-btn
                        admin-btn-light
                    "
                >

                    <i class="fa-solid fa-envelope"></i>

                    Email customer

                </a>

            @endif



            @if(data_get($shipping, 'phone'))

                <a
                    href="tel:{{ data_get($shipping, 'phone') }}"
                    class="
                        admin-btn
                        admin-btn-light
                    "
                >

                    <i class="fa-solid fa-phone"></i>

                    Call

                </a>

            @endif


        </div>


    </div>



    {{-- =========================================================
        SUMMARY CARDS
    ========================================================== --}}
    <div
        class="
            grid
            gap-4

            sm:grid-cols-2

            xl:grid-cols-4
        "
    >


        {{-- TOTAL --}}
        <div class="admin-card p-5">

            <div
                class="
                    flex
                    items-center
                    justify-between
                    gap-3
                "
            >

                <div>

                    <p
                        class="
                            text-[10px]
                            font-semibold
                            uppercase
                            tracking-[.12em]
                            text-slate-400
                        "
                    >
                        Order total
                    </p>


                    <p
                        class="
                            mt-2
                            text-xl
                            font-semibold
                            text-slate-900
                        "
                    >

                        ৳{{
                            number_format(
                                (float) $order->total,
                                0
                            )
                        }}

                    </p>

                </div>


                <span
                    class="
                        grid
                        h-10
                        w-10
                        place-items-center
                        rounded-xl
                        bg-emerald-50
                        text-forest
                    "
                >

                    <i
                        class="
                            fa-solid
                            fa-bangladeshi-taka-sign
                        "
                    ></i>

                </span>

            </div>

        </div>



        {{-- PAYMENT METHOD --}}
        <div class="admin-card p-5">

            <div
                class="
                    flex
                    items-center
                    justify-between
                    gap-3
                "
            >

                <div>

                    <p
                        class="
                            text-[10px]
                            font-semibold
                            uppercase
                            tracking-[.12em]
                            text-slate-400
                        "
                    >
                        Payment method
                    </p>


                    <p
                        class="
                            mt-2
                            text-base
                            font-semibold
                            text-slate-900
                        "
                    >

                        @if(
                            $order->payment_method
                            ===
                            'cod'
                        )

                            Cash on delivery

                        @elseif(
                            $order->payment_method
                            ===
                            'sslcommerz'
                        )

                            SSLCOMMERZ

                        @else

                            Bank / mobile payment

                        @endif

                    </p>

                </div>


                <span
                    class="
                        grid
                        h-10
                        w-10
                        place-items-center
                        rounded-xl
                        bg-blue-50
                        text-blue-600
                    "
                >

                    <i class="fa-solid fa-credit-card"></i>

                </span>

            </div>

        </div>



        {{-- CUSTOMER --}}
        <div class="admin-card p-5">

            <div
                class="
                    flex
                    items-center
                    justify-between
                    gap-3
                "
            >

                <div class="min-w-0">

                    <p
                        class="
                            text-[10px]
                            font-semibold
                            uppercase
                            tracking-[.12em]
                            text-slate-400
                        "
                    >
                        Customer
                    </p>


                    <p
                        class="
                            mt-2
                            truncate
                            text-base
                            font-semibold
                            text-slate-900
                        "
                    >

                        {{
                            $order->user?->name
                            ?:
                            data_get(
                                $shipping,
                                'name',
                                'Guest'
                            )
                        }}

                    </p>

                </div>


                <span
                    class="
                        grid
                        h-10
                        w-10
                        shrink-0
                        place-items-center
                        rounded-xl
                        bg-violet-50
                        text-violet-600
                    "
                >

                    <i class="fa-solid fa-user"></i>

                </span>

            </div>

        </div>



        {{-- TRANSACTION --}}
        <div class="admin-card p-5">

            <div
                class="
                    flex
                    items-center
                    justify-between
                    gap-3
                "
            >

                <div class="min-w-0">

                    <p
                        class="
                            text-[10px]
                            font-semibold
                            uppercase
                            tracking-[.12em]
                            text-slate-400
                        "
                    >
                        Transaction
                    </p>


                    <p
                        class="
                            mt-2
                            truncate
                            text-sm
                            font-semibold
                            text-slate-900
                        "
                    >

                        {{
                            $order->transaction_id
                            ?:
                            'Not available'
                        }}

                    </p>

                </div>


                <span
                    class="
                        grid
                        h-10
                        w-10
                        shrink-0
                        place-items-center
                        rounded-xl
                        bg-orange-50
                        text-copper-500
                    "
                >

                    <i class="fa-solid fa-receipt"></i>

                </span>

            </div>

        </div>


    </div>



    {{-- =========================================================
        MAIN GRID
    ========================================================== --}}
    <div
        class="
            grid
            gap-5

            xl:grid-cols-[minmax(0,1fr)_380px]
            xl:items-start
        "
    >


        {{-- =====================================================
            LEFT
        ====================================================== --}}
        <div
            class="
                grid
                min-w-0
                gap-5
            "
        >


            {{-- =================================================
                ORDER ITEMS
            ================================================== --}}
            <section class="admin-card overflow-hidden">


                <div
                    class="
                        flex
                        items-center
                        justify-between
                        gap-3
                        border-b
                        border-slate-100
                        px-5
                        py-4

                        sm:px-6
                    "
                >


                    <div>

                        <h2
                            class="
                                font-semibold
                                text-slate-900
                            "
                        >
                            Order items
                        </h2>


                        <p
                            class="
                                mt-0.5
                                text-xs
                                text-slate-500
                            "
                        >
                            Products included in this order.
                        </p>

                    </div>


                    <span
                        class="
                            rounded-full
                            bg-slate-100
                            px-3
                            py-1
                            text-[11px]
                            font-semibold
                            text-slate-600
                        "
                    >

                        {{
                            $order
                                ->items
                                ->sum(
                                    'quantity'
                                )
                        }}

                        item(s)

                    </span>


                </div>



                <div class="overflow-x-auto">


                    <table
                        class="
                            admin-table
                            min-w-[720px]
                        "
                    >


                        <thead>

                            <tr>

                                <th>Product</th>

                                <th>Unit price</th>

                                <th>Qty</th>

                                <th class="text-right">
                                    Line total
                                </th>

                            </tr>

                        </thead>



                        <tbody>


                            @foreach($order->items as $item)


                                <tr>


                                    <td>

                                        <div
                                            class="
                                                flex
                                                min-w-[300px]
                                                items-center
                                                gap-3
                                            "
                                        >


                                            @if($item->image)

                                                <img
                                                    class="
                                                        h-16
                                                        w-14
                                                        shrink-0
                                                        rounded-lg
                                                        border
                                                        border-slate-200
                                                        object-cover
                                                    "
                                                    src="{{ asset($item->image) }}"
                                                    alt="{{ $item->product_name }}"
                                                >

                                            @else

                                                <span
                                                    class="
                                                        grid
                                                        h-16
                                                        w-14
                                                        shrink-0
                                                        place-items-center
                                                        rounded-lg
                                                        border
                                                        border-slate-200
                                                        bg-slate-100
                                                        text-slate-400
                                                    "
                                                >

                                                    <i class="fa-solid fa-image"></i>

                                                </span>

                                            @endif



                                            <div class="min-w-0">


                                                <p
                                                    class="
                                                        font-semibold
                                                        text-slate-900
                                                    "
                                                >
                                                    {{ $item->product_name }}
                                                </p>


                                                <p
                                                    class="
                                                        mt-1
                                                        text-[11px]
                                                        text-slate-400
                                                    "
                                                >
                                                    SKU: {{ $item->sku }}
                                                </p>



                                                @if(
                                                    $item->color
                                                    ||
                                                    $item->size
                                                )

                                                    <div
                                                        class="
                                                            mt-2
                                                            flex
                                                            flex-wrap
                                                            gap-1.5
                                                        "
                                                    >


                                                        @if($item->color)

                                                            <span
                                                                class="
                                                                    rounded-full
                                                                    bg-slate-100
                                                                    px-2
                                                                    py-1
                                                                    text-[10px]
                                                                    text-slate-600
                                                                "
                                                            >
                                                                Color:
                                                                {{ $item->color }}
                                                            </span>

                                                        @endif



                                                        @if($item->size)

                                                            <span
                                                                class="
                                                                    rounded-full
                                                                    bg-slate-100
                                                                    px-2
                                                                    py-1
                                                                    text-[10px]
                                                                    text-slate-600
                                                                "
                                                            >
                                                                Size:
                                                                {{ $item->size }}
                                                            </span>

                                                        @endif


                                                    </div>

                                                @endif


                                            </div>


                                        </div>

                                    </td>



                                    <td>

                                        ৳{{
                                            number_format(
                                                (float)
                                                $item->unit_price,
                                                0
                                            )
                                        }}

                                    </td>



                                    <td>
                                        {{ $item->quantity }}
                                    </td>



                                    <td
                                        class="
                                            text-right
                                            font-semibold
                                            text-slate-900
                                        "
                                    >

                                        ৳{{
                                            number_format(
                                                (float)
                                                $item->total,
                                                0
                                            )
                                        }}

                                    </td>


                                </tr>


                            @endforeach


                        </tbody>


                    </table>


                </div>



                {{-- TOTALS --}}
                <div
                    class="
                        border-t
                        border-slate-100
                        bg-slate-50/50
                        p-5

                        sm:p-6
                    "
                >


                    <div
                        class="
                            ml-auto
                            grid
                            max-w-md
                            gap-2.5
                            text-sm
                        "
                    >


                        <div
                            class="
                                flex
                                items-center
                                justify-between
                                gap-4
                                text-slate-600
                            "
                        >

                            <span>
                                Subtotal
                            </span>


                            <span>

                                ৳{{
                                    number_format(
                                        (float)
                                        $order->subtotal,
                                        0
                                    )
                                }}

                            </span>

                        </div>



                        <div
                            class="
                                flex
                                items-center
                                justify-between
                                gap-4
                                text-slate-600
                            "
                        >

                            <span>

                                Discount

                                {{
                                    $order->coupon

                                        ? ' · '.$order->coupon->code

                                        : ''
                                }}

                            </span>


                            <span class="text-red-600">

                                −৳{{
                                    number_format(
                                        (float)
                                        $order->discount,
                                        0
                                    )
                                }}

                            </span>

                        </div>



                        <div
                            class="
                                flex
                                items-center
                                justify-between
                                gap-4
                                text-slate-600
                            "
                        >

                            <span>
                                Delivery
                            </span>


                            <span>

                                ৳{{
                                    number_format(
                                        (float)
                                        $order->shipping,
                                        0
                                    )
                                }}

                            </span>

                        </div>



                        @if((float) $order->tax > 0)

                            <div
                                class="
                                    flex
                                    items-center
                                    justify-between
                                    gap-4
                                    text-slate-600
                                "
                            >

                                <span>
                                    Tax
                                </span>


                                <span>

                                    ৳{{
                                        number_format(
                                            (float)
                                            $order->tax,
                                            0
                                        )
                                    }}

                                </span>

                            </div>

                        @endif



                        <div
                            class="
                                mt-2
                                flex
                                items-center
                                justify-between
                                gap-4
                                border-t
                                border-slate-200
                                pt-3
                                text-lg
                                text-slate-900
                            "
                        >

                            <strong>
                                Total
                            </strong>


                            <strong>

                                ৳{{
                                    number_format(
                                        (float)
                                        $order->total,
                                        0
                                    )
                                }}

                            </strong>

                        </div>


                    </div>


                </div>


            </section>



            {{-- =================================================
                CUSTOMER + DELIVERY
            ================================================== --}}
            <div
                class="
                    grid
                    gap-5

                    lg:grid-cols-2
                "
            >


                {{-- CUSTOMER --}}
                <section class="admin-card p-5 sm:p-6">


                    <div class="flex items-center gap-3">


                        <span
                            class="
                                grid
                                h-10
                                w-10
                                place-items-center
                                rounded-xl
                                bg-violet-50
                                text-violet-600
                            "
                        >

                            <i class="fa-solid fa-user"></i>

                        </span>


                        <div>

                            <h2
                                class="
                                    font-semibold
                                    text-slate-900
                                "
                            >
                                Customer details
                            </h2>


                            <p class="text-xs text-slate-500">
                                Account and contact information.
                            </p>

                        </div>


                    </div>



                    <dl
                        class="
                            mt-5
                            grid
                            gap-4
                            text-sm
                        "
                    >


                        <div>

                            <dt
                                class="
                                    text-[10px]
                                    font-semibold
                                    uppercase
                                    tracking-wider
                                    text-slate-400
                                "
                            >
                                Name
                            </dt>


                            <dd
                                class="
                                    mt-1
                                    font-medium
                                    text-slate-800
                                "
                            >

                                {{
                                    $order->user?->name
                                    ?:
                                    data_get(
                                        $shipping,
                                        'name',
                                        '—'
                                    )
                                }}

                            </dd>

                        </div>



                        <div>

                            <dt
                                class="
                                    text-[10px]
                                    font-semibold
                                    uppercase
                                    tracking-wider
                                    text-slate-400
                                "
                            >
                                Email
                            </dt>


                            <dd
                                class="
                                    mt-1
                                    break-all
                                    text-slate-700
                                "
                            >

                                {{
                                    data_get(
                                        $shipping,
                                        'email',
                                        '—'
                                    )
                                }}

                            </dd>

                        </div>



                        <div>

                            <dt
                                class="
                                    text-[10px]
                                    font-semibold
                                    uppercase
                                    tracking-wider
                                    text-slate-400
                                "
                            >
                                Phone
                            </dt>


                            <dd
                                class="
                                    mt-1
                                    text-slate-700
                                "
                            >

                                {{
                                    data_get(
                                        $shipping,
                                        'phone',
                                        '—'
                                    )
                                }}

                            </dd>

                        </div>



                        <div>

                            <dt
                                class="
                                    text-[10px]
                                    font-semibold
                                    uppercase
                                    tracking-wider
                                    text-slate-400
                                "
                            >
                                Customer type
                            </dt>


                            <dd
                                class="
                                    mt-1
                                    text-slate-700
                                "
                            >

                                {{
                                    $order->user

                                        ? 'Registered customer'

                                        : 'Guest checkout'
                                }}

                            </dd>

                        </div>


                    </dl>


                </section>



                {{-- DELIVERY --}}
                <section class="admin-card p-5 sm:p-6">


                    <div class="flex items-center gap-3">


                        <span
                            class="
                                grid
                                h-10
                                w-10
                                place-items-center
                                rounded-xl
                                bg-emerald-50
                                text-forest
                            "
                        >

                            <i class="fa-solid fa-location-dot"></i>

                        </span>


                        <div>

                            <h2
                                class="
                                    font-semibold
                                    text-slate-900
                                "
                            >
                                Delivery address
                            </h2>


                            <p class="text-xs text-slate-500">
                                Where this order should be delivered.
                            </p>

                        </div>


                    </div>



                    <address
                        class="
                            mt-5
                            not-italic
                            text-sm
                            leading-7
                            text-slate-700
                        "
                    >


                        <strong class="text-slate-900">

                            {{
                                data_get(
                                    $shipping,
                                    'name',
                                    '—'
                                )
                            }}

                        </strong>

                        <br>


                        {{
                            data_get(
                                $shipping,
                                'address_line_1',
                                '—'
                            )
                        }}


                        @if(
                            data_get(
                                $shipping,
                                'address_line_2'
                            )
                        )

                            <br>

                            {{
                                data_get(
                                    $shipping,
                                    'address_line_2'
                                )
                            }}

                        @endif


                        @if(
                            data_get(
                                $shipping,
                                'area'
                            )
                        )

                            <br>

                            {{
                                data_get(
                                    $shipping,
                                    'area'
                                )
                            }}

                        @endif


                        <br>


                        {{
                            data_get(
                                $shipping,
                                'city',
                                '—'
                            )
                        }},

                        {{
                            data_get(
                                $shipping,
                                'district',
                                '—'
                            )
                        }}


                        @if(
                            data_get(
                                $shipping,
                                'postal_code'
                            )
                        )

                            <br>

                            Postal code:

                            {{
                                data_get(
                                    $shipping,
                                    'postal_code'
                                )
                            }}

                        @endif


                    </address>


                </section>


            </div>



            {{-- =================================================
                BILLING + CUSTOMER NOTE
            ================================================== --}}
            <div
                class="
                    grid
                    gap-5

                    lg:grid-cols-2
                "
            >


                {{-- BILLING --}}
                <section class="admin-card p-5 sm:p-6">


                    <div class="flex items-center gap-3">


                        <span
                            class="
                                grid
                                h-10
                                w-10
                                place-items-center
                                rounded-xl
                                bg-blue-50
                                text-blue-600
                            "
                        >

                            <i class="fa-solid fa-file-invoice"></i>

                        </span>


                        <div>

                            <h2
                                class="
                                    font-semibold
                                    text-slate-900
                                "
                            >
                                Billing address
                            </h2>


                            <p class="text-xs text-slate-500">
                                Address stored for billing.
                            </p>

                        </div>


                    </div>



                    <address
                        class="
                            mt-5
                            not-italic
                            text-sm
                            leading-7
                            text-slate-700
                        "
                    >


                        <strong class="text-slate-900">

                            {{
                                data_get(
                                    $billing,
                                    'name',
                                    '—'
                                )
                            }}

                        </strong>

                        <br>


                        {{
                            data_get(
                                $billing,
                                'address_line_1',
                                '—'
                            )
                        }}


                        @if(
                            data_get(
                                $billing,
                                'address_line_2'
                            )
                        )

                            <br>

                            {{
                                data_get(
                                    $billing,
                                    'address_line_2'
                                )
                            }}

                        @endif


                        @if(
                            data_get(
                                $billing,
                                'area'
                            )
                        )

                            <br>

                            {{
                                data_get(
                                    $billing,
                                    'area'
                                )
                            }}

                        @endif


                        <br>


                        {{
                            data_get(
                                $billing,
                                'city',
                                '—'
                            )
                        }},

                        {{
                            data_get(
                                $billing,
                                'district',
                                '—'
                            )
                        }}


                        @if(
                            data_get(
                                $billing,
                                'postal_code'
                            )
                        )

                            <br>

                            Postal code:

                            {{
                                data_get(
                                    $billing,
                                    'postal_code'
                                )
                            }}

                        @endif


                    </address>


                </section>



                {{-- CUSTOMER NOTE --}}
                <section class="admin-card p-5 sm:p-6">


                    <div class="flex items-center gap-3">


                        <span
                            class="
                                grid
                                h-10
                                w-10
                                place-items-center
                                rounded-xl
                                bg-orange-50
                                text-copper-500
                            "
                        >

                            <i class="fa-solid fa-message"></i>

                        </span>


                        <div>

                            <h2
                                class="
                                    font-semibold
                                    text-slate-900
                                "
                            >
                                Customer note
                            </h2>


                            <p class="text-xs text-slate-500">
                                Instructions submitted during checkout.
                            </p>

                        </div>


                    </div>



                    <div
                        class="
                            mt-5
                            rounded-xl
                            border
                            border-slate-100
                            bg-slate-50
                            p-4
                            text-sm
                            leading-6
                            text-slate-700
                        "
                    >

                        {{
                            $order->customer_note
                            ?:
                            'No customer note was provided.'
                        }}

                    </div>


                </section>


            </div>



            {{-- =================================================
                STATUS HISTORY
            ================================================== --}}
            <section class="admin-card p-5 sm:p-6">


                <div class="flex items-center gap-3">


                    <span
                        class="
                            grid
                            h-10
                            w-10
                            place-items-center
                            rounded-xl
                            bg-slate-100
                            text-slate-600
                        "
                    >

                        <i
                            class="
                                fa-solid
                                fa-clock-rotate-left
                            "
                        ></i>

                    </span>


                    <div>

                        <h2
                            class="
                                font-semibold
                                text-slate-900
                            "
                        >
                            Status history
                        </h2>


                        <p class="text-xs text-slate-500">
                            A timeline of order status changes.
                        </p>

                    </div>


                </div>



                <div class="mt-6 grid gap-0">


                    @forelse($order->histories as $history)


                        <div
                            class="
                                relative
                                pb-6
                                pl-8

                                last:pb-0
                            "
                        >


                            <span
                                class="
                                    absolute
                                    left-[5px]
                                    top-1.5
                                    h-full
                                    w-px
                                    bg-slate-200

                                    last:hidden
                                "
                            ></span>


                            <span
                                class="
                                    absolute
                                    left-0
                                    top-1
                                    grid
                                    h-3
                                    w-3
                                    place-items-center
                                    rounded-full
                                    border-2
                                    border-white
                                    bg-copper-500
                                    shadow
                                "
                            ></span>



                            <div
                                class="
                                    flex
                                    flex-col
                                    gap-1

                                    sm:flex-row
                                    sm:items-start
                                    sm:justify-between
                                "
                            >


                                <div>

                                    <p
                                        class="
                                            font-semibold
                                            capitalize
                                            text-slate-800
                                        "
                                    >
                                        {{ $history->status }}
                                    </p>


                                    <p
                                        class="
                                            mt-0.5
                                            text-xs
                                            text-slate-400
                                        "
                                    >

                                        Changed by

                                        {{
                                            $history->user?->name
                                            ?:
                                            'System'
                                        }}

                                    </p>

                                </div>



                                <time
                                    class="
                                        text-xs
                                        text-slate-400
                                    "
                                >

                                    {{
                                        $history
                                            ->created_at
                                            ->format(
                                                'd M Y, h:i A'
                                            )
                                    }}

                                </time>


                            </div>



                            @if($history->note)

                                <p
                                    class="
                                        mt-2
                                        rounded-lg
                                        bg-slate-50
                                        px-3
                                        py-2
                                        text-sm
                                        text-slate-600
                                    "
                                >
                                    {{ $history->note }}
                                </p>

                            @endif


                        </div>


                    @empty


                        <p class="text-sm text-slate-500">
                            No status history is available yet.
                        </p>


                    @endforelse


                </div>


            </section>


        </div>



        {{-- =====================================================
            RIGHT SIDEBAR
        ====================================================== --}}
        <aside
            class="
                grid
                gap-5

                xl:sticky
                xl:top-[90px]
            "
        >


            {{-- =================================================
                UPDATE ORDER
            ================================================== --}}
            <form
                class="admin-card overflow-hidden"
                method="POST"
                action="{{
                    route(
                        'admin.orders.update',
                        $order
                    )
                }}"
            >

                @csrf

                @method('PUT')



                <div
                    class="
                        border-b
                        border-slate-100
                        px-5
                        py-4
                    "
                >


                    <div
                        class="
                            flex
                            items-center
                            justify-between
                            gap-3
                        "
                    >


                        <div>

                            <h2
                                class="
                                    font-semibold
                                    text-slate-900
                                "
                            >
                                Update order
                            </h2>


                            <p
                                class="
                                    mt-0.5
                                    text-xs
                                    text-slate-500
                                "
                            >
                                Change fulfilment and payment status.
                            </p>

                        </div>


                        <span
                            class="
                                grid
                                h-9
                                w-9
                                place-items-center
                                rounded-xl
                                bg-emerald-50
                                text-forest
                            "
                        >

                            <i
                                class="
                                    fa-solid
                                    fa-pen-to-square
                                "
                            ></i>

                        </span>


                    </div>


                </div>



                <div class="p-5">


                    @if($errors->any())

                        <div
                            class="
                                mb-4
                                rounded-lg
                                border
                                border-red-200
                                bg-red-50
                                px-4
                                py-3
                                text-xs
                                text-red-700
                            "
                        >
                            {{ $errors->first() }}
                        </div>

                    @endif



                    @if($order->stock_restored_at)

                        <div
                            class="
                                mb-4
                                rounded-lg
                                border
                                border-amber-200
                                bg-amber-50
                                px-4
                                py-3
                                text-xs
                                leading-5
                                text-amber-800
                            "
                        >

                            <i
                                class="
                                    fa-solid
                                    fa-triangle-exclamation
                                    mr-1
                                "
                            ></i>

                            Stock was restored on

                            {{
                                $order
                                    ->stock_restored_at
                                    ->format(
                                        'd M Y, h:i A'
                                    )
                            }}.

                            This order cannot be reopened to an
                            active fulfilment status.

                        </div>

                    @endif



                    {{-- ORDER STATUS --}}
                    <label>

                        <span class="admin-label">
                            Order status
                        </span>


                        <select
                            class="admin-input"
                            name="status"
                            required
                        >


                            @foreach(['pending','confirmed','processing','shipped','delivered','cancelled','refunded'] as $status)


                                <option
                                    value="{{ $status }}"

                                    @selected(
                                        old(
                                            'status',
                                            $order->status
                                        )
                                        ===
                                        $status
                                    )

                                    @disabled(
                                        $order->stock_restored_at
                                        &&
                                        ! in_array(
                                            $status,
                                            [
                                                'cancelled',
                                                'refunded'
                                            ],
                                            true
                                        )
                                    )
                                >

                                    {{ ucfirst($status) }}

                                </option>


                            @endforeach


                        </select>

                    </label>



                    {{-- PAYMENT STATUS --}}
                    <label
                        class="
                            mt-4
                            block
                        "
                    >

                        <span class="admin-label">
                            Payment status
                        </span>


                        <select
                            class="admin-input"
                            name="payment_status"
                            required
                        >


                            @foreach(['unpaid','pending','paid','failed','refunded'] as $paymentStatus)


                                <option
                                    value="{{ $paymentStatus }}"

                                    @selected(
                                        old(
                                            'payment_status',
                                            $order->payment_status
                                        )
                                        ===
                                        $paymentStatus
                                    )
                                >

                                    {{ ucfirst($paymentStatus) }}

                                </option>


                            @endforeach


                        </select>

                    </label>



                    {{-- INTERNAL NOTE --}}
                    <label
                        class="
                            mt-4
                            block
                        "
                    >

                        <span class="admin-label">
                            Internal admin note
                        </span>


                        <textarea
                            class="
                                admin-input
                                min-h-[120px]
                                resize-y
                            "
                            rows="5"
                            name="admin_note"
                            maxlength="3000"
                            placeholder="Add an internal note about this order..."
                        >{{ old('admin_note', $order->admin_note) }}</textarea>


                        <span
                            class="
                                mt-1
                                block
                                text-[11px]
                                text-slate-400
                            "
                        >
                            Visible to administrators only.
                        </span>

                    </label>



                    <button
                        type="submit"
                        class="
                            admin-btn
                            mt-5
                            w-full
                            !py-3
                        "
                    >

                        <i class="fa-solid fa-floppy-disk"></i>

                        Save order update

                    </button>


                </div>


            </form>



            {{-- =================================================
                PAYMENT DETAILS
            ================================================== --}}
            <section class="admin-card p-5">


                <div
                    class="
                        flex
                        items-center
                        justify-between
                        gap-3
                    "
                >


                    <h2
                        class="
                            font-semibold
                            text-slate-900
                        "
                    >
                        Payment details
                    </h2>


                    <span
                        class="
                            inline-flex
                            items-center
                            rounded-full
                            border
                            px-2.5
                            py-1
                            text-[10px]
                            font-bold
                            uppercase
                            tracking-wide

                            {{
                                $paymentClasses[
                                    $order->payment_status
                                ]
                                ??
                                'border-slate-200 bg-slate-50 text-slate-600'
                            }}
                        "
                    >

                        {{ $order->payment_status }}

                    </span>


                </div>



                <dl
                    class="
                        mt-4
                        grid
                        gap-4
                        text-sm
                    "
                >


                    <div>

                        <dt
                            class="
                                text-[10px]
                                font-semibold
                                uppercase
                                tracking-wider
                                text-slate-400
                            "
                        >
                            Method
                        </dt>


                        <dd
                            class="
                                mt-1
                                text-slate-700
                            "
                        >

                            @if(
                                $order->payment_method
                                ===
                                'cod'
                            )

                                Cash on delivery

                            @elseif(
                                $order->payment_method
                                ===
                                'sslcommerz'
                            )

                                SSLCOMMERZ

                            @else

                                Bank / mobile payment

                            @endif

                        </dd>

                    </div>



                    <div>

                        <dt
                            class="
                                text-[10px]
                                font-semibold
                                uppercase
                                tracking-wider
                                text-slate-400
                            "
                        >
                            Transaction ID
                        </dt>


                        <dd
                            class="
                                mt-1
                                break-all
                                font-mono
                                text-xs
                                text-slate-700
                            "
                        >

                            {{
                                $order->transaction_id
                                ?:
                                '—'
                            }}

                        </dd>

                    </div>



                    <div>

                        <dt
                            class="
                                text-[10px]
                                font-semibold
                                uppercase
                                tracking-wider
                                text-slate-400
                            "
                        >
                            Paid at
                        </dt>


                        <dd
                            class="
                                mt-1
                                text-slate-700
                            "
                        >

                            {{
                                $order->paid_at
                                    ?->format(
                                        'd M Y, h:i A'
                                    )
                                ?:
                                'Not marked paid'
                            }}

                        </dd>

                    </div>



                    <div>

                        <dt
                            class="
                                text-[10px]
                                font-semibold
                                uppercase
                                tracking-wider
                                text-slate-400
                            "
                        >
                            Delivered at
                        </dt>


                        <dd
                            class="
                                mt-1
                                text-slate-700
                            "
                        >

                            {{
                                $order->delivered_at
                                    ?->format(
                                        'd M Y, h:i A'
                                    )
                                ?:
                                'Not delivered yet'
                            }}

                        </dd>

                    </div>


                </dl>


            </section>



            {{-- =================================================
                ORDER INFORMATION
            ================================================== --}}
            <section class="admin-card p-5">


                <h2
                    class="
                        font-semibold
                        text-slate-900
                    "
                >
                    Order information
                </h2>



                <dl
                    class="
                        mt-4
                        grid
                        gap-4
                        text-sm
                    "
                >


                    <div
                        class="
                            flex
                            items-center
                            justify-between
                            gap-3
                        "
                    >

                        <dt class="text-slate-500">
                            Order number
                        </dt>


                        <dd
                            class="
                                font-mono
                                text-xs
                                font-semibold
                                text-slate-800
                            "
                        >
                            {{ $order->order_number }}
                        </dd>

                    </div>



                    <div
                        class="
                            flex
                            items-center
                            justify-between
                            gap-3
                        "
                    >

                        <dt class="text-slate-500">
                            Created
                        </dt>


                        <dd
                            class="
                                text-right
                                text-slate-700
                            "
                        >

                            {{
                                $order
                                    ->created_at
                                    ->format(
                                        'd M Y, h:i A'
                                    )
                            }}

                        </dd>

                    </div>



                    <div
                        class="
                            flex
                            items-center
                            justify-between
                            gap-3
                        "
                    >

                        <dt class="text-slate-500">
                            Last updated
                        </dt>


                        <dd
                            class="
                                text-right
                                text-slate-700
                            "
                        >

                            {{
                                $order
                                    ->updated_at
                                    ->format(
                                        'd M Y, h:i A'
                                    )
                            }}

                        </dd>

                    </div>



                    <div
                        class="
                            flex
                            items-center
                            justify-between
                            gap-3
                        "
                    >

                        <dt class="text-slate-500">
                            Coupon
                        </dt>


                        <dd
                            class="
                                font-semibold
                                text-slate-700
                            "
                        >

                            {{
                                $order->coupon?->code
                                ?:
                                '—'
                            }}

                        </dd>

                    </div>


                </dl>


            </section>


        </aside>


    </div>


</div>


@endsection