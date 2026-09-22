@extends('admin.layouts.app')

@section('title', 'Categories')
@section('page_title', 'Categories')

@section('content')

@php
    $hasFilters =
        request()->filled('q')
        ||
        request()->filled('level')
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
                            fa-layer-group
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
                        Category management
                    </h2>


                    <p
                        class="
                            mt-0.5
                            text-xs
                            text-slate-500
                        "
                    >
                        Search, organize and manage storefront categories.
                    </p>

                </div>


            </div>



            <a
                href="{{ route('admin.categories.create') }}"
                class="
                    admin-btn
                    w-full

                    sm:w-auto
                "
            >

                <i class="fa-solid fa-plus"></i>

                Add category

            </a>


        </div>



        {{-- =====================================================
            FILTERS
        ====================================================== --}}
        <form
            method="GET"
            action="{{ route('admin.categories.index') }}"
            class="p-5 sm:p-6"
        >


            <div
                class="
                    grid
                    gap-3

                    md:grid-cols-2

                    xl:grid-cols-[minmax(300px,1.5fr)_minmax(170px,.7fr)_minmax(170px,.7fr)_minmax(190px,.8fr)_auto]
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
                            placeholder="Name, slug, tagline or parent"
                            autocomplete="off"
                        >


                        {{-- CLEAR SEARCH ONLY --}}
                        @if(request()->filled('q'))

                            <a
                                href="{{
                                    route(
                                        'admin.categories.index',
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



                {{-- TYPE --}}
                <label>

                    <span class="admin-label">
                        Type
                    </span>


                    <select
                        class="admin-input"
                        name="level"
                    >

                        <option value="">
                            All categories
                        </option>

                        <option
                            value="top"
                            @selected(
                                request('level')
                                ===
                                'top'
                            )
                        >
                            Top-level
                        </option>

                        <option
                            value="sub"
                            @selected(
                                request('level')
                                ===
                                'sub'
                            )
                        >
                            Subcategory
                        </option>

                    </select>

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
                            value="order"
                            @selected(
                                request(
                                    'sort',
                                    'order'
                                )
                                ===
                                'order'
                            )
                        >
                            Menu order
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
                        href="{{ route('admin.categories.index') }}"
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
                Category catalogue
            </h2>


            <p
                class="
                    mt-0.5
                    text-xs
                    text-slate-500
                "
            >

                @if($categories->total())

                    Showing

                    {{ $categories->firstItem() }}

                    –

                    {{ $categories->lastItem() }}

                    of

                    {{ $categories->total() }}

                    categories

                @else

                    No categories found

                @endif

            </p>


        </div>



        @if($hasFilters)

            <a
                href="{{ route('admin.categories.index') }}"
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
        CATEGORY TABLE
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

                        <th>Category</th>

                        <th>Parent</th>

                        <th>Type</th>

                        <th>Menu order</th>

                        <th>Menu</th>

                        <th>Status</th>

                        <th class="text-right">
                            Actions
                        </th>

                    </tr>

                </thead>



                <tbody>


                    @forelse($categories as $category)


                        <tr
                            class="
                                transition
                                hover:bg-slate-50/70
                            "
                        >


                            {{-- CATEGORY --}}
                            <td>


                                <div
                                    class="
                                        flex
                                        min-w-[280px]
                                        items-center
                                        gap-3
                                    "
                                >


                                    <div
                                        class="
                                            h-14
                                            w-14
                                            shrink-0
                                            overflow-hidden
                                            rounded-xl
                                            border
                                            border-slate-200
                                            bg-slate-100
                                        "
                                    >


                                        @if($category->image)

                                            <img
                                                src="{{ asset($category->image) }}"
                                                alt="{{ $category->name }}"
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

                                                <i class="fa-solid fa-image"></i>

                                            </div>

                                        @endif


                                    </div>



                                    <div class="min-w-0">


                                        <a
                                            href="{{
                                                route(
                                                    'admin.categories.edit',
                                                    $category
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

                                            {{ $category->name }}

                                        </a>


                                        <p
                                            class="
                                                mt-1
                                                truncate
                                                font-mono
                                                text-[10px]
                                                text-slate-400
                                            "
                                        >

                                            /{{ $category->slug }}

                                        </p>


                                    </div>


                                </div>


                            </td>



                            {{-- PARENT --}}
                            <td>

                                @if($category->parent)

                                    <span class="text-slate-700">

                                        {{ $category->parent->name }}

                                    </span>

                                @else

                                    <span class="text-slate-400">
                                        —
                                    </span>

                                @endif

                            </td>



                            {{-- TYPE --}}
                            <td>


                                @if($category->parent_id)

                                    <span
                                        class="
                                            inline-flex
                                            items-center
                                            gap-1.5
                                            rounded-full
                                            bg-violet-50
                                            px-2.5
                                            py-1
                                            text-[10px]
                                            font-semibold
                                            text-violet-700
                                        "
                                    >

                                        <i
                                            class="
                                                fa-solid
                                                fa-turn-up
                                                rotate-90
                                                text-[8px]
                                            "
                                        ></i>

                                        Subcategory

                                    </span>

                                @else

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
                                                fa-layer-group
                                                text-[8px]
                                            "
                                        ></i>

                                        Top-level

                                    </span>

                                @endif


                            </td>



                            {{-- SORT --}}
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

                                    {{ $category->sort_order }}

                                </span>

                            </td>



                            {{-- MENU VISIBILITY --}}
                            <td>


                                @if($category->show_in_menu)

                                    <span
                                        class="
                                            inline-flex
                                            items-center
                                            gap-1.5
                                            text-xs
                                            font-medium
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

                                        Visible

                                    </span>

                                @else

                                    <span
                                        class="
                                            inline-flex
                                            items-center
                                            gap-1.5
                                            text-xs
                                            font-medium
                                            text-slate-400
                                        "
                                    >

                                        <span
                                            class="
                                                h-1.5
                                                w-1.5
                                                rounded-full
                                                bg-slate-300
                                            "
                                        ></span>

                                        Hidden

                                    </span>

                                @endif


                            </td>



                            {{-- STATUS --}}
                            <td>


                                @if($category->is_active)

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


                                    <a
                                        href="{{
                                            route(
                                                'admin.categories.edit',
                                                $category
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
                                        title="Edit category"
                                        aria-label="Edit {{ $category->name }}"
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
                                            js-delete-category
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
                                                'admin.categories.destroy',
                                                $category
                                            )
                                        }}"
                                        data-category-name="{{ $category->name }}"
                                        title="Delete category"
                                        aria-label="Delete {{ $category->name }}"
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
                                                fa-layer-group
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
                                        No categories found
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
                                                : 'Create your first category to organize the storefront.'
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
                                                href="{{ route('admin.categories.index') }}"
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
                                            href="{{ route('admin.categories.create') }}"
                                            class="admin-btn"
                                        >

                                            <i class="fa-solid fa-plus"></i>

                                            Add category

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



    @if($categories->hasPages())

        <div class="pagination">

            {{ $categories->links() }}

        </div>

    @endif


