@extends('admin.layouts.app')

@section(
    'title',
    $collection->exists
        ? 'Edit Collection'
        : 'New Collection'
)

@section(
    'page_title',
    $collection->exists
        ? 'Edit Collection'
        : 'Create Collection'
)


@section('content')


@php

    $selectedProductIds = collect(

        old(

            'products',

            $collection->exists
                ? $collection->products->pluck('id')->all()
                : []

        )

    )
        ->map(
            fn ($id) =>
                (int) $id
        );

@endphp



<div class="mx-auto max-w-screen-2xl">


    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div
        class="
            mb-6
            flex
            flex-col
            gap-4

            sm:flex-row
            sm:items-center
            sm:justify-between
        "
    >


        <div>


            <div
                class="
                    mb-1
                    flex
                    items-center
                    gap-2
                    text-xs
                    text-slate-500
                "
            >

                <a
                    href="{{ route('admin.collections.index') }}"
                    class="
                        transition
                        hover:text-copper-500
                    "
                >
                    Collections
                </a>


                <i
                    class="
                        fa-solid
                        fa-chevron-right
                        text-[9px]
                    "
                ></i>


                <span>

                    {{
                        $collection->exists
                            ? 'Edit'
                            : 'Create'
                    }}

                </span>

            </div>



            <h2
                class="
                    text-2xl
                    font-semibold
                    text-slate-900
                "
            >

                {{
                    $collection->exists
                        ? $collection->title
                        : 'Add a new collection'
                }}

            </h2>



            <p
                class="
                    mt-1
                    text-sm
                    text-slate-500
                "
            >

                {{
                    $collection->exists

                        ? 'Update collection content, cover image, product selection and storefront visibility.'

                        : 'Build a curated collection and choose which products should appear in it.'
                }}

            </p>


        </div>



        <div class="flex flex-wrap gap-2">


            <a
                href="{{ route('admin.collections.index') }}"
                class="
                    admin-btn
                    admin-btn-light
                "
            >

                <i class="fa-solid fa-arrow-left"></i>

                Back to collections

            </a>



            @if(
                $collection->exists
                &&
                $collection->is_active
            )

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
                        admin-btn
                        admin-btn-light
                    "
                >

                    <i
                        class="
                            fa-solid
                            fa-arrow-up-right-from-square
                        "
                    ></i>

                    View storefront

                </a>

            @endif


        </div>


    </div>



    {{-- =========================================================
        VALIDATION SUMMARY
    ========================================================== --}}
    @if($errors->any())

        <div
            class="
                mb-5
                rounded-xl
                border
                border-red-200
                bg-red-50
                px-5
                py-4
                text-red-800
            "
        >


            <div class="flex items-start gap-3">


                <i
                    class="
                        fa-solid
                        fa-circle-exclamation
                        mt-0.5
                    "
                ></i>



                <div>


                    <p
                        class="
                            text-sm
                            font-semibold
                        "
                    >
                        Please correct the highlighted fields.
                    </p>


                    <ul
                        class="
                            mt-2
                            list-disc
                            space-y-1
                            pl-5
                            text-xs
                        "
                    >

                        @foreach($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>


                </div>


            </div>


        </div>

    @endif



    {{-- =========================================================
        FORM
    ========================================================== --}}
    <form
        id="collection-form"
        method="POST"
        enctype="multipart/form-data"
        action="{{
            $collection->exists

                ? route(
                    'admin.collections.update',
                    $collection
                )

                : route(
                    'admin.collections.store'
                )
        }}"
    >


        @csrf


        @if($collection->exists)

            @method('PUT')

        @endif



        <div
            class="
                grid
                gap-6

                xl:grid-cols-[minmax(0,1fr)_370px]
                xl:items-start
            "
        >


            {{-- =================================================
                LEFT CONTENT
            ================================================== --}}
            <div
                class="
                    grid
                    min-w-0
                    gap-6
                "
            >


                {{-- =============================================
                    BASIC INFORMATION
                ============================================== --}}
                <section class="admin-card overflow-hidden">


                    <div
                        class="
                            flex
                            items-center
                            gap-3
                            border-b
                            border-slate-100
                            px-5
                            py-4

                            sm:px-6
                        "
                    >


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

                            <i class="fa-solid fa-shapes"></i>

                        </span>



                        <div>


                            <h3
                                class="
                                    font-semibold
                                    text-slate-900
                                "
                            >
                                Collection information
                            </h3>


                            <p
                                class="
                                    mt-0.5
                                    text-xs
                                    text-slate-500
                                "
                            >
                                Main content shown on the collection page.
                            </p>


                        </div>


                    </div>



                    <div class="p-5 sm:p-6">


                        <div
                            class="
                                grid
                                gap-5

                                md:grid-cols-2
                            "
                        >


                            {{-- TITLE --}}
                            <label>


                                <span class="admin-label">

                                    Title

                                    <span class="text-red-500">
                                        *
                                    </span>

                                </span>


                                <input
                                    id="collection-title-input"
                                    class="
                                        admin-input

                                        @error('title')
                                            !border-red-400
                                        @enderror
                                    "
                                    name="title"
                                    value="{{ old('title', $collection->title) }}"
                                    maxlength="191"
                                    autocomplete="off"
                                    required
                                >


                                @error('title')

                                    <span
                                        class="
                                            mt-1
                                            block
                                            text-xs
                                            text-red-600
                                        "
                                    >
                                        {{ $message }}
                                    </span>

                                @enderror


                            </label>



                            {{-- SLUG --}}
                            <label>


                                <span class="admin-label">

                                    Slug

                                    <span class="text-red-500">
                                        *
                                    </span>

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
                                                fa-link
                                                text-xs
                                            "
                                        ></i>

                                    </span>



                                    <input
                                        id="collection-slug-input"
                                        class="
                                            admin-input
                                            !pl-10

                                            @error('slug')
                                                !border-red-400
                                            @enderror
                                        "
                                        name="slug"
                                        value="{{ old('slug', $collection->slug) }}"
                                        maxlength="191"
                                        autocomplete="off"
                                        required
                                    >


                                </div>



                                <span
                                    class="
                                        mt-1
                                        block
                                        text-[11px]
                                        text-slate-400
                                    "
                                >
                                    Storefront URL: /collection/your-slug
                                </span>


                                @error('slug')

                                    <span
                                        class="
                                            mt-1
                                            block
                                            text-xs
                                            text-red-600
                                        "
                                    >
                                        {{ $message }}
                                    </span>

                                @enderror


                            </label>



                            {{-- TAGLINE --}}
                            <label class="md:col-span-2">


                                <span class="admin-label">
                                    Tagline
                                </span>


                                <input
                                    class="
                                        admin-input

                                        @error('tagline')
                                            !border-red-400
                                        @enderror
                                    "
                                    name="tagline"
                                    value="{{ old('tagline', $collection->tagline) }}"
                                    maxlength="191"
                                    placeholder="A short phrase that introduces this collection"
                                >


                                @error('tagline')

                                    <span
                                        class="
                                            mt-1
                                            block
                                            text-xs
                                            text-red-600
                                        "
                                    >
                                        {{ $message }}
                                    </span>

                                @enderror


                            </label>



                            {{-- DESCRIPTION --}}
                            <label class="md:col-span-2">


                                <div
                                    class="
                                        flex
                                        items-center
                                        justify-between
                                        gap-3
                                    "
                                >

                                    <span class="admin-label">
                                        Description
                                    </span>


                                    <span
                                        id="collection-description-count"
                                        class="
                                            text-[10px]
                                            text-slate-400
                                        "
                                    >
                                        0 / 5000
                                    </span>


                                </div>



                                <textarea
                                    id="collection-description-input"
                                    class="
                                        admin-input
                                        min-h-[180px]
                                        resize-y

                                        @error('description')
                                            !border-red-400
                                        @enderror
                                    "
                                    rows="7"
                                    name="description"
                                    maxlength="5000"
                                    placeholder="Tell customers the story, theme or idea behind this collection..."
                                >{{ old('description', $collection->description) }}</textarea>


                                @error('description')

                                    <span
                                        class="
                                            mt-1
                                            block
                                            text-xs
                                            text-red-600
                                        "
                                    >
                                        {{ $message }}
                                    </span>

                                @enderror


                            </label>


                        </div>


                    </div>


                </section>



                {{-- =============================================
                    COVER IMAGE
                ============================================== --}}
                <section class="admin-card overflow-hidden">


                    <div
                        class="
                            flex
                            items-center
                            gap-3
                            border-b
                            border-slate-100
                            px-5
                            py-4

                            sm:px-6
                        "
                    >


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

                            <i class="fa-regular fa-image"></i>

                        </span>



                        <div>


                            <h3
                                class="
                                    font-semibold
                                    text-slate-900
                                "
                            >
                                Collection cover
                            </h3>


                            <p
                                class="
                                    mt-0.5
                                    text-xs
                                    text-slate-500
                                "
                            >
                                This image is used on the storefront collection card and collection hero.
                            </p>


                        </div>


                    </div>



                    <div class="p-5 sm:p-6">


                        <div
                            class="
                                overflow-hidden
                                rounded-xl
                                border
                                border-slate-200
                                bg-slate-50
                            "
                        >


                            <div
                                class="
                                    relative
                                    aspect-[16/7]
                                    w-full
                                    bg-slate-100
                                "
                            >


                                <img
                                    id="collection-image-preview"
                                    src="{{
                                        $collection->image
                                            ? asset($collection->image)
                                            : ''
                                    }}"
                                    alt="Collection cover preview"
                                    class="
                                        {{
                                            $collection->image
                                                ? ''
                                                : 'hidden'
                                        }}

                                        h-full
                                        w-full
                                        object-cover
                                    "
                                >



                                <div
                                    id="collection-image-empty"
                                    class="
                                        {{
                                            $collection->image
                                                ? 'hidden'
                                                : ''
                                        }}

                                        absolute
                                        inset-0
                                        grid
                                        place-items-center
                                        p-6
                                        text-center
                                    "
                                >


                                    <div>


                                        <span
                                            class="
                                                mx-auto
                                                grid
                                                h-14
                                                w-14
                                                place-items-center
                                                rounded-full
                                                bg-white
                                                text-slate-400
                                                shadow-sm
                                            "
                                        >

                                            <i
                                                class="
                                                    fa-regular
                                                    fa-image
                                                    text-lg
                                                "
                                            ></i>

                                        </span>



                                        <p
                                            class="
                                                mt-3
                                                text-sm
                                                font-medium
                                                text-slate-700
                                            "
                                        >
                                            No cover image selected
                                        </p>



                                        <p
                                            class="
                                                mt-1
                                                text-xs
                                                text-slate-400
                                            "
                                        >
                                            Choose a wide lifestyle image for the best storefront presentation.
                                        </p>


                                    </div>


                                </div>



                                <div
                                    id="collection-image-new-badge"
                                    class="
                                        absolute
                                        left-3
                                        top-3
                                        hidden
                                        rounded-full
                                        bg-slate-950/75
                                        px-2.5
                                        py-1
                                        text-[10px]
                                        font-semibold
                                        uppercase
                                        tracking-wide
                                        text-white
                                        backdrop-blur
                                    "
                                >
                                    New preview
                                </div>


                            </div>



                            <div
                                class="
                                    border-t
                                    border-slate-200
                                    bg-white
                                    p-4
                                "
                            >


                                <div
                                    class="
                                        flex
                                        flex-col
                                        gap-3

                                        sm:flex-row
                                        sm:items-center
                                        sm:justify-between
                                    "
                                >


                                    <div>


                                        <p
                                            class="
                                                text-xs
                                                font-semibold
                                                text-slate-700
                                            "
                                        >

                                            {{
                                                $collection->exists
                                                    ? 'Replace cover image'
                                                    : 'Upload cover image'
                                            }}

                                        </p>



                                        <p
                                            class="
                                                mt-1
                                                text-[11px]
                                                text-slate-400
                                            "
                                        >
                                            JPG, PNG or WebP · maximum 6 MB.
                                        </p>


                                    </div>



                                    <input
                                        id="collection-image-input"
                                        class="
                                            block
                                            w-full
                                            cursor-pointer
                                            rounded-lg
                                            border
                                            border-slate-200
                                            bg-white
                                            text-xs
                                            text-slate-500

                                            file:mr-3
                                            file:border-0
                                            file:bg-forest
                                            file:px-3
                                            file:py-2
                                            file:text-xs
                                            file:font-semibold
                                            file:text-white

                                            hover:file:bg-copper-500

                                            sm:max-w-md
                                        "
                                        type="file"
                                        name="image"
                                        accept="image/jpeg,image/png,image/webp"
                                        @required(!$collection->exists)
                                    >


                                </div>



                                <p
                                    id="collection-image-filename"
                                    class="
                                        mt-2
                                        hidden
                                        text-[11px]
                                        font-medium
                                        text-emerald-700
                                    "
                                ></p>


                            </div>


                        </div>



                        @error('image')

                            <span
                                class="
                                    mt-1
                                    block
                                    text-xs
                                    text-red-600
                                "
                            >
                                {{ $message }}
                            </span>

                        @enderror


                    </div>


                </section>



                {{-- =============================================
                    PRODUCT PICKER
                ============================================== --}}
                <section class="admin-card overflow-hidden">


                    <div
                        class="
                            flex
                            flex-col
                            gap-3
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


                        <div class="flex items-center gap-3">


                            <span
                                class="
                                    grid
                                    h-10
                                    w-10
                                    shrink-0
                                    place-items-center
                                    rounded-xl
                                    bg-emerald-50
                                    text-forest
                                "
                            >

                                <i
                                    class="
                                        fa-solid
                                        fa-boxes-stacked
                                    "
                                ></i>

                            </span>



                            <div>


                                <h3
                                    class="
                                        font-semibold
                                        text-slate-900
                                    "
                                >
                                    Collection products
                                </h3>


                                <p
                                    class="
                                        mt-0.5
                                        text-xs
                                        text-slate-500
                                    "
                                >
                                    Choose the products that should appear in this collection.
                                </p>


                            </div>


                        </div>



                        <span
                            class="
                                inline-flex
                                w-max
                                items-center
                                rounded-full
                                bg-slate-100
                                px-3
                                py-1
                                text-[11px]
                                font-semibold
                                text-slate-600
                            "
                        >

                            <span id="selected-products-count">

                                {{ $selectedProductIds->count() }}

                            </span>

                            &nbsp;selected

                        </span>


                    </div>



                    <div class="p-5 sm:p-6">


                        <div
                            class="
                                mb-4
                                flex
                                flex-col
                                gap-3

                                sm:flex-row
                                sm:items-center
                                sm:justify-between
                            "
                        >


                            {{-- PRODUCT SEARCH --}}
                            <div
                                class="
                                    relative
                                    w-full

                                    sm:max-w-md
                                "
                            >


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
                                    id="collection-product-search"
                                    class="
                                        admin-input
                                        !pl-10
                                        !pr-10
                                    "
                                    type="search"
                                    placeholder="Search products by name, SKU or material"
                                    autocomplete="off"
                                >



                                <button
                                    id="clear-collection-product-search"
                                    type="button"
                                    class="
                                        absolute
                                        inset-y-0
                                        right-0
                                        hidden
                                        w-10
                                        place-items-center
                                        text-slate-400
                                        transition

                                        hover:text-red-600
                                    "
                                    title="Clear product search"
                                    aria-label="Clear product search"
                                >

                                    <i
                                        class="
                                            fa-solid
                                            fa-circle-xmark
                                            text-sm
                                        "
                                    ></i>

                                </button>


                            </div>



                            <button
                                id="clear-selected-products"
                                type="button"
                                class="
                                    inline-flex
                                    items-center
                                    justify-center
                                    gap-2
                                    rounded-lg
                                    border
                                    border-slate-200
                                    bg-white
                                    px-3
                                    py-2.5
                                    text-xs
                                    font-semibold
                                    text-slate-600
                                    transition

                                    hover:border-red-200
                                    hover:bg-red-50
                                    hover:text-red-600
                                "
                            >

                                <i class="fa-solid fa-xmark"></i>

                                Clear selected

                            </button>


                        </div>



                        <div
                            id="product-picker-list"
                            class="
                                max-h-[520px]
                                overflow-y-auto
                                rounded-xl
                                border
                                border-slate-200
                                bg-slate-50/50
                            "
                        >


                            @forelse($products as $product)


                                <label
                                    class="
                                        collection-product-option
                                        flex
                                        cursor-pointer
                                        items-center
                                        gap-3
                                        border-b
                                        border-slate-100
                                        bg-white
                                        px-4
                                        py-3
                                        transition

                                        last:border-b-0

                                        hover:bg-orange-50/40
                                    "
                                    data-search="{{
                                        strtolower(
                                            $product->name
                                            .' '.
                                            $product->sku
                                            .' '.
                                            ($product->material ?? '')
                                        )
                                    }}"
                                >


                                    <input
                                        type="checkbox"
                                        name="products[]"
                                        value="{{ $product->id }}"
                                        class="
                                            collection-product-checkbox
                                            h-4
                                            w-4
                                            shrink-0
                                            rounded
                                            border-slate-300
                                            text-forest
                                            focus:ring-copper-500
                                        "
                                        @checked(
                                            $selectedProductIds
                                                ->contains(
                                                    $product->id
                                                )
                                        )
                                    >



                                    <div
                                        class="
                                            h-12
                                            w-10
                                            shrink-0
                                            overflow-hidden
                                            rounded-lg
                                            border
                                            border-slate-200
                                            bg-slate-100
                                        "
                                    >


                                        <img
                                            src="{{
                                                asset(
                                                    $product
                                                        ->primaryImage
                                                        ->path
                                                )
                                            }}"
                                            alt="{{ $product->name }}"
                                            class="
                                                h-full
                                                w-full
                                                object-cover
                                            "
                                        >


                                    </div>



                                    <div
                                        class="
                                            min-w-0
                                            flex-1
                                        "
                                    >


                                        <div
                                            class="
                                                flex
                                                flex-col
                                                gap-1

                                                sm:flex-row
                                                sm:items-center
                                                sm:justify-between
                                                sm:gap-3
                                            "
                                        >


                                            <div class="min-w-0">


                                                <p
                                                    class="
                                                        truncate
                                                        text-sm
                                                        font-semibold
                                                        text-slate-800
                                                    "
                                                >
                                                    {{ $product->name }}
                                                </p>



                                                <p
                                                    class="
                                                        mt-0.5
                                                        truncate
                                                        text-[11px]
                                                        text-slate-400
                                                    "
                                                >

                                                    SKU:
                                                    {{ $product->sku }}


                                                    @if($product->material)

                                                        ·
                                                        {{ $product->material }}

                                                    @endif


                                                </p>


                                            </div>



                                            <div
                                                class="
                                                    shrink-0
                                                    text-left

                                                    sm:text-right
                                                "
                                            >


                                                <p
                                                    class="
                                                        text-xs
                                                        font-semibold
                                                        text-slate-700
                                                    "
                                                >

                                                    ৳{{
                                                        number_format(
                                                            (float)
                                                            $product->price,
                                                            0
                                                        )
                                                    }}

                                                </p>



                                                <p
                                                    class="
                                                        mt-0.5
                                                        text-[10px]

                                                        {{
                                                            $product->stock
                                                            >
                                                            0

                                                                ? 'text-emerald-600'

                                                                : 'text-red-600'
                                                        }}
                                                    "
                                                >

                                                    Stock:
                                                    {{ $product->stock }}

                                                </p>


                                            </div>


                                        </div>


                                    </div>


                                </label>


                            @empty


                                <div
                                    class="
                                        p-10
                                        text-center
                                    "
                                >


                                    <i
                                        class="
                                            fa-solid
                                            fa-box-open
                                            text-xl
                                            text-slate-300
                                        "
                                    ></i>


                                    <p
                                        class="
                                            mt-3
                                            text-sm
                                            font-medium
                                            text-slate-600
                                        "
                                    >
                                        No products available
                                    </p>


                                    <p
                                        class="
                                            mt-1
                                            text-xs
                                            text-slate-400
                                        "
                                    >
                                        Create or activate products before adding them to a collection.
                                    </p>


                                </div>


                            @endforelse



                            <div
                                id="product-picker-empty-search"
                                class="
                                    hidden
                                    p-10
                                    text-center
                                "
                            >


                                <i
                                    class="
                                        fa-solid
                                        fa-magnifying-glass
                                        text-xl
                                        text-slate-300
                                    "
                                ></i>


                                <p
                                    class="
                                        mt-3
                                        text-sm
                                        font-medium
                                        text-slate-600
                                    "
                                >
                                    No matching products
                                </p>


                                <p
                                    class="
                                        mt-1
                                        text-xs
                                        text-slate-400
                                    "
                                >
                                    Try another product name, SKU or material.
                                </p>


                            </div>


                        </div>



                        @error('products')

                            <span
                                class="
                                    mt-2
                                    block
                                    text-xs
                                    text-red-600
                                "
                            >
                                {{ $message }}
                            </span>

                        @enderror



                        @error('products.*')

                            <span
                                class="
                                    mt-2
                                    block
                                    text-xs
                                    text-red-600
                                "
                            >
                                {{ $message }}
                            </span>

                        @enderror


                    </div>


                </section>


            </div>



            {{-- =================================================
                RIGHT SIDEBAR
            ================================================== --}}
            <aside
                class="
                    grid
                    gap-6

                    xl:sticky
                    xl:top-[94px]
                "
            >


                {{-- =============================================
                    PUBLISH
                ============================================== --}}
                <section class="admin-card overflow-hidden">


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


                                <h3
                                    class="
                                        font-semibold
                                        text-slate-900
                                    "
                                >
                                    Publish
                                </h3>


                                <p
                                    class="
                                        mt-0.5
                                        text-xs
                                        text-slate-500
                                    "
                                >
                                    Control storefront availability.
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

                                <i class="fa-solid fa-store"></i>

                            </span>


                        </div>


                    </div>



                    <div class="p-5">


                        <label
                            class="
                                flex
                                cursor-pointer
                                items-start
                                gap-3
                            "
                        >


                            <input
                                type="hidden"
                                name="is_active"
                                value="0"
                            >



                            <input
                                type="checkbox"
                                name="is_active"
                                value="1"
                                class="
                                    mt-1
                                    h-4
                                    w-4
                                    rounded
                                    border-slate-300
                                    text-forest
                                    focus:ring-copper-500
                                "
                                @checked(
                                    (bool)
                                    old(
                                        'is_active',
                                        $collection->exists
                                            ? $collection->is_active
                                            : true
                                    )
                                )
                            >



                            <span>


                                <span
                                    class="
                                        block
                                        text-sm
                                        font-medium
                                        text-slate-800
                                    "
                                >
                                    Active collection
                                </span>


                                <span
                                    class="
                                        mt-1
                                        block
                                        text-[11px]
                                        leading-4
                                        text-slate-500
                                    "
                                >
                                    Active collections can be opened on the storefront.
                                    Hidden collections remain manageable in admin.
                                </span>


                            </span>


                        </label>



                        <div
                            class="
                                mt-5
                                border-t
                                border-slate-100
                                pt-5
                            "
                        >


                            <button
                                type="submit"
                                class="
                                    admin-btn
                                    w-full
                                    !py-3
                                "
                            >

                                <i
                                    class="
                                        fa-solid

                                        {{
                                            $collection->exists
                                                ? 'fa-floppy-disk'
                                                : 'fa-plus'
                                        }}
                                    "
                                ></i>


                                {{
                                    $collection->exists
                                        ? 'Save changes'
                                        : 'Create collection'
                                }}

                            </button>


                        </div>


                    </div>


                </section>



                {{-- =============================================
                    GUIDE
                ============================================== --}}
                <section class="admin-card p-5">


                    <div class="flex items-center gap-3">


                        <span
                            class="
                                grid
                                h-9
                                w-9
                                place-items-center
                                rounded-xl
                                bg-slate-100
                                text-slate-600
                            "
                        >

                            <i
                                class="
                                    fa-solid
                                    fa-circle-info
                                "
                            ></i>

                        </span>



                        <div>


                            <h3
                                class="
                                    text-sm
                                    font-semibold
                                    text-slate-900
                                "
                            >
                                Collection guide
                            </h3>


                            <p
                                class="
                                    mt-0.5
                                    text-xs
                                    text-slate-500
                                "
                            >
                                How AATCHALA collections behave.
                            </p>


                        </div>


                    </div>



                    <div
                        class="
                            mt-4
                            space-y-3
                            text-xs
                            leading-5
                            text-slate-600
                        "
                    >


                        <div
                            class="
                                rounded-xl
                                bg-slate-50
                                p-3
                            "
                        >

                            <strong class="text-slate-800">
                                Products:
                            </strong>

                            a product can belong to more than one collection.

                        </div>



                        <div
                            class="
                                rounded-xl
                                bg-slate-50
                                p-3
                            "
                        >

                            <strong class="text-slate-800">
                                Deleting a collection:
                            </strong>

                            removes only the collection and its product links.
                            Products stay intact.

                        </div>



                        <div
                            class="
                                rounded-xl
                                bg-slate-50
                                p-3
                            "
                        >

                            <strong class="text-slate-800">
                                Cover image:
                            </strong>

                            used by the current storefront collection card
                            and collection hero.

                        </div>


                    </div>


                </section>


            </aside>


        </div>



        {{-- =====================================================
            BOTTOM ACTION BAR
        ====================================================== --}}
        <div
            class="
                mt-6
                flex
                flex-col-reverse
                gap-3
                rounded-xl
                border
                border-slate-200
                bg-white
                p-4
                shadow-sm

                sm:flex-row
                sm:items-center
                sm:justify-between
            "
        >


            <p
                class="
                    text-xs
                    text-slate-500
                "
            >

                <i
                    class="
                        fa-solid
                        fa-circle-info
                        mr-1
                        text-slate-400
                    "
                ></i>

                Required fields are marked with an asterisk (*).

            </p>



            <div class="flex gap-2">


                <a
                    href="{{ route('admin.collections.index') }}"
                    class="
                        admin-btn
                        admin-btn-light
                    "
                >
                    Cancel
                </a>



                <button
                    type="submit"
                    class="admin-btn"
                >

                    <i
                        class="
                            fa-solid

                            {{
                                $collection->exists
                                    ? 'fa-floppy-disk'
                                    : 'fa-plus'
                            }}
                        "
                    ></i>


                    {{
                        $collection->exists
                            ? 'Save changes'
                            : 'Create collection'
                    }}

                </button>


            </div>


        </div>


    </form>


