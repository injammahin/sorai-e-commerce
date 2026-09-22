@extends('admin.layouts.app')

@section('title', 'Products')
@section('page_title', 'Products')

@section('content')

@php

    $hasFilters =
        request()->filled('q')
        ||
        request()->filled('status')
        ||
        request()->filled('category')
        ||
        request()->filled('stock')
        ||
        request()->filled('sort');


    $activeFilterCount = collect([

        request('q'),

        request('status'),

        request('category'),

        request('stock'),

        request('sort'),

    ])
    ->filter(
        function ($value) {

            return
                $value !== null
                &&
                $value !== '';

        }
    )
    ->count();

@endphp



<div class="space-y-5">


    {{-- =========================================================
        FILTER / ACTION BAR
    ========================================================== --}}
    <section class="admin-card overflow-visible">


        <div
            class="
                border-b
                border-slate-100
                px-5
                py-4
                sm:px-6
            "
        >

            <div
                class="
                    flex
                    flex-col
                    gap-3

                    lg:flex-row
                    lg:items-center
                    lg:justify-between
                "
            >


                <div>

                    <div class="flex items-center gap-2">


                        <span
                            class="
                                grid
                                h-9
                                w-9
                                place-items-center
                                rounded-lg
                                bg-orange-50
                                text-copper-500
                            "
                        >

                            <i
                                class="
                                    fa-solid
                                    fa-filter
                                    text-sm
                                "
                            ></i>

                        </span>


                        <div>

                            <h2
                                class="
                                    text-sm
                                    font-semibold
                                    text-slate-900
                                "
                            >
                                Product filters
                            </h2>


                            <p
                                class="
                                    mt-0.5
                                    text-xs
                                    text-slate-500
                                "
                            >
                                Search and narrow down the product catalogue.
                            </p>

                        </div>


                    </div>

                </div>



                <a
                    class="
                        admin-btn
                        w-full
                        sm:w-auto
                    "
                    href="{{ route('admin.products.create') }}"
                >

                    <i class="fa-solid fa-plus"></i>

                    Add product

                </a>


            </div>

        </div>



        {{-- =====================================================
            FILTER FORM
        ====================================================== --}}
        <form
            method="GET"
            action="{{ route('admin.products.index') }}"
            class="p-5 sm:p-6"
        >


            <div
                class="
                    grid
                    gap-3

                    sm:grid-cols-2

                    xl:grid-cols-[minmax(260px,1.5fr)_minmax(170px,.8fr)_minmax(170px,.8fr)_minmax(170px,.8fr)_minmax(180px,.8fr)_auto]
                "
            >


                {{-- =================================================
                    SEARCH
                ================================================== --}}
                <div class="sm:col-span-2 xl:col-span-1">


                    <label
                        for="product-search"
                        class="admin-label"
                    >
                        Search
                    </label>


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
                            id="product-search"
                            class="admin-input !pl-10"
                            type="search"
                            name="q"
                            value="{{ request('q') }}"
                            placeholder="Name, SKU or material"
                            autocomplete="off"
                        >


                    </div>

                </div>



                {{-- =================================================
                    CATEGORY
                ================================================== --}}
                <div>


                    <label
                        for="category-filter"
                        class="admin-label"
                    >
                        Category
                    </label>


                    <select
                        id="category-filter"
                        class="admin-input"
                        name="category"
                    >

                        <option value="">
                            All categories
                        </option>


                        @foreach($categories as $category)

                            <option
                                value="{{ $category->id }}"
                                @selected(
                                    (string) request('category')
                                    ===
                                    (string) $category->id
                                )
                            >
                                {{ $category->name }}
                            </option>

                        @endforeach


                    </select>


                </div>



                {{-- =================================================
                    STATUS
                ================================================== --}}
                <div>


                    <label
                        for="status-filter"
                        class="admin-label"
                    >
                        Status
                    </label>


                    <select
                        id="status-filter"
                        class="admin-input"
                        name="status"
                    >

                        <option value="">
                            All status
                        </option>


                        <option
                            value="active"
                            @selected(
                                request('status')
                                ===
                                'active'
                            )
                        >
                            Active
                        </option>


                        <option
                            value="inactive"
                            @selected(
                                request('status')
                                ===
                                'inactive'
                            )
                        >
                            Hidden
                        </option>


                    </select>


                </div>



                {{-- =================================================
                    STOCK
                ================================================== --}}
                <div>


                    <label
                        for="stock-filter"
                        class="admin-label"
                    >
                        Stock
                    </label>


                    <select
                        id="stock-filter"
                        class="admin-input"
                        name="stock"
                    >

                        <option value="">
                            All stock
                        </option>


                        <option
                            value="in_stock"
                            @selected(
                                request('stock')
                                ===
                                'in_stock'
                            )
                        >
                            In stock
                        </option>


                        <option
                            value="low_stock"
                            @selected(
                                request('stock')
                                ===
                                'low_stock'
                            )
                        >
                            Low stock
                        </option>


                        <option
                            value="out_of_stock"
                            @selected(
                                request('stock')
                                ===
                                'out_of_stock'
                            )
                        >
                            Out of stock
                        </option>


                    </select>


                </div>



                {{-- =================================================
                    SORT
                ================================================== --}}
                <div>


                    <label
                        for="sort-filter"
                        class="admin-label"
                    >
                        Sort by
                    </label>


                    <select
                        id="sort-filter"
                        class="admin-input"
                        name="sort"
                    >

                        <option value="">
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
                            value="name_asc"
                            @selected(
                                request('sort')
                                ===
                                'name_asc'
                            )
                        >
                            Name A–Z
                        </option>


                        <option
                            value="name_desc"
                            @selected(
                                request('sort')
                                ===
                                'name_desc'
                            )
                        >
                            Name Z–A
                        </option>


                        <option
                            value="price_asc"
                            @selected(
                                request('sort')
                                ===
                                'price_asc'
                            )
                        >
                            Price low to high
                        </option>


                        <option
                            value="price_desc"
                            @selected(
                                request('sort')
                                ===
                                'price_desc'
                            )
                        >
                            Price high to low
                        </option>


                        <option
                            value="stock_asc"
                            @selected(
                                request('sort')
                                ===
                                'stock_asc'
                            )
                        >
                            Stock low to high
                        </option>


                        <option
                            value="stock_desc"
                            @selected(
                                request('sort')
                                ===
                                'stock_desc'
                            )
                        >
                            Stock high to low
                        </option>


                    </select>


                </div>



                {{-- =================================================
                    APPLY + CLEAR
                ================================================== --}}
                <div class="flex items-end gap-2">


                    <button
                        type="submit"
                        class="
                            admin-btn
                            h-[42px]
                            flex-1
                            xl:flex-none
                        "
                    >

                        <i class="fa-solid fa-filter"></i>

                        Apply

                    </button>



                    {{-- CLEAR FILTER ICON --}}
                    <a
                        href="{{ route('admin.products.index') }}"
                        class="
                            group
                            relative
                            grid
                            h-[42px]
                            w-[42px]
                            shrink-0
                            place-items-center
                            rounded-md
                            border
                            transition

                            {{
                                $hasFilters

                                ? 'border-red-200 bg-red-50 text-red-600 hover:border-red-300 hover:bg-red-100'

                                : 'pointer-events-none border-slate-200 bg-slate-50 text-slate-300'
                            }}
                        "
                        aria-label="Clear all filters"
                        title="Clear all filters"
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
                                    shadow-sm
                                "
                            >
                                {{ $activeFilterCount }}
                            </span>

                        @endif


                    </a>


                </div>


            </div>



            {{-- =================================================
                ACTIVE FILTERS
            ================================================== --}}
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
                            mr-1
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
                                inline-flex
                                items-center
                                gap-1.5
                                rounded-full
                                bg-slate-100
                                px-3
                                py-1.5
                                text-xs
                                text-slate-600
                            "
                        >

                            <i
                                class="
                                    fa-solid
                                    fa-magnifying-glass
                                    text-[9px]
                                    text-slate-400
                                "
                            ></i>

                            “{{ request('q') }}”

                        </span>

                    @endif



                    @if(request()->filled('category'))


                        @php

                            $selectedCategory =
                                $categories->firstWhere(
                                    'id',
                                    (int) request('category')
                                );

                        @endphp


                        @if($selectedCategory)

                            <span
                                class="
                                    inline-flex
                                    items-center
                                    gap-1.5
                                    rounded-full
                                    bg-slate-100
                                    px-3
                                    py-1.5
                                    text-xs
                                    text-slate-600
                                "
                            >

                                <i
                                    class="
                                        fa-solid
                                        fa-layer-group
                                        text-[9px]
                                        text-slate-400
                                    "
                                ></i>

                                {{ $selectedCategory->name }}

                            </span>

                        @endif


                    @endif



                    @if(request()->filled('status'))

                        <span
                            class="
                                inline-flex
                                items-center
                                gap-1.5
                                rounded-full
                                bg-slate-100
                                px-3
                                py-1.5
                                text-xs
                                text-slate-600
                            "
                        >

                            <i
                                class="
                                    fa-solid
                                    fa-circle
                                    text-[7px]

                                    {{
                                        request('status')
                                        ===
                                        'active'

                                        ? 'text-emerald-500'

                                        : 'text-slate-400'
                                    }}
                                "
                            ></i>


                            {{
                                request('status')
                                ===
                                'active'

                                ? 'Active'

                                : 'Hidden'
                            }}

                        </span>

                    @endif



                    @if(request()->filled('stock'))

                        <span
                            class="
                                inline-flex
                                items-center
                                gap-1.5
                                rounded-full
                                bg-slate-100
                                px-3
                                py-1.5
                                text-xs
                                text-slate-600
                            "
                        >

                            <i
                                class="
                                    fa-solid
                                    fa-boxes-stacked
                                    text-[9px]
                                    text-slate-400
                                "
                            ></i>


                            {{
                                [

                                    'in_stock' =>
                                        'In stock',

                                    'low_stock' =>
                                        'Low stock',

                                    'out_of_stock' =>
                                        'Out of stock',

                                ][request('stock')]
                                ??
                                request('stock')
                            }}

                        </span>

                    @endif



                    @if(request()->filled('sort'))

                        <span
                            class="
                                inline-flex
                                items-center
                                gap-1.5
                                rounded-full
                                bg-slate-100
                                px-3
                                py-1.5
                                text-xs
                                text-slate-600
                            "
                        >

                            <i
                                class="
                                    fa-solid
                                    fa-arrow-down-wide-short
                                    text-[9px]
                                    text-slate-400
                                "
                            ></i>

                            Sorted

                        </span>

                    @endif



                    <a
                        href="{{ route('admin.products.index') }}"
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
        RESULT INFORMATION
    ========================================================== --}}
    <div
        class="
            flex
            flex-col
            gap-2

            sm:flex-row
            sm:items-center
            sm:justify-between
        "
    >


        <div>


            <p
                class="
                    text-sm
                    font-semibold
                    text-slate-800
                "
            >
                Product catalogue
            </p>


            <p
                class="
                    mt-0.5
                    text-xs
                    text-slate-500
                "
            >

                @if($products->total())

                    Showing

                    {{ $products->firstItem() }}

                    –

                    {{ $products->lastItem() }}

                    of

                    {{ $products->total() }}

                    products

                @else

                    No products found

                @endif

            </p>


        </div>



        @if($hasFilters)

            <p
                class="
                    text-xs
                    text-slate-400
                "
            >
                Results reflect the filters above.
            </p>

        @endif


    </div>



    {{-- =========================================================
        PRODUCT TABLE
    ========================================================== --}}
    <section class="admin-card overflow-hidden">


        <div class="overflow-x-auto">


            <table
                class="
                    admin-table
                    min-w-[980px]
                "
            >


                <thead>


                    <tr>

                        <th>
                            Product
                        </th>

                        <th>
                            SKU
                        </th>

                        <th>
                            Category
                        </th>

                        <th>
                            Price
                        </th>

                        <th>
                            Stock
                        </th>

                        <th>
                            Status
                        </th>

                        <th class="text-right">
                            Actions
                        </th>

                    </tr>


                </thead>



                <tbody>


                    @forelse($products as $product)


                        <tr
                            class="
                                transition
                                hover:bg-slate-50/70
                            "
                        >


                            {{-- PRODUCT --}}
                            <td>


                                <div
                                    class="
                                        flex
                                        min-w-[290px]
                                        items-center
                                        gap-3
                                    "
                                >


                                    <div
                                        class="
                                            relative
                                            h-14
                                            w-12
                                            shrink-0
                                            overflow-hidden
                                            rounded-lg
                                            border
                                            border-slate-200
                                            bg-slate-100
                                        "
                                    >


                                        <img
                                            class="
                                                h-full
                                                w-full
                                                object-cover
                                            "
                                            src="{{
                                                asset(
                                                    $product
                                                        ->primaryImage
                                                        ->path
                                                )
                                            }}"
                                            alt="{{ $product->name }}"
                                        >


                                    </div>



                                    <div class="min-w-0">


                                        <p
                                            class="
                                                truncate
                                                font-semibold
                                                text-slate-900
                                            "
                                        >
                                            {{ $product->name }}
                                        </p>



                                        @if($product->material)

                                            <p
                                                class="
                                                    mt-1
                                                    truncate
                                                    text-[11px]
                                                    text-slate-400
                                                "
                                            >
                                                {{ $product->material }}
                                            </p>

                                        @endif


                                    </div>


                                </div>


                            </td>



                            {{-- SKU --}}
                            <td>


                                <span
                                    class="
                                        font-mono
                                        text-xs
                                        text-slate-600
                                    "
                                >
                                    {{ $product->sku }}
                                </span>


                            </td>



                            {{-- CATEGORY --}}
                            <td>


                                <span class="text-slate-700">

                                    {{
                                        $product
                                            ->category
                                            ?->name
                                        ??
                                        '—'
                                    }}

                                </span>


                            </td>



                            {{-- PRICE --}}
                            <td>


                                <span
                                    class="
                                        font-semibold
                                        text-slate-800
                                    "
                                >

                                    ৳{{
                                        number_format(
                                            (float)
                                            $product->price,
                                            0
                                        )
                                    }}

                                </span>



                                @if(
                                    $product->compare_price
                                    &&
                                    $product->compare_price
                                    >
                                    $product->price
                                )

                                    <span
                                        class="
                                            ml-1
                                            text-[11px]
                                            text-slate-400
                                            line-through
                                        "
                                    >

                                        ৳{{
                                            number_format(
                                                (float)
                                                $product->compare_price,
                                                0
                                            )
                                        }}

                                    </span>

                                @endif


                            </td>



                            {{-- STOCK --}}
                            <td>


                                @if(
                                    (int) $product->stock
                                    ===
                                    0
                                )


                                    <span
                                        class="
                                            inline-flex
                                            items-center
                                            gap-1.5
                                            font-semibold
                                            text-red-600
                                        "
                                    >

                                        <span
                                            class="
                                                h-1.5
                                                w-1.5
                                                rounded-full
                                                bg-red-500
                                            "
                                        ></span>

                                        0

                                    </span>


                                @elseif(
                                    (int) $product->stock
                                    <=
                                    (int) $product->low_stock_threshold
                                )


                                    <span
                                        class="
                                            inline-flex
                                            items-center
                                            gap-1.5
                                            font-semibold
                                            text-amber-600
                                        "
                                    >

                                        <span
                                            class="
                                                h-1.5
                                                w-1.5
                                                rounded-full
                                                bg-amber-500
                                            "
                                        ></span>

                                        {{ $product->stock }}

                                    </span>


                                @else


                                    <span
                                        class="
                                            inline-flex
                                            items-center
                                            gap-1.5
                                            text-slate-700
                                        "
                                    >

                                        <span
                                            class="
                                                h-1.5
                                                w-1.5
                                                rounded-full
                                                bg-emerald-500
                                            "
                                        ></span>

                                        {{ $product->stock }}

                                    </span>


                                @endif


                            </td>



                            {{-- STATUS --}}
                            <td>


                                @if($product->is_active)


                                    <span
                                        class="
                                            inline-flex
                                            items-center
                                            gap-1.5
                                            rounded-full
                                            bg-emerald-50
                                            px-2.5
                                            py-1
                                            text-[10px]
                                            font-bold
                                            uppercase
                                            tracking-wide
                                            text-emerald-700
                                        "
                                    >

                                        <span
                                            class="
                                                h-1.5
                                                w-1.5
                                                rounded-full
                                                bg-emerald-500
                                            "
                                        ></span>

                                        Active

                                    </span>


                                @else


                                    <span
                                        class="
                                            inline-flex
                                            items-center
                                            gap-1.5
                                            rounded-full
                                            bg-slate-100
                                            px-2.5
                                            py-1
                                            text-[10px]
                                            font-bold
                                            uppercase
                                            tracking-wide
                                            text-slate-500
                                        "
                                    >

                                        <span
                                            class="
                                                h-1.5
                                                w-1.5
                                                rounded-full
                                                bg-slate-400
                                            "
                                        ></span>

                                        Hidden

                                    </span>


                                @endif


                            </td>



                            {{-- ACTIONS --}}
                            <td>


                                <div
                                    class="
                                        flex
                                        justify-end
                                        gap-2
                                    "
                                >


                                    {{-- EDIT --}}
                                    <a
                                        href="{{
                                            route(
                                                'admin.products.edit',
                                                $product
                                            )
                                        }}"
                                        class="
                                            grid
                                            h-9
                                            w-9
                                            place-items-center
                                            rounded-lg
                                            border
                                            border-slate-200
                                            bg-white
                                            text-slate-600
                                            transition

                                            hover:border-copper-300
                                            hover:bg-orange-50
                                            hover:text-copper-600
                                        "
                                        title="Edit product"
                                        aria-label="Edit {{ $product->name }}"
                                    >

                                        <i
                                            class="
                                                fa-solid
                                                fa-pen
                                                text-xs
                                            "
                                        ></i>

                                    </a>



                                    {{-- DELETE --}}
                                    <button
                                        type="button"
                                        class="
                                            js-delete-product
                                            grid
                                            h-9
                                            w-9
                                            place-items-center
                                            rounded-lg
                                            border
                                            border-red-200
                                            bg-red-50
                                            text-red-600
                                            transition

                                            hover:border-red-300
                                            hover:bg-red-100
                                            hover:text-red-700
                                        "
                                        data-delete-url="{{
                                            route(
                                                'admin.products.destroy',
                                                $product
                                            )
                                        }}"
                                        data-product-name="{{ $product->name }}"
                                        title="Delete product"
                                        aria-label="Delete {{ $product->name }}"
                                    >

                                        <i
                                            class="
                                                fa-solid
                                                fa-trash-can
                                                text-xs
                                            "
                                        ></i>

                                    </button>


                                </div>


                            </td>


                        </tr>


                    @empty


                        <tr>


                            <td
                                colspan="7"
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
                                                fa-box-open
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
                                        No products found
                                    </h3>



                                    <p
                                        class="
                                            mt-1
                                            text-xs
                                            leading-5
                                            text-slate-500
                                        "
                                    >

                                        @if($hasFilters)

                                            Try changing or clearing the current filters.

                                        @else

                                            Start by adding your first product to the catalogue.

                                        @endif

                                    </p>



                                    <div
                                        class="
                                            mt-4
                                            flex
                                            justify-center
                                            gap-2
                                        "
                                    >


                                        @if($hasFilters)


                                            <a
                                                href="{{ route('admin.products.index') }}"
                                                class="
                                                    admin-btn
                                                    admin-btn-light
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



                                        <a
                                            href="{{ route('admin.products.create') }}"
                                            class="admin-btn"
                                        >

                                            <i class="fa-solid fa-plus"></i>

                                            Add product

                                        </a>


                                    </div>


                                </div>


                            </td>


                        </tr>


                    @endforelse


                </tbody>


            </table>


        </div>


    </section>



    {{-- =========================================================
        PAGINATION
    ========================================================== --}}
    @if($products->hasPages())


        <div class="pagination">

            {{ $products->links() }}

        </div>


    @endif


