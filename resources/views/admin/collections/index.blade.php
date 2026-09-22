@extends('admin.layouts.app')

@section('title', 'Collections')
@section('page_title', 'Collections')


@section('content')


@php

    $hasFilters =

        request()->filled('q')

        ||

        request()->filled('status')

        ||

        request()->filled('sort');


    $searchClearParams =
        request()->except([
            'q',
            'page'
        ]);

@endphp



<div class="space-y-5">


    {{-- =========================================================
        FILTER / ACTION CARD
    ========================================================== --}}
    <section class="admin-card overflow-visible">


        <div
            class="
                flex
                flex-col
                gap-4
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
                        shrink-0
                        place-items-center
                        rounded-xl
                        bg-orange-50
                        text-copper-500
                    "
                >

                    <i
                        class="
                            fa-solid
                            fa-shapes
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
                        Collection management
                    </h2>


                    <p
                        class="
                            mt-0.5
                            text-xs
                            text-slate-500
                        "
                    >
                        Search, filter and manage the curated storefront collections.
                    </p>

                </div>


            </div>



            <a
                href="{{ route('admin.collections.create') }}"
                class="
                    admin-btn
                    w-full

                    sm:w-auto
                "
            >

                <i class="fa-solid fa-plus"></i>

                Add collection

            </a>


        </div>



        {{-- =====================================================
            FILTER FORM
        ====================================================== --}}
        <form
            method="GET"
            action="{{ route('admin.collections.index') }}"
            class="p-5 sm:p-6"
        >


            <div
                class="
                    grid
                    gap-3

                    md:grid-cols-2

                    xl:grid-cols-[minmax(320px,1.5fr)_minmax(180px,.7fr)_minmax(210px,.8fr)_auto]
                "
            >


                {{-- SEARCH --}}
                <label
                    class="
                        md:col-span-2
                        xl:col-span-1
                    "
                >


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
                            class="
                                admin-input
                                !pl-10

                                {{
                                    request()->filled('q')
                                        ? '!pr-10'
                                        : ''
                                }}
                            "
                            type="search"
                            name="q"
                            value="{{ request('q') }}"
                            placeholder="Title, slug, tagline or description"
                            autocomplete="off"
                        >



                        {{-- CLEAR ONLY SEARCH --}}
                        @if(request()->filled('q'))

                            <a
                                href="{{
                                    route(
                                        'admin.collections.index',
                                        $searchClearParams
                                    )
                                }}"
                                class="
                                    absolute
                                    inset-y-0
                                    right-0
                                    grid
                                    w-10
                                    place-items-center
                                    text-slate-400
                                    transition

                                    hover:text-red-600
                                "
                                title="Clear search"
                                aria-label="Clear search"
                            >

                                <i
                                    class="
                                        fa-solid
                                        fa-circle-xmark
                                        text-sm
                                    "
                                ></i>

                            </a>

                        @endif


                    </div>


                </label>



                {{-- STATUS --}}
                <label>


                    <span class="admin-label">
                        Status
                    </span>


                    <select
                        class="admin-input"
                        name="status"
                    >

                        <option value="">
                            All statuses
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


                </label>



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
                            value="title_asc"
                            @selected(
                                request('sort')
                                ===
                                'title_asc'
                            )
                        >
                            Title A–Z
                        </option>


                        <option
                            value="title_desc"
                            @selected(
                                request('sort')
                                ===
                                'title_desc'
                            )
                        >
                            Title Z–A
                        </option>


                        <option
                            value="products_high"
                            @selected(
                                request('sort')
                                ===
                                'products_high'
                            )
                        >
                            Most products
                        </option>


                        <option
                            value="products_low"
                            @selected(
                                request('sort')
                                ===
                                'products_low'
                            )
                        >
                            Fewest products
                        </option>


                    </select>


                </label>



                {{-- APPLY / CLEAR --}}
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



                    <a
                        href="{{ route('admin.collections.index') }}"
                        class="
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

                            {{
                                request('status')
                                ===
                                'active'

                                    ? 'Active'

                                    : 'Hidden'
                            }}

                        </span>

                    @endif



                    @if(request()->filled('sort'))

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
                            Custom sort
                        </span>

                    @endif



                    <a
                        href="{{ route('admin.collections.index') }}"
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
        LIST HEADING
    ========================================================== --}}
    <div
        class="
            flex
            flex-col
            gap-2

            sm:flex-row
            sm:items-end
            sm:justify-between
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
                Collection catalogue
            </h2>


            <p
                class="
                    mt-0.5
                    text-xs
                    text-slate-500
                "
            >

                @if($collections->total())

                    Showing

                    {{ $collections->firstItem() }}

                    –

                    {{ $collections->lastItem() }}

                    of

                    {{ $collections->total() }}

                    collections

                @else

                    No collections found

                @endif


            </p>


        </div>



        <p class="text-xs text-slate-400">
            Products remain intact when a collection is deleted.
        </p>


    </div>



    {{-- =========================================================
        TABLE
    ========================================================== --}}
    <section class="admin-card overflow-hidden">


        <div class="overflow-x-auto">


            <table
                class="
                    admin-table
                    min-w-[1050px]
                "
            >


                <thead>

                    <tr>

                        <th>
                            Collection
                        </th>

                        <th>
                            Products
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Updated
                        </th>

                        <th class="text-right">
                            Actions
                        </th>

                    </tr>

                </thead>



                <tbody>


                    @forelse($collections as $collection)


                        <tr
                            class="
                                transition
                                hover:bg-slate-50/70
                            "
                        >


                            {{-- COLLECTION --}}
                            <td>


                                <div
                                    class="
                                        flex
                                        min-w-[340px]
                                        items-center
                                        gap-3
                                    "
                                >


                                    <div
                                        class="
                                            h-16
                                            w-24
                                            shrink-0
                                            overflow-hidden
                                            rounded-xl
                                            border
                                            border-slate-200
                                            bg-slate-100
                                        "
                                    >


                                        @if($collection->image)

                                            <img
                                                src="{{ asset($collection->image) }}"
                                                alt="{{ $collection->title }}"
                                                class="
                                                    h-full
                                                    w-full
                                                    object-cover
                                                "
                                            >

                                        @else

                                            <div
                                                class="
                                                    grid
                                                    h-full
                                                    w-full
                                                    place-items-center
                                                    text-slate-300
                                                "
                                            >

                                                <i
                                                    class="
                                                        fa-regular
                                                        fa-image
                                                    "
                                                ></i>

                                            </div>

                                        @endif


                                    </div>



                                    <div class="min-w-0">


                                        <a
                                            href="{{
                                                route(
                                                    'admin.collections.edit',
                                                    $collection
                                                )
                                            }}"
                                            class="
                                                block
                                                truncate
                                                font-semibold
                                                text-slate-900
                                                transition

                                                hover:text-copper-600
                                            "
                                        >

                                            {{ $collection->title }}

                                        </a>



                                        @if($collection->tagline)

                                            <p
                                                class="
                                                    mt-1
                                                    max-w-[320px]
                                                    truncate
                                                    text-[11px]
                                                    text-slate-500
                                                "
                                            >

                                                {{ $collection->tagline }}

                                            </p>

                                        @endif



                                        <p
                                            class="
                                                mt-1
                                                truncate
                                                font-mono
                                                text-[10px]
                                                text-slate-400
                                            "
                                        >
                                            /collection/{{ $collection->slug }}
                                        </p>


                                    </div>


                                </div>


                            </td>



                            {{-- PRODUCTS --}}
                            <td>


                                <span
                                    class="
                                        inline-flex
                                        items-center
                                        gap-2
                                        rounded-lg
                                        bg-slate-100
                                        px-3
                                        py-1.5
                                        text-xs
                                        font-semibold
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

                                    {{ $collection->products_count }}

                                </span>


                            </td>



                            {{-- STATUS --}}
                            <td>


                                @if($collection->is_active)


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



                            {{-- UPDATED --}}
                            <td>


                                <div class="min-w-[130px]">


                                    <p
                                        class="
                                            text-sm
                                            text-slate-700
                                        "
                                    >

                                        {{
                                            $collection
                                                ->updated_at
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
                                            $collection
                                                ->updated_at
                                                ->format(
                                                    'h:i A'
                                                )
                                        }}

                                    </p>


                                </div>


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


                                    {{-- VIEW STOREFRONT --}}
                                    @if($collection->is_active)

                                        <a
                                            href="{{
                                                route(
                                                    'collections.show',
                                                    $collection
                                                )
                                            }}"
                                            target="_blank"
                                            rel="noopener"
                                            class="
                                                grid
                                                h-9
                                                w-9
                                                place-items-center
                                                rounded-lg
                                                border
                                                border-slate-200
                                                bg-white
                                                text-slate-500
                                                transition

                                                hover:border-emerald-300
                                                hover:bg-emerald-50
                                                hover:text-emerald-700
                                            "
                                            title="View collection on storefront"
                                            aria-label="View {{ $collection->title }}"
                                        >

                                            <i
                                                class="
                                                    fa-solid
                                                    fa-arrow-up-right-from-square
                                                    text-xs
                                                "
                                            ></i>

                                        </a>

                                    @endif



                                    {{-- EDIT --}}
                                    <a
                                        href="{{
                                            route(
                                                'admin.collections.edit',
                                                $collection
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
                                        title="Edit collection"
                                        aria-label="Edit {{ $collection->title }}"
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
                                            js-delete-collection
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
                                                'admin.collections.destroy',
                                                $collection
                                            )
                                        }}"
                                        data-collection-title="{{ $collection->title }}"
                                        data-products-count="{{ $collection->products_count }}"
                                        title="Delete collection"
                                        aria-label="Delete {{ $collection->title }}"
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
                                colspan="5"
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
                                                fa-shapes
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
                                        No collections found
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

                                                ? 'Try changing or clearing the current filters.'

                                                : 'Create your first curated collection for the storefront.'
                                        }}

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
                                                href="{{ route('admin.collections.index') }}"
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
                                            href="{{ route('admin.collections.create') }}"
                                            class="admin-btn"
                                        >

                                            <i class="fa-solid fa-plus"></i>

                                            Add collection

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



    {{-- PAGINATION --}}
    @if($collections->hasPages())

        <div class="pagination">

            {{ $collections->links() }}

        </div>

    @endif


