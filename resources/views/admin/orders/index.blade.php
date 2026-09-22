@extends('admin.layouts.app')

@section('title', 'Orders')

@section('page_title', 'Orders')


@section('content')


@php

    $hasFilters =

        request()->filled('q')

        ||

        request()->filled('status')

        ||

        request()->filled('payment_status')

        ||

        request()->filled('payment_method')

        ||

        request()->filled('date_from')

        ||

        request()->filled('date_to')

        ||

        request()->filled('sort');


    $activeFilterCount = collect([

        request('q'),

        request('status'),

        request('payment_status'),

        request('payment_method'),

        request('date_from'),

        request('date_to'),

        request('sort'),

    ])
    ->filter(
        fn ($value) =>
            $value !== null
            &&
            $value !== ''
    )
    ->count();



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

@endphp



<div class="space-y-5">


    {{-- =========================================================
        FILTER CARD
    ========================================================== --}}
    <section class="admin-card overflow-visible">


        <div
            class="
                flex
                flex-col
                gap-3
                border-b
                border-slate-100
                px-5
                py-4

                sm:px-6

                lg:flex-row
                lg:items-center
                lg:justify-between
            "
        >


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

                    <i class="fa-solid fa-filter"></i>

                </span>


                <div>

                    <h2
                        class="
                            text-sm
                            font-semibold
                            text-slate-900
                        "
                    >
                        Order filters
                    </h2>


                    <p
                        class="
                            mt-0.5
                            text-xs
                            text-slate-500
                        "
                    >
                        Find orders by customer, status, payment,
                        method or date.
                    </p>

                </div>


            </div>



            <div class="text-xs text-slate-500">

                <span
                    class="
                        font-semibold
                        text-slate-800
                    "
                >
                    {{ number_format($orders->total()) }}
                </span>

                {{
                    $orders->total() === 1
                        ? 'order'
                        : 'orders'
                }}

                found

            </div>


        </div>



        <form
            method="GET"
            action="{{ route('admin.orders.index') }}"
            class="p-5 sm:p-6"
        >


            <div
                class="
                    grid
                    gap-3

                    md:grid-cols-2

                    xl:grid-cols-7
                "
            >


                {{-- SEARCH --}}
                <label class="xl:col-span-2">

                    <span class="admin-label">
                        Search
                    </span>


                    <div class="relative">

                        <span
                            class="
                                pointer-events-none
                                absolute
                                inset-y-0
                                left-0
                                grid
                                w-10
                                place-items-center
                                text-slate-400
                            "
                        >

                            <i
                                class="
                                    fa-solid
                                    fa-magnifying-glass
                                    text-xs
                                "
                            ></i>

                        </span>


                        <input
                            class="admin-input !pl-10"
                            type="search"
                            name="q"
                            value="{{ request('q') }}"
                            placeholder="Order no, name, email, phone, transaction"
                            autocomplete="off"
                        >

                    </div>

                </label>



                {{-- ORDER STATUS --}}
                <label>

                    <span class="admin-label">
                        Order status
                    </span>


                    <select
                        class="admin-input"
                        name="status"
                    >

                        <option value="">
                            All statuses
                        </option>


                        @foreach(['pending','confirmed','processing','shipped','delivered','cancelled','refunded'] as $status)

                            <option
                                value="{{ $status }}"
                                @selected(
                                    request('status')
                                    ===
                                    $status
                                )
                            >

                                {{ ucfirst($status) }}

                            </option>

                        @endforeach

                    </select>

                </label>



                {{-- PAYMENT STATUS --}}
                <label>

                    <span class="admin-label">
                        Payment
                    </span>


                    <select
                        class="admin-input"
                        name="payment_status"
                    >

                        <option value="">
                            All payment states
                        </option>


                        @foreach(['unpaid','pending','paid','failed','refunded'] as $paymentStatus)

                            <option
                                value="{{ $paymentStatus }}"
                                @selected(
                                    request('payment_status')
                                    ===
                                    $paymentStatus
                                )
                            >

                                {{ ucfirst($paymentStatus) }}

                            </option>

                        @endforeach

                    </select>

                </label>



                {{-- PAYMENT METHOD --}}
                <label>

                    <span class="admin-label">
                        Method
                    </span>


                    <select
                        class="admin-input"
                        name="payment_method"
                    >

                        <option value="">
                            All methods
                        </option>


                        <option
                            value="cod"
                            @selected(
                                request('payment_method')
                                ===
                                'cod'
                            )
                        >
                            Cash on delivery
                        </option>


                        <option
                            value="bank"
                            @selected(
                                request('payment_method')
                                ===
                                'bank'
                            )
                        >
                            Bank / mobile
                        </option>


                        <option
                            value="sslcommerz"
                            @selected(
                                request('payment_method')
                                ===
                                'sslcommerz'
                            )
                        >
                            SSLCOMMERZ
                        </option>

                    </select>

                </label>



                {{-- DATE FROM --}}
                <label>

                    <span class="admin-label">
                        From
                    </span>


                    <input
                        class="admin-input"
                        type="date"
                        name="date_from"
                        value="{{ request('date_from') }}"
                    >

                </label>



                {{-- DATE TO --}}
                <label>

                    <span class="admin-label">
                        To
                    </span>


                    <input
                        class="admin-input"
                        type="date"
                        name="date_to"
                        value="{{ request('date_to') }}"
                    >

                </label>


            </div>



            {{-- SECOND FILTER ROW --}}
            <div
                class="
                    mt-3
                    grid
                    gap-3

                    md:grid-cols-[minmax(180px,260px)_auto]
                    md:items-end
                    md:justify-between
                "
            >


                {{-- SORT --}}
                <label>

                    <span class="admin-label">
                        Sort by
                    </span>


                    <select
                        class="admin-input"
                        name="sort"
                    >

                        <option
                            value="newest"
                            @selected(
                                request(
                                    'sort',
                                    'newest'
                                )
                                ===
                                'newest'
                            )
                        >
                            Newest first
                        </option>


                        <option
                            value="oldest"
                            @selected(
                                request('sort')
                                ===
                                'oldest'
                            )
                        >
                            Oldest first
                        </option>


                        <option
                            value="total_high"
                            @selected(
                                request('sort')
                                ===
                                'total_high'
                            )
                        >
                            Total high to low
                        </option>


                        <option
                            value="total_low"
                            @selected(
                                request('sort')
                                ===
                                'total_low'
                            )
                        >
                            Total low to high
                        </option>

                    </select>

                </label>



                {{-- ACTIONS --}}
                <div
                    class="
                        flex
                        items-end
                        gap-2

                        md:justify-end
                    "
                >


                    <button
                        type="submit"
                        class="
                            admin-btn
                            h-[42px]
                            px-5
                        "
                    >

                        <i class="fa-solid fa-filter"></i>

                        Apply filters

                    </button>



                    {{-- CLEAR FILTER --}}
                    <a
                        href="{{ route('admin.orders.index') }}"
                        class="
                            relative
                            grid
                            h-[42px]
                            w-[42px]
                            place-items-center
                            rounded-md
                            border
                            transition

                            {{
                                $hasFilters

                                    ? 'border-red-200 bg-red-50 text-red-600 hover:bg-red-100'

                                    : 'pointer-events-none border-slate-200 bg-slate-50 text-slate-300'
                            }}
                        "
                        title="Clear all filters"
                        aria-label="Clear all filters"
                    >

                        <i
                            class="
                                fa-solid
                                fa-filter-circle-xmark
                            "
                        ></i>


                        @if($hasFilters)

                            <span
                                class="
                                    absolute
                                    -right-1.5
                                    -top-1.5
                                    grid
                                    h-5
                                    min-w-[20px]
                                    place-items-center
                                    rounded-full
                                    bg-red-600
                                    px-1
                                    text-[9px]
                                    font-bold
                                    text-white
                                "
                            >
                                {{ $activeFilterCount }}
                            </span>

                        @endif


                    </a>


                </div>


            </div>



            {{-- ACTIVE FILTERS --}}
            @if($hasFilters)

                <div
                    class="
                        mt-4
                        flex
                        flex-wrap
                        items-center
                        gap-2
                        border-t
                        border-slate-100
                        pt-4
                    "
                >

                    <span
                        class="
                            text-[11px]
                            font-semibold
                            uppercase
                            tracking-wider
                            text-slate-400
                        "
                    >
                        Active filters
                    </span>


                    @if(request()->filled('q'))

                        <span
                            class="
                                rounded-full
                                bg-slate-100
                                px-3
                                py-1.5
                                text-xs
                                text-slate-600
                            "
                        >
                            “{{ request('q') }}”
                        </span>

                    @endif


                    @if(request()->filled('status'))

                        <span
                            class="
                                rounded-full
                                bg-slate-100
                                px-3
                                py-1.5
                                text-xs
                                capitalize
                                text-slate-600
                            "
                        >
                            {{ request('status') }}
                        </span>

                    @endif


                    @if(request()->filled('payment_status'))

                        <span
                            class="
                                rounded-full
                                bg-slate-100
                                px-3
                                py-1.5
                                text-xs
                                capitalize
                                text-slate-600
                            "
                        >
                            Payment:
                            {{ request('payment_status') }}
                        </span>

                    @endif


                    @if(request()->filled('payment_method'))

                        <span
                            class="
                                rounded-full
                                bg-slate-100
                                px-3
                                py-1.5
                                text-xs
                                uppercase
                                text-slate-600
                            "
                        >
                            {{ request('payment_method') }}
                        </span>

                    @endif


                    @if(
                        request()->filled('date_from')
                        ||
                        request()->filled('date_to')
                    )

                        <span
                            class="
                                rounded-full
                                bg-slate-100
                                px-3
                                py-1.5
                                text-xs
                                text-slate-600
                            "
                        >

                            {{
                                request('date_from')
                                ?:
                                'Any date'
                            }}

                            →

                            {{
                                request('date_to')
                                ?:
                                'Today'
                            }}

                        </span>

                    @endif


                    <a
                        href="{{ route('admin.orders.index') }}"
                        class="
                            ml-auto
                            inline-flex
                            items-center
                            gap-1.5
                            text-xs
                            font-semibold
                            text-red-600

                            hover:text-red-700
                        "
                    >

                        <i class="fa-solid fa-xmark"></i>

                        Clear all

                    </a>

                </div>

            @endif


        </form>


    </section>



    {{-- =========================================================
        ORDER LIST
    ========================================================== --}}
    <section class="admin-card overflow-hidden">


        <div
            class="
                flex
                flex-col
                gap-2
                border-b
                border-slate-100
                px-5
                py-4

                sm:flex-row
                sm:items-center
                sm:justify-between
                sm:px-6
            "
        >


            <div>

                <h2
                    class="
                        text-sm
                        font-semibold
                        text-slate-900
                    "
                >
                    Order list
                </h2>


                <p
                    class="
                        mt-0.5
                        text-xs
                        text-slate-500
                    "
                >

                    @if($orders->total())

                        Showing

                        {{ $orders->firstItem() }}

                        –

                        {{ $orders->lastItem() }}

                        of

                        {{ $orders->total() }}

                        orders

                    @else

                        No orders match the current filters

                    @endif

                </p>

            </div>



            <p class="text-xs text-slate-400">

                Click an order number or View to open full details
                and update status.

            </p>


        </div>



        <div class="overflow-x-auto">


            <table
                class="
                    admin-table
                    min-w-[1180px]
                "
            >


                <thead>

                    <tr>

                        <th>Order</th>

                        <th>Customer</th>

                        <th>Status</th>

                        <th>Payment</th>

                        <th>Method</th>

                        <th>Items</th>

                        <th>Total</th>

                        <th>Date</th>

                        <th class="text-right">
                            Actions
                        </th>

                    </tr>

                </thead>



                <tbody>


                    @forelse($orders as $order)


                        <tr
                            class="
                                transition
                                hover:bg-slate-50/80
                            "
                        >


                            {{-- ORDER --}}
                            <td>

                                <a
                                    href="{{
                                        route(
                                            'admin.orders.show',
                                            $order
                                        )
                                    }}"
                                    class="
                                        inline-flex
                                        items-center
                                        gap-2
                                        font-semibold
                                        text-forest

                                        hover:text-copper-600
                                    "
                                >

                                    <span
                                        class="
                                            grid
                                            h-8
                                            w-8
                                            place-items-center
                                            rounded-lg
                                            bg-emerald-50
                                            text-forest
                                        "
                                    >

                                        <i
                                            class="
                                                fa-solid
                                                fa-receipt
                                                text-xs
                                            "
                                        ></i>

                                    </span>


                                    <span>
                                        {{ $order->order_number }}
                                    </span>

                                </a>

                            </td>



                            {{-- CUSTOMER --}}
                            <td>

                                <div class="min-w-[190px]">

                                    <p
                                        class="
                                            font-medium
                                            text-slate-800
                                        "
                                    >

                                        {{
                                            $order->user?->name
                                            ?:
                                            data_get(
                                                $order->shipping_address,
                                                'name',
                                                'Guest'
                                            )
                                        }}

                                    </p>


                                    <p
                                        class="
                                            mt-0.5
                                            text-[11px]
                                            text-slate-400
                                        "
                                    >

                                        {{
                                            data_get(
                                                $order->shipping_address,
                                                'email',
                                                '—'
                                            )
                                        }}

                                    </p>


                                    @if(
                                        data_get(
                                            $order->shipping_address,
                                            'phone'
                                        )
                                    )

                                        <p
                                            class="
                                                mt-0.5
                                                text-[11px]
                                                text-slate-400
                                            "
                                        >

                                            {{
                                                data_get(
                                                    $order->shipping_address,
                                                    'phone'
                                                )
                                            }}

                                        </p>

                                    @endif

                                </div>

                            </td>



                            {{-- STATUS --}}
                            <td>

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

                            </td>



                            {{-- PAYMENT --}}
                            <td>

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
                                            $paymentClasses[$order->payment_status]
                                            ??
                                            'border-slate-200 bg-slate-50 text-slate-600'
                                        }}
                                    "
                                >

                                    {{ $order->payment_status }}

                                </span>

                            </td>



                            {{-- METHOD --}}
                            <td>

                                <span
                                    class="
                                        text-xs
                                        font-medium
                                        text-slate-700
                                    "
                                >

                                    @if(
                                        $order->payment_method
                                        ===
                                        'cod'
                                    )

                                        COD

                                    @elseif(
                                        $order->payment_method
                                        ===
                                        'sslcommerz'
                                    )

                                        SSLCOMMERZ

                                    @else

                                        BANK

                                    @endif

                                </span>

                            </td>



                            {{-- ITEMS --}}
                            <td>

                                <span
                                    class="
                                        inline-flex
                                        items-center
                                        gap-1.5
                                        text-sm
                                        text-slate-700
                                    "
                                >

                                    <i
                                        class="
                                            fa-solid
                                            fa-box
                                            text-[10px]
                                            text-slate-400
                                        "
                                    ></i>

                                    {{ $order->items_count }}

                                </span>

                            </td>



                            {{-- TOTAL --}}
                            <td>

                                <span
                                    class="
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

                                </span>

                            </td>



                            {{-- DATE --}}
                            <td>

                                <div class="min-w-[120px]">

                                    <p
                                        class="
                                            text-sm
                                            text-slate-700
                                        "
                                    >
                                        {{
                                            $order
                                                ->created_at
                                                ->format(
                                                    'd M Y'
                                                )
                                        }}
                                    </p>


                                    <p
                                        class="
                                            mt-0.5
                                            text-[11px]
                                            text-slate-400
                                        "
                                    >
                                        {{
                                            $order
                                                ->created_at
                                                ->format(
                                                    'h:i A'
                                                )
                                        }}
                                    </p>

                                </div>

                            </td>



                            {{-- ACTION --}}
                            <td>

                                <div class="flex justify-end">

                                    <a
                                        href="{{
                                            route(
                                                'admin.orders.show',
                                                $order
                                            )
                                        }}"
                                        class="
                                            inline-flex
                                            items-center
                                            gap-2
                                            rounded-lg
                                            border
                                            border-slate-200
                                            bg-white
                                            px-3
                                            py-2
                                            text-xs
                                            font-semibold
                                            text-slate-700
                                            transition

                                            hover:border-copper-300
                                            hover:bg-orange-50
                                            hover:text-copper-600
                                        "
                                    >

                                        <i class="fa-solid fa-eye"></i>

                                        View

                                    </a>

                                </div>

                            </td>


                        </tr>


                    @empty


                        <tr>

                            <td
                                colspan="9"
                                class="
                                    !py-16
                                    text-center
                                "
                            >

                                <div class="mx-auto max-w-sm">


                                    <span
                                        class="
                                            mx-auto
                                            grid
                                            h-14
                                            w-14
                                            place-items-center
                                            rounded-full
                                            bg-slate-100
                                            text-slate-400
                                        "
                                    >

                                        <i
                                            class="
                                                fa-solid
                                                fa-receipt
                                                text-xl
                                            "
                                        ></i>

                                    </span>


                                    <h3
                                        class="
                                            mt-4
                                            text-base
                                            font-semibold
                                            text-slate-800
                                        "
                                    >
                                        No orders found
                                    </h3>


                                    <p
                                        class="
                                            mt-1
                                            text-xs
                                            leading-5
                                            text-slate-500
                                        "
                                    >

                                        {{
                                            $hasFilters

                                                ? 'Try changing or clearing your filters.'

                                                : 'New customer orders will appear here.'
                                        }}

                                    </p>


                                    @if($hasFilters)

                                        <a
                                            href="{{ route('admin.orders.index') }}"
                                            class="
                                                admin-btn
                                                admin-btn-light
                                                mt-4
                                            "
                                        >

                                            <i
                                                class="
                                                    fa-solid
                                                    fa-filter-circle-xmark
                                                "
                                            ></i>

                                            Clear filters

                                        </a>

                                    @endif


                                </div>

                            </td>

                        </tr>


                    @endforelse


                </tbody>


            </table>


        </div>


    </section>



    {{-- PAGINATION --}}
    @if($orders->hasPages())

        <div class="pagination">

            {{ $orders->links() }}

        </div>

    @endif


</div>


@endsection