</div>



{{-- =============================================================
    DELETE CONFIRMATION MODAL
============================================================== --}}
<div
    id="delete-product-modal"
    class="
        fixed
        inset-0
        z-[100]
        hidden
        items-center
        justify-center
        p-4
    "
    role="dialog"
    aria-modal="true"
    aria-labelledby="delete-modal-title"
>


    {{-- BACKDROP --}}
    <div
        class="
            js-delete-backdrop
            absolute
            inset-0
            bg-slate-950/50
            backdrop-blur-[2px]
        "
    ></div>



    {{-- MODAL --}}
    <div
        class="
            relative
            z-10
            w-full
            max-w-md
            overflow-hidden
            rounded-2xl
            border
            border-slate-200
            bg-white
            shadow-2xl
        "
    >


        <div class="p-6 sm:p-7">


            <div class="flex items-start gap-4">


                <span
                    class="
                        grid
                        h-12
                        w-12
                        shrink-0
                        place-items-center
                        rounded-full
                        bg-red-50
                        text-red-600
                    "
                >

                    <i
                        class="
                            fa-solid
                            fa-trash-can
                        "
                    ></i>

                </span>



                <div
                    class="
                        min-w-0
                        flex-1
                    "
                >


                    <h2
                        id="delete-modal-title"
                        class="
                            text-lg
                            font-semibold
                            text-slate-900
                        "
                    >
                        Delete product?
                    </h2>



                    <p
                        class="
                            mt-2
                            text-sm
                            leading-6
                            text-slate-500
                        "
                    >

                        You are about to move

                        <strong
                            id="delete-product-name"
                            class="
                                font-semibold
                                text-slate-800
                            "
                        ></strong>

                        to trash.

                        This product will no longer appear in the active product list.

                    </p>


                </div>



                <button
                    type="button"
                    class="
                        js-close-delete-modal
                        grid
                        h-8
                        w-8
                        shrink-0
                        place-items-center
                        rounded-lg
                        text-slate-400
                        transition

                        hover:bg-slate-100
                        hover:text-slate-600
                    "
                    aria-label="Close delete confirmation"
                >

                    <i class="fa-solid fa-xmark"></i>

                </button>


            </div>


        </div>



        {{-- =====================================================
            MODAL ACTIONS
        ====================================================== --}}
        <div
            class="
                flex
                flex-col-reverse
                gap-2
                border-t
                border-slate-100
                bg-slate-50
                px-6
                py-4

                sm:flex-row
                sm:justify-end
            "
        >


            <button
                type="button"
                class="
                    js-close-delete-modal
                    admin-btn
                    admin-btn-light
                "
            >
                Cancel
            </button>



            <form
                id="delete-product-form"
                method="POST"
                action=""
            >

                @csrf

                @method('DELETE')


                <button
                    type="submit"
                    class="
                        admin-btn
                        admin-btn-danger
                        !bg-red-700
                        hover:!bg-red-800
                        w-full
                        sm:w-auto
                    "
                >

                    <i class="fa-solid fa-trash-can"></i>

                    Yes, move to trash

                </button>


            </form>


        </div>


    </div>