</div>



{{-- =============================================================
    DELETE CONFIRMATION MODAL
============================================================== --}}
<div
    id="delete-collection-modal"
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
    aria-labelledby="delete-collection-title"
>


    {{-- BACKDROP --}}
    <button
        type="button"
        class="
            js-delete-collection-backdrop
            absolute
            inset-0
            cursor-default
            bg-slate-950/50
            backdrop-blur-[2px]
        "
        aria-label="Close delete confirmation"
    ></button>



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

                    <i class="fa-solid fa-trash-can"></i>

                </span>



                <div class="min-w-0 flex-1">


                    <h2
                        id="delete-collection-title"
                        class="
                            text-lg
                            font-semibold
                            text-slate-900
                        "
                    >
                        Delete collection?
                    </h2>



                    <p
                        class="
                            mt-2
                            text-sm
                            leading-6
                            text-slate-500
                        "
                    >

                        You are about to permanently delete

                        <strong
                            id="delete-collection-name"
                            class="
                                font-semibold
                                text-slate-800
                            "
                        ></strong>.

                    </p>



                    <div
                        class="
                            mt-3
                            rounded-lg
                            border
                            border-amber-200
                            bg-amber-50
                            px-3
                            py-2.5
                            text-xs
                            leading-5
                            text-amber-800
                        "
                    >

                        <i
                            class="
                                fa-solid
                                fa-circle-info
                                mr-1
                            "
                        ></i>

                        The collection will be removed, but the

                        <strong
                            id="delete-collection-product-count"
                        >
                            0
                        </strong>

                        linked product(s) will remain in your
                        product catalogue.

                    </div>


                </div>



                <button
                    type="button"
                    class="
                        js-close-collection-modal
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
                    js-close-collection-modal
                    admin-btn
                    admin-btn-light
                "
            >
                Cancel
            </button>



            <form
                id="delete-collection-form"
                method="POST"
                action=""
            >

                @csrf

                @method('DELETE')


                <button
                    type="submit"
                    class="
                        admin-btn
                        w-full
                        !bg-red-700

                        hover:!bg-red-800

                        sm:w-auto
                    "
                >

                    <i class="fa-solid fa-trash-can"></i>

                    Yes, delete collection

                </button>


            </form>


        </div>


    </div>


