@extends('admin.layouts.app')

@section('title', $product->exists ? 'Edit Product' : 'New Product')
@section('page_title', $product->exists ? 'Edit Product' : 'Create Product')

@section('content')

<div class="max-w-screen-2xl mx-auto">

    {{-- =========================================================
        PAGE HEADING
    ========================================================== --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">

        <div>

            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">

                <a
                    href="{{ route('admin.products.index') }}"
                    class="hover:text-copper-500 transition"
                >
                    Products
                </a>

                <i class="fa-solid fa-chevron-right text-[9px]"></i>

                <span>
                    {{ $product->exists ? 'Edit' : 'Create' }}
                </span>

            </div>


            <h2 class="text-2xl font-semibold text-slate-900">

                {{ $product->exists
                    ? $product->name
                    : 'Add a new product'
                }}

            </h2>


            <p class="text-sm text-slate-500 mt-1">

                {{ $product->exists
                    ? 'Update product details, pricing, images and storefront visibility.'
                    : 'Create a complete product listing for the SARAI storefront.'
                }}

            </p>

        </div>


        <div class="flex flex-wrap items-center gap-2">

            <a
                href="{{ route('admin.products.index') }}"
                class="admin-btn admin-btn-light"
            >

                <i class="fa-solid fa-arrow-left"></i>

                Back to products

            </a>


            @if($product->exists)

                <a
                    href="{{ route('products.show', $product) }}"
                    target="_blank"
                    class="admin-btn admin-btn-light"
                >

                    <i class="fa-solid fa-arrow-up-right-from-square"></i>

                    View product

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

                <i class="fa-solid fa-circle-exclamation mt-0.5"></i>

                <div>

                    <p class="font-semibold text-sm">
                        Please correct the highlighted fields.
                    </p>


                    <ul class="mt-2 list-disc pl-5 text-xs space-y-1">

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
        MAIN PRODUCT FORM
    ========================================================== --}}
    <form
        id="product-form"
        action="{{
            $product->exists
                ? route('admin.products.update', $product)
                : route('admin.products.store')
        }}"
        method="POST"
        enctype="multipart/form-data"
    >

        @csrf


        @if($product->exists)

            @method('PUT')

        @endif



        <div
            class="
                grid
                gap-6
                xl:grid-cols-[minmax(0,1fr)_350px]
                xl:items-start
            "
        >


            {{-- =================================================
                LEFT CONTENT
            ================================================== --}}
            <div class="grid gap-6 min-w-0">



                {{-- =============================================
                    PRODUCT INFORMATION
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

                            <i class="fa-solid fa-box-open"></i>

                        </span>


                        <div>

                            <h3 class="font-semibold text-slate-900">
                                Product information
                            </h3>

                            <p class="text-xs text-slate-500 mt-0.5">
                                Basic information customers will see on the storefront.
                            </p>

                        </div>

                    </div>



                    <div class="p-5 sm:p-6">

                        <div class="grid gap-5 md:grid-cols-2">


                            {{-- NAME --}}
                            <label class="md:col-span-2">

                                <span class="admin-label">

                                    Name

                                    <span class="text-red-500">
                                        *
                                    </span>

                                </span>


                                <input
                                    id="product-name"
                                    class="
                                        admin-input
                                        @error('name')
                                            !border-red-400
                                        @enderror
                                    "
                                    name="name"
                                    value="{{ old('name', $product->name) }}"
                                    maxlength="191"
                                    autocomplete="off"
                                    required
                                >


                                @error('name')

                                    <span class="error">
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

                                        <i class="fa-solid fa-link text-xs"></i>

                                    </span>


                                    <input
                                        id="product-slug"
                                        class="
                                            admin-input
                                            !pl-10
                                            @error('slug')
                                                !border-red-400
                                            @enderror
                                        "
                                        name="slug"
                                        value="{{ old('slug', $product->slug) }}"
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
                                    Used in the product URL.
                                </span>


                                @error('slug')

                                    <span class="error">
                                        {{ $message }}
                                    </span>

                                @enderror

                            </label>



                            {{-- SKU --}}
                            <label>

                                <span class="admin-label">

                                    SKU

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

                                        <i class="fa-solid fa-barcode text-xs"></i>

                                    </span>


                                    <input
                                        class="
                                            admin-input
                                            !pl-10
                                            @error('sku')
                                                !border-red-400
                                            @enderror
                                        "
                                        name="sku"
                                        value="{{ old('sku', $product->sku) }}"
                                        maxlength="100"
                                        autocomplete="off"
                                        required
                                    >

                                </div>


                                @error('sku')

                                    <span class="error">
                                        {{ $message }}
                                    </span>

                                @enderror

                            </label>



                            {{-- SHORT DESCRIPTION --}}
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
                                        Short description
                                    </span>


                                    <span
                                        id="short-description-count"
                                        class="text-[10px] text-slate-400"
                                    >
                                        0 / 500
                                    </span>

                                </div>


                                <textarea
                                    id="short-description"
                                    class="
                                        admin-input
                                        min-h-[92px]
                                        resize-y
                                        @error('short_description')
                                            !border-red-400
                                        @enderror
                                    "
                                    name="short_description"
                                    rows="3"
                                    maxlength="500"
                                    placeholder="A short product summary for cards and previews."
                                >{{ old('short_description', $product->short_description) }}</textarea>


                                @error('short_description')

                                    <span class="error">
                                        {{ $message }}
                                    </span>

                                @enderror

                            </label>



                            {{-- FULL DESCRIPTION --}}
                            <label class="md:col-span-2">

                                <span class="admin-label">
                                    Full description
                                </span>


                                <textarea
                                    class="
                                        admin-input
                                        min-h-[220px]
                                        resize-y
                                        @error('description')
                                            !border-red-400
                                        @enderror
                                    "
                                    name="description"
                                    rows="9"
                                    placeholder="Describe materials, craftsmanship, care instructions and other important details."
                                >{{ old('description', $product->description) }}</textarea>


                                @error('description')

                                    <span class="error">
                                        {{ $message }}
                                    </span>

                                @enderror

                            </label>

                        </div>

                    </div>

                </section>



                {{-- =============================================
                    PRICE & INVENTORY
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
                                bg-emerald-50
                                text-forest
                            "
                        >

                            <i class="fa-solid fa-tags"></i>

                        </span>


                        <div>

                            <h3 class="font-semibold text-slate-900">
                                Price & inventory
                            </h3>

                            <p class="text-xs text-slate-500 mt-0.5">
                                Control pricing, stock levels and product weight.
                            </p>

                        </div>

                    </div>



                    <div class="p-5 sm:p-6">

                        <div
                            class="
                                grid
                                gap-5
                                md:grid-cols-2
                                xl:grid-cols-3
                            "
                        >


                            {{-- SELLING PRICE --}}
                            <label>

                                <span class="admin-label">

                                    Selling price

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
                                            text-sm
                                            font-medium
                                            text-slate-500
                                        "
                                    >
                                        ৳
                                    </span>


                                    <input
                                        class="
                                            admin-input
                                            !pl-10
                                            @error('price')
                                                !border-red-400
                                            @enderror
                                        "
                                        type="number"
                                        min="0"
                                        step="0.01"
                                        name="price"
                                        value="{{ old('price', $product->price) }}"
                                        required
                                    >

                                </div>


                                @error('price')

                                    <span class="error">
                                        {{ $message }}
                                    </span>

                                @enderror

                            </label>



                            {{-- COMPARE PRICE --}}
                            <label>

                                <span class="admin-label">
                                    Compare price
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
                                            text-sm
                                            font-medium
                                            text-slate-500
                                        "
                                    >
                                        ৳
                                    </span>


                                    <input
                                        class="
                                            admin-input
                                            !pl-10
                                            @error('compare_price')
                                                !border-red-400
                                            @enderror
                                        "
                                        type="number"
                                        min="0"
                                        step="0.01"
                                        name="compare_price"
                                        value="{{ old('compare_price', $product->compare_price) }}"
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
                                    Must be equal to or higher than selling price.
                                </span>


                                @error('compare_price')

                                    <span class="error">
                                        {{ $message }}
                                    </span>

                                @enderror

                            </label>



                            {{-- COST PRICE --}}
                            <label>

                                <span class="admin-label">
                                    Cost price
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
                                            text-sm
                                            font-medium
                                            text-slate-500
                                        "
                                    >
                                        ৳
                                    </span>


                                    <input
                                        class="
                                            admin-input
                                            !pl-10
                                            @error('cost_price')
                                                !border-red-400
                                            @enderror
                                        "
                                        type="number"
                                        min="0"
                                        step="0.01"
                                        name="cost_price"
                                        value="{{ old('cost_price', $product->cost_price) }}"
                                    >

                                </div>


                                @error('cost_price')

                                    <span class="error">
                                        {{ $message }}
                                    </span>

                                @enderror

                            </label>



                            {{-- STOCK --}}
                            <label>

                                <span class="admin-label">

                                    Stock

                                    <span class="text-red-500">
                                        *
                                    </span>

                                </span>


                                <input
                                    class="
                                        admin-input
                                        @error('stock')
                                            !border-red-400
                                        @enderror
                                    "
                                    type="number"
                                    min="0"
                                    step="1"
                                    name="stock"
                                    value="{{ old('stock', $product->stock ?? 0) }}"
                                    required
                                >


                                @error('stock')

                                    <span class="error">
                                        {{ $message }}
                                    </span>

                                @enderror

                            </label>



                            {{-- LOW STOCK --}}
                            <label>

                                <span class="admin-label">

                                    Low stock warning

                                    <span class="text-red-500">
                                        *
                                    </span>

                                </span>


                                <input
                                    class="
                                        admin-input
                                        @error('low_stock_threshold')
                                            !border-red-400
                                        @enderror
                                    "
                                    type="number"
                                    min="0"
                                    step="1"
                                    name="low_stock_threshold"
                                    value="{{
                                        old(
                                            'low_stock_threshold',
                                            $product->low_stock_threshold ?? 5
                                        )
                                    }}"
                                    required
                                >


                                @error('low_stock_threshold')

                                    <span class="error">
                                        {{ $message }}
                                    </span>

                                @enderror

                            </label>



                            {{-- WEIGHT --}}
                            <label>

                                <span class="admin-label">
                                    Weight (kg)
                                </span>


                                <input
                                    class="
                                        admin-input
                                        @error('weight')
                                            !border-red-400
                                        @enderror
                                    "
                                    type="number"
                                    min="0"
                                    step="0.01"
                                    name="weight"
                                    value="{{ old('weight', $product->weight) }}"
                                >


                                @error('weight')

                                    <span class="error">
                                        {{ $message }}
                                    </span>

                                @enderror

                            </label>

                        </div>

                    </div>

                </section>



                {{-- =============================================
                    PRODUCT IMAGES
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
                                    bg-violet-50
                                    text-violet-600
                                "
                            >

                                <i class="fa-regular fa-images"></i>

                            </span>


                            <div>

                                <h3 class="font-semibold text-slate-900">
                                    Product images
                                </h3>

                                <p class="text-xs text-slate-500 mt-0.5">
                                    Upload up to 6 JPG, PNG or WebP images, maximum 4 MB each.
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

                            <span id="image-count">
                                {{ $product->exists ? $product->images->count() : 0 }}
                            </span>

                            /6 images

                        </span>

                    </div>



                    <div class="p-5 sm:p-6">


                        {{-- EXISTING IMAGES --}}
                        @if(
                            $product->exists
                            &&
                            $product->images->count()
                        )

                            <div
                                class="
                                    mb-5
                                    grid
                                    grid-cols-2
                                    gap-3
                                    sm:grid-cols-3
                                    lg:grid-cols-4
                                    xl:grid-cols-5
                                "
                            >

                                @foreach($product->images as $image)

                                    <div
                                        class="
                                            group
                                            relative
                                            overflow-hidden
                                            rounded-xl
                                            border
                                            border-slate-200
                                            bg-slate-50
                                        "
                                    >

                                        <img
                                            class="
                                                aspect-[4/5]
                                                w-full
                                                object-cover
                                                transition
                                                duration-300
                                                group-hover:scale-[1.03]
                                            "
                                            src="{{ asset($image->path) }}"
                                            alt="{{ $image->alt_text ?: $product->name }}"
                                        >


                                        <div
                                            class="
                                                absolute
                                                inset-x-0
                                                bottom-0
                                                flex
                                                items-center
                                                justify-between
                                                gap-2
                                                bg-gradient-to-t
                                                from-black/70
                                                to-transparent
                                                p-2
                                                pt-8
                                                text-white
                                            "
                                        >

                                            @if($image->is_primary)

                                                <span
                                                    class="
                                                        rounded-full
                                                        bg-white/90
                                                        px-2
                                                        py-1
                                                        text-[9px]
                                                        font-bold
                                                        uppercase
                                                        tracking-wider
                                                        text-slate-900
                                                    "
                                                >
                                                    Primary
                                                </span>

                                            @else

                                                <span></span>

                                            @endif


                                            <button
                                                type="submit"
                                                form="delete-image-{{ $image->id }}"
                                                onclick="return confirm('Remove this product image?')"
                                                class="
                                                    grid
                                                    h-8
                                                    w-8
                                                    place-items-center
                                                    rounded-full
                                                    bg-red-600
                                                    text-white
                                                    shadow
                                                    transition
                                                    hover:bg-red-700
                                                "
                                                title="Remove image"
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

                                    </div>

                                @endforeach

                            </div>

                        @endif



                        {{-- IMAGE UPLOAD --}}
                        <label class="block">

                            <span class="admin-label">
                                Upload product images
                            </span>


                            <div
                                class="
                                    rounded-xl
                                    border-2
                                    border-dashed
                                    border-slate-200
                                    bg-slate-50
                                    p-5
                                    transition
                                    hover:border-copper-300
                                    hover:bg-orange-50/30
                                "
                            >

                                <div
                                    class="
                                        flex
                                        flex-col
                                        items-center
                                        justify-center
                                        text-center
                                    "
                                >

                                    <span
                                        class="
                                            mb-3
                                            grid
                                            h-12
                                            w-12
                                            place-items-center
                                            rounded-full
                                            bg-white
                                            text-copper-500
                                            shadow-sm
                                        "
                                    >

                                        <i class="fa-solid fa-cloud-arrow-up"></i>

                                    </span>


                                    <p class="text-sm font-medium text-slate-800">
                                        Choose product images
                                    </p>


                                    <p class="mt-1 text-xs text-slate-500">
                                        You can select multiple images at once.
                                    </p>


                                    <input
                                        id="product-images"
                                        class="
                                            mt-4
                                            block
                                            w-full
                                            max-w-xl
                                            cursor-pointer
                                            rounded-lg
                                            border
                                            border-slate-200
                                            bg-white
                                            text-xs
                                            text-slate-500

                                            file:mr-4
                                            file:border-0
                                            file:bg-forest
                                            file:px-4
                                            file:py-2.5
                                            file:text-xs
                                            file:font-semibold
                                            file:text-white

                                            hover:file:bg-copper-500

                                            @error('images')
                                                !border-red-400
                                            @enderror

                                            @error('images.*')
                                                !border-red-400
                                            @enderror
                                        "
                                        type="file"
                                        name="images[]"
                                        accept="image/jpeg,image/png,image/webp"
                                        multiple
                                        data-existing-images="{{
                                            $product->exists
                                                ? $product->images->count()
                                                : 0
                                        }}"
                                    >

                                </div>

                            </div>

                        </label>


                        <p
                            id="image-limit-message"
                            class="
                                mt-2
                                hidden
                                text-xs
                                font-medium
                                text-red-600
                            "
                        ></p>


                        @error('images')

                            <span class="error">
                                {{ $message }}
                            </span>

                        @enderror


                        @error('images.*')

                            <span class="error">
                                {{ $message }}
                            </span>

                        @enderror


                        <div
                            id="new-image-preview"
                            class="
                                mt-4
                                hidden
                                grid-cols-2
                                gap-3
                                sm:grid-cols-3
                                lg:grid-cols-4
                                xl:grid-cols-5
                            "
                        ></div>

                    </div>

                </section>



                {{-- =============================================
                    SEO
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
                                bg-sky-50
                                text-sky-600
                            "
                        >

                            <i class="fa-solid fa-magnifying-glass"></i>

                        </span>


                        <div>

                            <h3 class="font-semibold text-slate-900">
                                Search engine optimization
                            </h3>

                            <p class="text-xs text-slate-500 mt-0.5">
                                Optional metadata for search engines and social sharing.
                            </p>

                        </div>

                    </div>



                    <div class="p-5 sm:p-6">

                        <div class="grid gap-5">


                            {{-- META TITLE --}}
                            <label>

                                <div
                                    class="
                                        flex
                                        items-center
                                        justify-between
                                        gap-3
                                    "
                                >

                                    <span class="admin-label">
                                        Meta title
                                    </span>


                                    <span
                                        id="meta-title-count"
                                        class="text-[10px] text-slate-400"
                                    >
                                        0 / 70
                                    </span>

                                </div>


                                <input
                                    id="meta-title"
                                    class="
                                        admin-input
                                        @error('meta_title')
                                            !border-red-400
                                        @enderror
                                    "
                                    maxlength="70"
                                    name="meta_title"
                                    value="{{ old('meta_title', $product->meta_title) }}"
                                >


                                @error('meta_title')

                                    <span class="error">
                                        {{ $message }}
                                    </span>

                                @enderror

                            </label>



                            {{-- META DESCRIPTION --}}
                            <label>

                                <div
                                    class="
                                        flex
                                        items-center
                                        justify-between
                                        gap-3
                                    "
                                >

                                    <span class="admin-label">
                                        Meta description
                                    </span>


                                    <span
                                        id="meta-description-count"
                                        class="text-[10px] text-slate-400"
                                    >
                                        0 / 170
                                    </span>

                                </div>


                                <textarea
                                    id="meta-description"
                                    class="
                                        admin-input
                                        min-h-[100px]
                                        resize-y
                                        @error('meta_description')
                                            !border-red-400
                                        @enderror
                                    "
                                    maxlength="170"
                                    name="meta_description"
                                    rows="4"
                                >{{ old('meta_description', $product->meta_description) }}</textarea>


                                @error('meta_description')

                                    <span class="error">
                                        {{ $message }}
                                    </span>

                                @enderror

                            </label>



                            {{-- CANONICAL URL --}}
                            <label>

                                <span class="admin-label">
                                    Canonical URL
                                </span>


                                <input
                                    class="
                                        admin-input
                                        @error('canonical_url')
                                            !border-red-400
                                        @enderror
                                    "
                                    type="url"
                                    name="canonical_url"
                                    value="{{ old('canonical_url', $product->canonical_url) }}"
                                    placeholder="https://example.com/product/product-slug"
                                >


                                @error('canonical_url')

                                    <span class="error">
                                        {{ $message }}
                                    </span>

                                @enderror

                            </label>

                        </div>

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

                                <h3 class="font-semibold text-slate-900">
                                    Publish
                                </h3>

                                <p class="mt-0.5 text-xs text-slate-500">
                                    Choose how this product appears.
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

                        @php

                            $publishOptions = [

                                'is_active' => [
                                    'Visible in store',
                                    'Customers can browse and purchase this product.',
                                    'fa-eye'
                                ],

                                'is_featured' => [
                                    'Featured',
                                    'Highlight the product in featured areas.',
                                    'fa-star'
                                ],

                                'is_new' => [
                                    'New arrival',
                                    'Show the new-arrival label where supported.',
                                    'fa-bolt'
                                ],

                                'is_bestseller' => [
                                    'Bestseller',
                                    'Show the bestseller label where supported.',
                                    'fa-fire'
                                ],

                                'is_limited' => [
                                    'Limited edition',
                                    'Mark this item as a limited release.',
                                    'fa-gem'
                                ],

                            ];

                        @endphp



                        <div class="divide-y divide-slate-100">

                            @foreach($publishOptions as $name => $option)

                                @php
                                    $label = $option[0];
                                    $help = $option[1];
                                    $icon = $option[2];

                                    $defaultValue = $product->exists
                                        ? (bool) $product->{$name}
                                        : ($name === 'is_active');
                                @endphp


                                <label
                                    class="
                                        flex
                                        cursor-pointer
                                        items-start
                                        gap-3
                                        py-3
                                        first:pt-0
                                        last:pb-0
                                    "
                                >

                                    <input
                                        type="hidden"
                                        name="{{ $name }}"
                                        value="0"
                                    >


                                    <input
                                        type="checkbox"
                                        name="{{ $name }}"
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
                                            (bool) old(
                                                $name,
                                                $defaultValue
                                            )
                                        )
                                    >


                                    <span class="min-w-0 flex-1">

                                        <span
                                            class="
                                                flex
                                                items-center
                                                gap-2
                                                text-sm
                                                font-medium
                                                text-slate-800
                                            "
                                        >

                                            <i
                                                class="
                                                    fa-solid
                                                    {{ $icon }}
                                                    w-4
                                                    text-xs
                                                    text-slate-400
                                                "
                                            ></i>

                                            {{ $label }}

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
                                            {{ $help }}
                                        </span>

                                    </span>

                                </label>

                            @endforeach

                        </div>



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
                                class="admin-btn w-full !py-3"
                            >

                                <i
                                    class="
                                        fa-solid
                                        {{
                                            $product->exists
                                                ? 'fa-floppy-disk'
                                                : 'fa-plus'
                                        }}
                                    "
                                ></i>


                                {{
                                    $product->exists
                                        ? 'Save changes'
                                        : 'Create product'
                                }}

                            </button>

                        </div>

                    </div>

                </section>



                {{-- =============================================
                    ORGANIZATION
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

                                <h3 class="font-semibold text-slate-900">
                                    Organization
                                </h3>

                                <p class="mt-0.5 text-xs text-slate-500">
                                    Categorize and describe the item.
                                </p>

                            </div>


                            <span
                                class="
                                    grid
                                    h-9
                                    w-9
                                    place-items-center
                                    rounded-xl
                                    bg-orange-50
                                    text-copper-500
                                "
                            >

                                <i class="fa-solid fa-layer-group"></i>

                            </span>

                        </div>

                    </div>



                    <div class="grid gap-5 p-5">


                        {{-- CATEGORY --}}
                        <label>

                            <span class="admin-label">

                                Category

                                <span class="text-red-500">
                                    *
                                </span>

                            </span>


                            <select
                                id="category-id"
                                class="
                                    admin-input
                                    @error('category_id')
                                        !border-red-400
                                    @enderror
                                "
                                name="category_id"
                                required
                            >

                                <option value="">
                                    Select category
                                </option>


                                @foreach($categories as $cat)

                                    <option
                                        value="{{ $cat->id }}"
                                        @selected(
                                            (string) old(
                                                'category_id',
                                                $product->category_id
                                            )
                                            ===
                                            (string) $cat->id
                                        )
                                    >
                                        {{ $cat->name }}
                                    </option>

                                @endforeach

                            </select>


                            @error('category_id')

                                <span class="error">
                                    {{ $message }}
                                </span>

                            @enderror

                        </label>



                        {{-- SUBCATEGORY --}}
                        <label>

                            <span class="admin-label">
                                Subcategory
                            </span>


                            <select
                                id="subcategory-id"
                                class="
                                    admin-input
                                    @error('subcategory_id')
                                        !border-red-400
                                    @enderror
                                "
                                name="subcategory_id"
                            >

                                <option value="">
                                    None
                                </option>


                                @foreach($categories as $cat)

                                    @foreach($cat->children as $child)

                                        <option
                                            value="{{ $child->id }}"
                                            data-parent="{{ $cat->id }}"
                                            @selected(
                                                (string) old(
                                                    'subcategory_id',
                                                    $product->subcategory_id
                                                )
                                                ===
                                                (string) $child->id
                                            )
                                        >
                                            {{ $child->name }}
                                        </option>

                                    @endforeach

                                @endforeach

                            </select>


                            <span
                                class="
                                    mt-1
                                    block
                                    text-[11px]
                                    text-slate-400
                                "
                            >
                                Only subcategories under the selected category are shown.
                            </span>


                            @error('subcategory_id')

                                <span class="error">
                                    {{ $message }}
                                </span>

                            @enderror

                        </label>



                        {{-- MATERIAL --}}
                        <label>

                            <span class="admin-label">
                                Material
                            </span>


                            <input
                                class="
                                    admin-input
                                    @error('material')
                                        !border-red-400
                                    @enderror
                                "
                                name="material"
                                value="{{ old('material', $product->material) }}"
                                maxlength="191"
                                placeholder="e.g. Cotton, Jute, Clay"
                            >


                            @error('material')

                                <span class="error">
                                    {{ $message }}
                                </span>

                            @enderror

                        </label>



                        {{-- COLORS --}}
                        <label>

                            <span class="admin-label">
                                Colours
                            </span>


                            <input
                                class="
                                    admin-input
                                    @error('colors')
                                        !border-red-400
                                    @enderror
                                "
                                name="colors"
                                value="{{
                                    old(
                                        'colors',
                                        collect($product->colors)
                                            ->pluck('name')
                                            ->implode(', ')
                                    )
                                }}"
                                placeholder="Indigo, Ivory, Gold"
                            >


                            <span
                                class="
                                    mt-1
                                    block
                                    text-[11px]
                                    text-slate-400
                                "
                            >
                                Separate values with commas.
                            </span>


                            @error('colors')

                                <span class="error">
                                    {{ $message }}
                                </span>

                            @enderror

                        </label>



                        {{-- SIZES --}}
                        <label>

                            <span class="admin-label">
                                Sizes
                            </span>


                            <input
                                class="
                                    admin-input
                                    @error('sizes')
                                        !border-red-400
                                    @enderror
                                "
                                name="sizes"
                                value="{{
                                    old(
                                        'sizes',
                                        collect($product->sizes)
                                            ->implode(', ')
                                    )
                                }}"
                                placeholder="S, M, L, XL"
                            >


                            <span
                                class="
                                    mt-1
                                    block
                                    text-[11px]
                                    text-slate-400
                                "
                            >
                                Separate values with commas.
                            </span>


                            @error('sizes')

                                <span class="error">
                                    {{ $message }}
                                </span>

                            @enderror

                        </label>



                        {{-- TAGS --}}
                        <label>

                            <span class="admin-label">
                                Tags
                            </span>


                            <input
                                class="
                                    admin-input
                                    @error('tags')
                                        !border-red-400
                                    @enderror
                                "
                                name="tags"
                                value="{{
                                    old(
                                        'tags',
                                        collect($product->tags)
                                            ->implode(', ')
                                    )
                                }}"
                                placeholder="handmade, artisan, gift"
                            >


                            <span
                                class="
                                    mt-1
                                    block
                                    text-[11px]
                                    text-slate-400
                                "
                            >
                                Separate values with commas.
                            </span>


                            @error('tags')

                                <span class="error">
                                    {{ $message }}
                                </span>

                            @enderror

                        </label>

                    </div>

                </section>



                {{-- =============================================
                    DELETE PRODUCT
                ============================================== --}}
                @if($product->exists)

                    <section
                        class="
                            rounded-xl
                            border
                            border-red-200
                            bg-red-50
                            p-5
                        "
                    >

                        <div class="flex items-start gap-3">

                            <span
                                class="
                                    grid
                                    h-9
                                    w-9
                                    shrink-0
                                    place-items-center
                                    rounded-lg
                                    bg-white
                                    text-red-600
                                    shadow-sm
                                "
                            >

                                <i
                                    class="
                                        fa-solid
                                        fa-triangle-exclamation
                                    "
                                ></i>

                            </span>


                            <div>

                                <h3
                                    class="
                                        text-sm
                                        font-semibold
                                        text-red-900
                                    "
                                >
                                    Danger zone
                                </h3>


                                <p
                                    class="
                                        mt-1
                                        text-xs
                                        leading-5
                                        text-red-700
                                    "
                                >
                                    Deleting moves this product to trash using the existing soft-delete behavior.
                                </p>


                                <button
                                    type="submit"
                                    form="delete-product-form"
                                    onclick="return confirm('Move this product to trash?')"
                                    class="
                                        mt-3
                                        inline-flex
                                        items-center
                                        gap-2
                                        rounded-lg
                                        bg-red-700
                                        px-3
                                        py-2
                                        text-xs
                                        font-semibold
                                        text-white
                                        transition
                                        hover:bg-red-800
                                    "
                                >

                                    <i class="fa-solid fa-trash-can"></i>

                                    Move to trash

                                </button>

                            </div>

                        </div>

                    </section>

                @endif

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

            <p class="text-xs text-slate-500">

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
                    href="{{ route('admin.products.index') }}"
                    class="admin-btn admin-btn-light"
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
                                $product->exists
                                    ? 'fa-floppy-disk'
                                    : 'fa-plus'
                            }}
                        "
                    ></i>


                    {{
                        $product->exists
                            ? 'Save changes'
                            : 'Create product'
                    }}

                </button>

            </div>

        </div>

    </form>



    {{-- =========================================================
        SEPARATE DELETE FORMS

        Important:
        These are intentionally outside the main product form.
        This prevents invalid nested HTML forms.
    ========================================================== --}}
    @if($product->exists)

        @foreach($product->images as $image)

            <form
                id="delete-image-{{ $image->id }}"
                method="POST"
                action="{{
                    route(
                        'admin.products.images.destroy',
                        [$product, $image]
                    )
                }}"
                class="hidden"
            >

                @csrf

                @method('DELETE')

            </form>

        @endforeach



        <form
            id="delete-product-form"
            method="POST"
            action="{{ route('admin.products.destroy', $product) }}"
            class="hidden"
        >

            @csrf

            @method('DELETE')

        </form>

    @endif