</div>



@endsection



{{-- =============================================================
    DELETE MODAL JAVASCRIPT
============================================================== --}}
@push('scripts')

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {


        /*
        |--------------------------------------------------------------------------
        | Elements
        |--------------------------------------------------------------------------
        */

        const modal =
            document.getElementById(
                'delete-product-modal'
            );


        const deleteForm =
            document.getElementById(
                'delete-product-form'
            );


        const productName =
            document.getElementById(
                'delete-product-name'
            );


        const deleteButtons =
            document.querySelectorAll(
                '.js-delete-product'
            );


        const closeButtons =
            document.querySelectorAll(
                '.js-close-delete-modal'
            );


        const backdrop =
            modal?.querySelector(
                '.js-delete-backdrop'
            );


        let lastTrigger = null;



        /*
        |--------------------------------------------------------------------------
        | Open modal
        |--------------------------------------------------------------------------
        */

        function openDeleteModal(button) {


            if (
                !modal
                ||
                !deleteForm
                ||
                !productName
            ) {
                return;
            }


            lastTrigger =
                button;


            /*
            |--------------------------------------------------------------------------
            | Set delete URL dynamically
            |--------------------------------------------------------------------------
            */

            deleteForm.action =
                button.dataset.deleteUrl
                ||
                '';


            /*
            |--------------------------------------------------------------------------
            | Show product name
            |--------------------------------------------------------------------------
            */

            productName.textContent =
                button.dataset.productName
                ||
                'this product';


            /*
            |--------------------------------------------------------------------------
            | Show modal
            |--------------------------------------------------------------------------
            */

            modal.classList.remove(
                'hidden'
            );


            modal.classList.add(
                'flex'
            );


            /*
            |--------------------------------------------------------------------------
            | Prevent page scrolling
            |--------------------------------------------------------------------------
            */

            document.body.classList.add(
                'overflow-hidden'
            );


            /*
            |--------------------------------------------------------------------------
            | Focus cancel button
            |--------------------------------------------------------------------------
            */

            const cancelButton =
                modal.querySelector(
                    '.js-close-delete-modal'
                );


            cancelButton?.focus();

        }



        /*
        |--------------------------------------------------------------------------
        | Close modal
        |--------------------------------------------------------------------------
        */

        function closeDeleteModal() {


            if (!modal) {
                return;
            }


            modal.classList.add(
                'hidden'
            );


            modal.classList.remove(
                'flex'
            );


            document.body.classList.remove(
                'overflow-hidden'
            );


            /*
            |--------------------------------------------------------------------------
            | Reset modal
            |--------------------------------------------------------------------------
            */

            deleteForm.action =
                '';


            productName.textContent =
                '';


            /*
            |--------------------------------------------------------------------------
            | Return keyboard focus
            |--------------------------------------------------------------------------
            */

            lastTrigger?.focus();


            lastTrigger =
                null;

        }



        /*
        |--------------------------------------------------------------------------
        | Delete buttons
        |--------------------------------------------------------------------------
        */

        deleteButtons.forEach(
            function (button) {


                button.addEventListener(
                    'click',
                    function () {


                        openDeleteModal(
                            button
                        );


                    }
                );


            }
        );



        /*
        |--------------------------------------------------------------------------
        | Close buttons
        |--------------------------------------------------------------------------
        */

        closeButtons.forEach(
            function (button) {


                button.addEventListener(
                    'click',
                    closeDeleteModal
                );


            }
        );



        /*
        |--------------------------------------------------------------------------
        | Close by clicking backdrop
        |--------------------------------------------------------------------------
        */

        backdrop?.addEventListener(
            'click',
            closeDeleteModal
        );



        /*
        |--------------------------------------------------------------------------
        | ESC keyboard support
        |--------------------------------------------------------------------------
        */

        document.addEventListener(
            'keydown',
            function (event) {


                if (
                    event.key === 'Escape'
                    &&
                    modal
                    &&
                    !modal.classList.contains(
                        'hidden'
                    )
                ) {


                    closeDeleteModal();


                }


            }
        );


    }
);

</script>

@endpush