@extends('admin.layouts.app')

@section('title', 'Banners')

@section(
    'page_title',
    'Banners & Hero Slides'
)


@section('content')


@php

    $hasFilters =

        request()->filled('q')

        ||

        request()->filled('placement')

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

                    <i class="fa-solid fa-images"></i>

                </span>



                <div>


                    <h2
                        class="
                            text-sm
                            font-semibold
                            text-slate-900
                        "
                    >
                        Banner management
                    </h2>


                    <p
                        class="
                            mt-0.5
                            text-xs
                            text-slate-500
                        "
                    >
                        Manage storefront hero slides,
                        promotional banners and category banners.
                    </p>


                </div>


            </div>



            <a
                href="{{ route('admin.banners.create') }}"
                class="
                    admin-btn
                    w-full

                    sm:w-auto
                "
            >

                <i class="fa-solid fa-plus"></i>

                Add banner

            </a>


        </div>



        {{-- =====================================================
            FILTERS
        ====================================================== --}}
        <form
            method="GET"
            action="{{ route('admin.banners.index') }}"
            class="p-5 sm:p-6"
        >


            <div
                class="
                    grid
                    gap-3

                    md:grid-cols-2

                    xl:grid-cols-[minmax(320px,1.5fr)_minmax(190px,.7fr)_minmax(180px,.7fr)_minmax(210px,.8fr)_auto]
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
                            type="search"
                            name="q"
                            value="{{ request('q') }}"
                            placeholder="Title, eyebrow, subtitle or button"
                            autocomplete="off"
                            class="
                                admin-input
                                !pl-10

                                {{
                                    request()->filled('q')
                                        ? '!pr-10'
                                        : ''
                                }}
                            "
                        >



                        {{-- CLEAR ONLY SEARCH --}}
                        @if(request()->filled('q'))

                            <a
                                href="{{
                                    route(
                                        'admin.banners.index',
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



                {{-- PLACEMENT --}}
                <label>


                    <span class="admin-label">
                        Placement
                    </span>


                    <select
                        name="placement"
                        class="admin-input"
                    >

                        <option value="">
                            All placements
                        </option>


                        <option
                            value="home_hero"
                            @selected(
                                request('placement')
                                ===
                                'home_hero'
                            )
                        >
                            Home hero
                        </option>
                        <option
                            value="home_popup"
                            @selected(request('placement') === 'home_popup')
                        >
                            Homepage popup
                        </option>

                        <option
                            value="home_promo"
                            @selected(
                                request('placement')
                                ===
                                'home_promo'
                            )
                        >
                            Home promo
                        </option>


                        <option
                            value="category"
                            @selected(
                                request('placement')
                                ===
                                'category'
                            )
                        >
                            Category
                        </option>


                    </select>


                </label>



                {{-- STATUS --}}
                <label>


                    <span class="admin-label">
                        Status
                    </span>


                    <select
                        name="status"
                        class="admin-input"
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
                        name="sort"
                        class="admin-input"
                    >

                        <option
                            value="placement_order"
                            @selected(
                                request(
                                    'sort',
                                    'placement_order'
                                )
                                ===
                                'placement_order'
                            )
                        >
                            Placement & order
                        </option>


                        <option
                            value="newest"
                            @selected(
                                request('sort')
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
                        href="{{ route('admin.banners.index') }}"
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


        </form>


    </section>



    {{-- =========================================================
        LIST HEADER
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
                Banner catalogue
            </h2>


            <p
                class="
                    mt-0.5
                    text-xs
                    text-slate-500
                "
            >

                @if($banners->total())

                    Showing

                    {{ $banners->firstItem() }}

                    –

                    {{ $banners->lastItem() }}

                    of

                    {{ $banners->total() }}

                    banners

                @else

                    No banners found

                @endif

            </p>


        </div>



        @if($hasFilters)

            <a
                href="{{ route('admin.banners.index') }}"
                class="
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

                Clear filters

            </a>

        @endif


    </div>



    {{-- =========================================================
        TABLE
    ========================================================== --}}
    <section class="admin-card overflow-hidden">


        <div class="overflow-x-auto">


            <table
                class="
                    admin-table
                    min-w-[1150px]
                "
            >


                <thead>

                    <tr>

                        <th>
                            Banner
                        </th>

                        <th>
                            Placement
                        </th>

                        <th>
                            Order
                        </th>

                        <th>
                            Schedule
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


                    @forelse($banners as $banner)


                        @php

                            $isScheduled =
                                $banner->starts_at
                                &&
                                $banner->starts_at->isFuture();


                            $isExpired =
                                $banner->ends_at
                                &&
                                $banner->ends_at->isPast();


                            $isLiveNow =
                                $banner->is_active
                                &&
                                ! $isScheduled
                                &&
                                ! $isExpired;

                        @endphp



                        <tr
                            class="
                                transition
                                hover:bg-slate-50/70
                            "
                        >


                            {{-- BANNER --}}
                            <td>


                                <div
                                    class="
                                        flex
                                        min-w-[360px]
                                        items-center
                                        gap-3
                                    "
                                >


                                    <div
                                        class="
                                            h-16
                                            w-28
                                            shrink-0
                                            overflow-hidden
                                            rounded-xl
                                            border
                                            border-slate-200
                                            bg-slate-100
                                        "
                                    >

                                        <img
                                            src="{{ asset($banner->image) }}"
                                            alt="{{ $banner->title }}"
                                            class="
                                                h-full
                                                w-full
                                                object-cover
                                            "
                                        >

                                    </div>



                                    <div class="min-w-0">


                                        <a
                                            href="{{
                                                route(
                                                    'admin.banners.edit',
                                                    $banner
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

                                            {{ $banner->title }}

                                        </a>



                                        @if($banner->eyebrow)

                                            <p
                                                class="
                                                    mt-1
                                                    truncate
                                                    text-[11px]
                                                    uppercase
                                                    tracking-wide
                                                    text-copper-500
                                                "
                                            >

                                                {{ $banner->eyebrow }}

                                            </p>

                                        @elseif($banner->subtitle)

                                            <p
                                                class="
                                                    mt-1
                                                    truncate
                                                    text-[11px]
                                                    text-slate-500
                                                "
                                            >

                                                {{ $banner->subtitle }}

                                            </p>

                                        @else

                                            <p
                                                class="
                                                    mt-1
                                                    text-[11px]
                                                    text-slate-400
                                                "
                                            >
                                                No supporting copy
                                            </p>

                                        @endif


                                    </div>


                                </div>


                            </td>



                            {{-- PLACEMENT --}}
                            <td>


                                @php

                                    $placementLabel = [

                                        'home_hero' =>
                                            'Home hero',

                                        'home_promo' =>
                                            'Home promo',
                                        'home_popup' => 'Homepage popup',

                                        'category' =>
                                            'Category',

                                    ][$banner->placement]
                                    ??
                                    $banner->placement;

                                @endphp


                                <span
                                    class="
                                        inline-flex
                                        items-center
                                        gap-1.5
                                        rounded-full
                                        bg-blue-50
                                        px-2.5
                                        py-1
                                        text-[10px]
                                        font-semibold
                                        text-blue-700
                                    "
                                >

                                    <i
                                        class="
                                            fa-solid
                                            fa-location-dot
                                            text-[8px]
                                        "
                                    ></i>

                                    {{ $placementLabel }}

                                </span>


                            </td>



                            {{-- ORDER --}}
                            <td>


                                <span
                                    class="
                                        inline-flex
                                        min-w-[36px]
                                        justify-center
                                        rounded-lg
                                        bg-slate-100
                                        px-2
                                        py-1
                                        text-xs
                                        font-semibold
                                        text-slate-600
                                    "
                                >

                                    {{ $banner->sort_order }}

                                </span>


                            </td>



                            {{-- SCHEDULE --}}
                            <td>


                                <div class="min-w-[190px]">


                                    @if($isExpired)

                                        <span
                                            class="
                                                inline-flex
                                                items-center
                                                gap-1.5
                                                text-xs
                                                font-semibold
                                                text-red-600
                                            "
                                        >

                                            <i class="fa-regular fa-clock"></i>

                                            Expired

                                        </span>

                                    @elseif($isScheduled)

                                        <span
                                            class="
                                                inline-flex
                                                items-center
                                                gap-1.5
                                                text-xs
                                                font-semibold
                                                text-amber-600
                                            "
                                        >

                                            <i class="fa-regular fa-clock"></i>

                                            Scheduled

                                        </span>

                                    @elseif($isLiveNow)

                                        <span
                                            class="
                                                inline-flex
                                                items-center
                                                gap-1.5
                                                text-xs
                                                font-semibold
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

                                            Live now

                                        </span>

                                    @else

                                        <span class="text-xs text-slate-400">
                                            Not live
                                        </span>

                                    @endif



                                    @if(
                                        $banner->starts_at
                                        ||
                                        $banner->ends_at
                                    )

                                        <p
                                            class="
                                                mt-1
                                                text-[10px]
                                                leading-4
                                                text-slate-400
                                            "
                                        >

                                            {{
                                                $banner->starts_at

                                                    ? $banner
                                                        ->starts_at
                                                        ->format(
                                                            'd M Y, h:i A'
                                                        )

                                                    : 'Any time'
                                            }}

                                            <br>

                                            →

                                            {{
                                                $banner->ends_at

                                                    ? $banner
                                                        ->ends_at
                                                        ->format(
                                                            'd M Y, h:i A'
                                                        )

                                                    : 'No end date'
                                            }}

                                        </p>

                                    @else

                                        <p
                                            class="
                                                mt-1
                                                text-[10px]
                                                text-slate-400
                                            "
                                        >
                                            No schedule
                                        </p>

                                    @endif


                                </div>


                            </td>



                            {{-- STATUS --}}
                            <td>


                                @if($banner->is_active)

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


                                <div class="min-w-[120px]">

                                    <p class="text-sm text-slate-700">

                                        {{
                                            $banner
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
                                            $banner
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


                                    <a
                                        href="{{
                                            route(
                                                'admin.banners.edit',
                                                $banner
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
                                        title="Edit banner"
                                        aria-label="Edit {{ $banner->title }}"
                                    >

                                        <i
                                            class="
                                                fa-solid
                                                fa-pen
                                                text-xs
                                            "
                                        ></i>

                                    </a>



                                    <button
                                        type="button"
                                        class="
                                            js-delete-banner
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
                                                'admin.banners.destroy',
                                                $banner
                                            )
                                        }}"
                                        data-banner-title="{{ $banner->title }}"
                                        title="Delete banner"
                                        aria-label="Delete {{ $banner->title }}"
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
                                                fa-regular
                                                fa-images
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
                                        No banners found
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

                                                : 'Create your first storefront banner or hero slide.'
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
                                                href="{{ route('admin.banners.index') }}"
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
                                            href="{{ route('admin.banners.create') }}"
                                            class="admin-btn"
                                        >

                                            <i class="fa-solid fa-plus"></i>

                                            Add banner

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
    @if($banners->hasPages())

        <div class="mt-5">

            {{
                $banners
                    ->onEachSide(1)
                    ->links()
            }}

        </div>

    @endif


</div>



{{-- =============================================================
    DELETE CONFIRMATION MODAL
============================================================== --}}
<div
    id="delete-banner-modal"
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
    aria-labelledby="delete-banner-modal-title"
>


    <button
        type="button"
        class="
            js-delete-banner-backdrop
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
                        id="delete-banner-modal-title"
                        class="
                            text-lg
                            font-semibold
                            text-slate-900
                        "
                    >
                        Delete banner?
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
                            id="delete-banner-name"
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

                        The banner record and its stored
                        desktop/mobile images will be removed.

                    </div>


                </div>



                <button
                    type="button"
                    class="
                        js-close-banner-modal
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
                    js-close-banner-modal
                    admin-btn
                    admin-btn-light
                "
            >
                Cancel
            </button>



            <form
                id="delete-banner-form"
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

                    Yes, delete banner

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
                'delete-banner-modal'
            );


        const form =
            document.getElementById(
                'delete-banner-form'
            );


        const bannerName =
            document.getElementById(
                'delete-banner-name'
            );


        const deleteButtons =
            document.querySelectorAll(
                '.js-delete-banner'
            );


        const closeButtons =
            document.querySelectorAll(
                '.js-close-banner-modal'
            );


        const backdrop =
            document.querySelector(
                '.js-delete-banner-backdrop'
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
                !bannerName
            ) {

                return;

            }


            lastTrigger =
                button;


            form.action =
                button.dataset.deleteUrl
                ||
                '';


            bannerName.textContent =
                button.dataset.bannerTitle
                ||
                'this banner';


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
                    '.js-close-banner-modal'
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


            if (bannerName) {

                bannerName.textContent =
                    '';

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