</div>


@endsection



@push('scripts')

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {


        /* =========================================================
           AUTO SLUG
        ========================================================== */

        const collectionExists =
            @json($collection->exists);


        const titleInput =
            document.getElementById(
                'collection-title-input'
            );


        const slugInput =
            document.getElementById(
                'collection-slug-input'
            );


        let slugManuallyChanged =

            collectionExists

            ||

            Boolean(
                slugInput?.value
            );



        function makeSlug(
            value
        ) {

            return value

                .toString()

                .normalize(
                    'NFKD'
                )

                .replace(
                    /[\u0300-\u036f]/g,
                    ''
                )

                .toLowerCase()

                .trim()

                .replace(
                    /[^a-z0-9]+/g,
                    '-'
                )

                .replace(
                    /^-+|-+$/g,
                    ''
                );

        }



        slugInput?.addEventListener(
            'input',
            function () {

                slugManuallyChanged =
                    this.value.trim()
                    !==
                    '';

            }
        );



        titleInput?.addEventListener(
            'input',
            function () {

                if (
                    !slugManuallyChanged
                    &&
                    slugInput
                ) {

                    slugInput.value =
                        makeSlug(
                            this.value
                        );

                }

            }
        );



        /* =========================================================
           DESCRIPTION COUNTER
        ========================================================== */

        const descriptionInput =
            document.getElementById(
                'collection-description-input'
            );


        const descriptionCounter =
            document.getElementById(
                'collection-description-count'
            );



        function updateDescriptionCounter()
        {

            if (
                !descriptionInput
                ||
                !descriptionCounter
            ) {

                return;

            }


            descriptionCounter.textContent =

                `${
                    descriptionInput.value.length
                } / 5000`;

        }



        descriptionInput?.addEventListener(
            'input',
            updateDescriptionCounter
        );


        updateDescriptionCounter();



        /* =========================================================
           LIVE COVER IMAGE PREVIEW
        ========================================================== */

        const imageInput =
            document.getElementById(
                'collection-image-input'
            );


        const imagePreview =
            document.getElementById(
                'collection-image-preview'
            );


        const imageEmpty =
            document.getElementById(
                'collection-image-empty'
            );


        const imageBadge =
            document.getElementById(
                'collection-image-new-badge'
            );


        const imageFilename =
            document.getElementById(
                'collection-image-filename'
            );


        let collectionImageObjectUrl =
            null;



        imageInput?.addEventListener(
            'change',
            function () {


                const file =
                    this.files?.[0];


                if (!file) {

                    return;

                }


                if (
                    !file.type.startsWith(
                        'image/'
                    )
                ) {

                    this.value =
                        '';

                    return;

                }


                if (
                    collectionImageObjectUrl
                ) {

                    URL.revokeObjectURL(
                        collectionImageObjectUrl
                    );

                }


                collectionImageObjectUrl =
                    URL.createObjectURL(
                        file
                    );


                if (imagePreview) {

                    imagePreview.src =
                        collectionImageObjectUrl;


                    imagePreview
                        .classList
                        .remove(
                            'hidden'
                        );

                }


                imageEmpty
                    ?.classList
                    .add(
                        'hidden'
                    );


                imageBadge
                    ?.classList
                    .remove(
                        'hidden'
                    );


                if (imageFilename) {

                    imageFilename.textContent =
                        `Selected: ${file.name}`;


                    imageFilename
                        .classList
                        .remove(
                            'hidden'
                        );

                }


            }
        );



        window.addEventListener(
            'beforeunload',
            function () {


                if (
                    collectionImageObjectUrl
                ) {

                    URL.revokeObjectURL(
                        collectionImageObjectUrl
                    );

                }


            }
        );



        /* =========================================================
           PRODUCT PICKER
        ========================================================== */

        const productSearch =
            document.getElementById(
                'collection-product-search'
            );


        const clearProductSearch =
            document.getElementById(
                'clear-collection-product-search'
            );


        const clearSelectedProducts =
            document.getElementById(
                'clear-selected-products'
            );


        const productOptions =
            Array.from(
                document.querySelectorAll(
                    '.collection-product-option'
                )
            );


        const productCheckboxes =
            Array.from(
                document.querySelectorAll(
                    '.collection-product-checkbox'
                )
            );


        const selectedCount =
            document.getElementById(
                'selected-products-count'
            );


        const emptySearch =
            document.getElementById(
                'product-picker-empty-search'
            );



        function updateSelectedCount()
        {

            const count =
                productCheckboxes
                    .filter(
                        function (checkbox) {

                            return checkbox.checked;

                        }
                    )
                    .length;


            if (selectedCount) {

                selectedCount.textContent =
                    count;

            }

        }



        function filterProducts()
        {

            const term =
                (
                    productSearch?.value
                    ||
                    ''
                )
                    .trim()
                    .toLowerCase();


            let visibleCount =
                0;



            productOptions.forEach(
                function (option) {


                    const haystack =
                        option.dataset.search
                        ||
                        '';


                    const visible =

                        term === ''

                        ||

                        haystack.includes(
                            term
                        );


                    option.classList.toggle(
                        'hidden',
                        !visible
                    );


                    if (visible) {

                        visibleCount +=
                            1;

                    }


                }
            );



            if (clearProductSearch) {


                clearProductSearch
                    .classList
                    .toggle(
                        'hidden',
                        term === ''
                    );


                clearProductSearch
                    .classList
                    .toggle(
                        'grid',
                        term !== ''
                    );


            }



            if (emptySearch) {


                emptySearch
                    .classList
                    .toggle(

                        'hidden',

                        !(
                            term !== ''
                            &&
                            visibleCount === 0
                        )

                    );


            }

        }



        productSearch?.addEventListener(
            'input',
            filterProducts
        );



        clearProductSearch?.addEventListener(
            'click',
            function () {


                if (!productSearch) {

                    return;

                }


                productSearch.value =
                    '';


                filterProducts();


                productSearch.focus();


            }
        );



        productCheckboxes.forEach(
            function (checkbox) {


                checkbox.addEventListener(
                    'change',
                    updateSelectedCount
                );


            }
        );



        clearSelectedProducts?.addEventListener(
            'click',
            function () {


                productCheckboxes.forEach(
                    function (checkbox) {

                        checkbox.checked =
                            false;

                    }
                );


                updateSelectedCount();


            }
        );



        updateSelectedCount();


        filterProducts();


    }
);

</script>

@endpush