</div>


@endsection



@push('scripts')

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {


        const modal =
            document.getElementById(
                'delete-collection-modal'
            );


        const form =
            document.getElementById(
                'delete-collection-form'
            );


        const collectionName =
            document.getElementById(
                'delete-collection-name'
            );


        const productCount =
            document.getElementById(
                'delete-collection-product-count'
            );


        const deleteButtons =
            document.querySelectorAll(
                '.js-delete-collection'
            );


        const closeButtons =
            document.querySelectorAll(
                '.js-close-collection-modal'
            );


        const backdrop =
            document.querySelector(
                '.js-delete-collection-backdrop'
            );


        let lastTrigger =
            null;



        function openModal(
            button
        ) {


            if (
                !modal
                ||
                !form
                ||
                !collectionName
                ||
                !productCount
            ) {

                return;

            }


            lastTrigger =
                button;


            form.action =
                button.dataset.deleteUrl
                ||
                '';


            collectionName.textContent =
                button.dataset.collectionTitle
                ||
                'this collection';


            productCount.textContent =
                button.dataset.productsCount
                ||
                '0';


            modal.classList.remove(
                'hidden'
            );


            modal.classList.add(
                'flex'
            );


            document.body.classList.add(
                'overflow-hidden'
            );


            modal
                .querySelector(
                    '.js-close-collection-modal'
                )
                ?.focus();

        }



        function closeModal()
        {


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


            if (form) {

                form.action =
                    '';

            }


            if (collectionName) {

                collectionName.textContent =
                    '';

            }


            if (productCount) {

                productCount.textContent =
                    '0';

            }


            lastTrigger?.focus();


            lastTrigger =
                null;

        }



        deleteButtons.forEach(
            function (button) {


                button.addEventListener(
                    'click',
                    function () {


                        openModal(
                            button
                        );


                    }
                );


            }
        );



        closeButtons.forEach(
            function (button) {


                button.addEventListener(
                    'click',
                    closeModal
                );


            }
        );



        backdrop?.addEventListener(
            'click',
            closeModal
        );



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

                    closeModal();

                }


            }
        );


    }
);

</script>

@endpush