</div>

@endsection



{{-- =============================================================
    PRODUCT FORM JAVASCRIPT
============================================================== --}}
@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {


    /* =========================================================
        AUTO SLUG
    ========================================================== */

    const productExists = @json($product->exists);

    const nameInput =
        document.getElementById('product-name');

    const slugInput =
        document.getElementById('product-slug');


    let slugManuallyChanged =
        productExists ||
        Boolean(slugInput?.value);


    const makeSlug = (value) => {

        return value

            .toString()

            .normalize('NFKD')

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

    };


    slugInput?.addEventListener(
        'input',
        function () {

            slugManuallyChanged =
                this.value.trim() !== '';

        }
    );


    nameInput?.addEventListener(
        'input',
        function () {

            if (
                !slugManuallyChanged
                &&
                slugInput
            ) {

                slugInput.value =
                    makeSlug(this.value);

            }

        }
    );



    /* =========================================================
        CATEGORY / SUBCATEGORY
    ========================================================== */

    const categorySelect =
        document.getElementById('category-id');

    const subcategorySelect =
        document.getElementById('subcategory-id');


    const filterSubcategories = () => {

        if (
            !categorySelect
            ||
            !subcategorySelect
        ) {

            return;

        }


        const selectedCategory =
            categorySelect.value;


        const currentValue =
            subcategorySelect.value;


        let currentStillVisible =
            currentValue === '';


        Array
            .from(subcategorySelect.options)
            .forEach(
                (option, index) => {

                    if (index === 0) {

                        option.hidden = false;

                        option.disabled = false;

                        return;

                    }


                    const visible =

                        selectedCategory !== ''

                        &&

                        option.dataset.parent ===
                        selectedCategory;


                    option.hidden =
                        !visible;


                    option.disabled =
                        !visible;


                    if (
                        visible
                        &&
                        option.value === currentValue
                    ) {

                        currentStillVisible = true;

                    }

                }
            );


        if (!currentStillVisible) {

            subcategorySelect.value = '';

        }

    };


    categorySelect?.addEventListener(
        'change',
        filterSubcategories
    );


    filterSubcategories();



    /* =========================================================
        CHARACTER COUNTERS
    ========================================================== */

    const bindCounter = (
        inputId,
        counterId,
        max
    ) => {

        const input =
            document.getElementById(inputId);

        const counter =
            document.getElementById(counterId);


        if (
            !input
            ||
            !counter
        ) {

            return;

        }


        const update = () => {

            counter.textContent =
                `${input.value.length} / ${max}`;

        };


        input.addEventListener(
            'input',
            update
        );


        update();

    };


    bindCounter(
        'short-description',
        'short-description-count',
        500
    );


    bindCounter(
        'meta-title',
        'meta-title-count',
        70
    );


    bindCounter(
        'meta-description',
        'meta-description-count',
        170
    );



    /* =========================================================
        IMAGE PREVIEW / IMAGE LIMIT
    ========================================================== */

    const imageInput =
        document.getElementById('product-images');


    const imagePreview =
        document.getElementById('new-image-preview');


    const imageLimitMessage =
        document.getElementById('image-limit-message');


    const imageCount =
        document.getElementById('image-count');



    const clearPreview = () => {

        if (!imagePreview) {

            return;

        }


        imagePreview.innerHTML = '';


        imagePreview.classList.add(
            'hidden'
        );


        imagePreview.classList.remove(
            'grid'
        );

    };



    imageInput?.addEventListener(
        'change',
        function () {

            clearPreview();


            if (imageLimitMessage) {

                imageLimitMessage.textContent = '';

                imageLimitMessage.classList.add(
                    'hidden'
                );

            }


            const existingImages =
                Number(
                    this.dataset.existingImages
                    ||
                    0
                );


            const selectedFiles =
                Array.from(
                    this.files
                    ||
                    []
                );


            const totalImages =
                existingImages
                +
                selectedFiles.length;



            /* -----------------------------------------
                Maximum six product images
            ----------------------------------------- */

            if (totalImages > 6) {

                if (imageLimitMessage) {

                    imageLimitMessage.textContent =

                        `You can upload only ${
                            Math.max(
                                0,
                                6 - existingImages
                            )
                        } more image(s).`;


                    imageLimitMessage.classList.remove(
                        'hidden'
                    );

                }


                this.value = '';


                if (imageCount) {

                    imageCount.textContent =
                        existingImages;

                }


                return;

            }



            if (imageCount) {

                imageCount.textContent =
                    totalImages;

            }



            if (
                !imagePreview
                ||
                selectedFiles.length === 0
            ) {

                return;

            }



            imagePreview.classList.remove(
                'hidden'
            );


            imagePreview.classList.add(
                'grid'
            );



            selectedFiles.forEach(
                (file, index) => {

                    const reader =
                        new FileReader();


                    reader.onload =
                        (event) => {

                            const item =
                                document.createElement(
                                    'div'
                                );


                            item.className =

                                'overflow-hidden rounded-xl border border-slate-200 bg-slate-50';


                            item.innerHTML = `

                                <img
                                    src="${event.target.result}"
                                    alt="Preview ${index + 1}"
                                    class="aspect-[4/5] w-full object-cover"
                                >

                                <div
                                    class="
                                        truncate
                                        border-t
                                        border-slate-100
                                        bg-white
                                        px-2
                                        py-2
                                        text-[10px]
                                        text-slate-500
                                    "
                                >
                                    ${file.name}
                                </div>

                            `;


                            imagePreview.appendChild(
                                item
                            );

                        };


                    reader.readAsDataURL(
                        file
                    );

                }
            );

        }
    );

});

</script>

@endpush