</div>



{{-- =============================================================
    DELETE CONFIRMATION MODAL
============================================================== --}}
<div
    id="delete-category-modal"
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
    aria-labelledby="delete-category-title"
>


    <button
        type="button"
        class="
            js-delete-category-backdrop
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
                        id="delete-category-title"
                        class="
                            text-lg
                            font-semibold
                            text-slate-900
                        "
                    >
                        Delete category?
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
                            id="delete-category-name"
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

                        Categories that still contain child categories
                        or are used by products cannot be deleted.

                    </div>


                </div>



                <button
                    type="button"
                    class="
                        js-close-category-modal
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
                    js-close-category-modal
                    admin-btn
                    admin-btn-light
                "
            >
                Cancel
            </button>



            <form
                id="delete-category-form"
                method="POST"
                action=""
            >

                @csrf
                @method('DELETE')


                <button
                    type="submit"
                    class="
                        admin-btn
                        !bg-red-700
                        hover:!bg-red-800
                        w-full

                        sm:w-auto
                    "
                >

                    <i class="fa-solid fa-trash-can"></i>

                    Yes, delete category

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
                'delete-category-modal'
            );

        const form =
            document.getElementById(
                'delete-category-form'
            );

        const categoryName =
            document.getElementById(
                'delete-category-name'
            );

        const deleteButtons =
            document.querySelectorAll(
                '.js-delete-category'
            );

        const closeButtons =
            document.querySelectorAll(
                '.js-close-category-modal'
            );

        const backdrop =
            document.querySelector(
                '.js-delete-category-backdrop'
            );

        let lastTrigger = null;


        function openModal(button)
        {
            if (
                ! modal
                ||
                ! form
                ||
                ! categoryName
            ) {
                return;
            }

            lastTrigger =
                button;

            form.action =
                button.dataset.deleteUrl
                ||
                '';

            categoryName.textContent =
                button.dataset.categoryName
                ||
                'this category';

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
                    '.js-close-category-modal'
                )
                ?.focus();
        }


        function closeModal()
        {
            if (! modal) {
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
                form.action = '';
            }

            if (categoryName) {
                categoryName.textContent = '';
            }

            lastTrigger?.focus();

            lastTrigger = null;
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
                    ! modal.classList.contains(
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