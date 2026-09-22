@extends('admin.layouts.app')

@section(
    'title',
    $banner->exists
        ? 'Edit Banner'
        : 'New Banner'
)

@section(
    'page_title',
    $banner->exists
        ? 'Edit Banner'
        : 'Create Banner'
)


@section('content')


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
                    href="{{ route('admin.banners.index') }}"
                    class="
                        transition
                        hover:text-copper-500
                    "
                >
                    Banners
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
                        $banner->exists
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
                    $banner->exists
                        ? $banner->title
                        : 'Add a new banner'
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
                    $banner->exists

                        ? 'Update banner content, artwork, scheduling and storefront visibility.'

                        : 'Create a polished storefront banner or hero slide for SARAI.'
                }}

            </p>


        </div>



        <a
            href="{{ route('admin.banners.index') }}"
            class="
                admin-btn
                admin-btn-light
            "
        >

            <i class="fa-solid fa-arrow-left"></i>

            Back to banners

        </a>


    </div>



    {{-- =========================================================
        VALIDATION
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
        id="banner-form"
        method="POST"
        enctype="multipart/form-data"
        action="{{
            $banner->exists

                ? route(
                    'admin.banners.update',
                    $banner
                )

                : route(
                    'admin.banners.store'
                )
        }}"
    >

        @csrf


        @if($banner->exists)

            @method('PUT')

        @endif



        <div
            class="
                grid
                gap-6

                xl:grid-cols-[minmax(0,1fr)_360px]
                xl:items-start
            "
        >


            {{-- =================================================
                LEFT
            ================================================== --}}
            <div
                class="
                    grid
                    min-w-0
                    gap-6
                "
            >


                {{-- =============================================
                    CONTENT
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

                            <i
                                class="
                                    fa-solid
                                    fa-pen-to-square
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
                                Banner content
                            </h3>


                            <p
                                class="
                                    mt-0.5
                                    text-xs
                                    text-slate-500
                                "
                            >
                                Write the text customers will see on the banner.
                            </p>


                        </div>


                    </div>



                    <div
                        class="
                            grid
                            gap-5
                            p-5

                            sm:p-6
                            md:grid-cols-2
                        "
                    >


                        {{-- EYEBROW --}}
                        <label>


                            <span class="admin-label">
                                Eyebrow
                            </span>


                            <input
                                id="banner-eyebrow"
                                class="
                                    admin-input

                                    @error('eyebrow')
                                        !border-red-400
                                    @enderror
                                "
                                name="eyebrow"
                                value="{{ old('eyebrow', $banner->eyebrow) }}"
                                maxlength="100"
                                placeholder="e.g. Festive Collection"
                            >


                            @error('eyebrow')

                                <span class="error">
                                    {{ $message }}
                                </span>

                            @enderror


                        </label>



                        {{-- TITLE --}}
                        <label>


                            <span class="admin-label">

                                Title

                                <span class="text-red-500">
                                    *
                                </span>

                            </span>


                            <input
                                id="banner-title"
                                class="
                                    admin-input

                                    @error('title')
                                        !border-red-400
                                    @enderror
                                "
                                name="title"
                                value="{{ old('title', $banner->title) }}"
                                maxlength="191"
                                placeholder="Main banner headline"
                                required
                            >


                            @error('title')

                                <span class="error">
                                    {{ $message }}
                                </span>

                            @enderror


                        </label>



                        {{-- SUBTITLE --}}
                        <label>


                            <span class="admin-label">
                                Subtitle
                            </span>


                            <input
                                id="banner-subtitle"
                                class="
                                    admin-input

                                    @error('subtitle')
                                        !border-red-400
                                    @enderror
                                "
                                name="subtitle"
                                value="{{ old('subtitle', $banner->subtitle) }}"
                                maxlength="191"
                                placeholder="Supporting headline"
                            >


                            @error('subtitle')

                                <span class="error">
                                    {{ $message }}
                                </span>

                            @enderror


                        </label>



                        {{-- BUTTON TEXT --}}
                        <label>


                            <span class="admin-label">
                                Button text
                            </span>


                            <input
                                id="banner-button-text"
                                class="
                                    admin-input

                                    @error('button_text')
                                        !border-red-400
                                    @enderror
                                "
                                name="button_text"
                                value="{{ old('button_text', $banner->button_text) }}"
                                maxlength="60"
                                placeholder="e.g. Explore collection"
                            >


                            @error('button_text')

                                <span class="error">
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
                                    id="banner-description-count"
                                    class="
                                        text-[10px]
                                        text-slate-400
                                    "
                                >
                                    0 / 500
                                </span>


                            </div>



                            <textarea
                                id="banner-description"
                                class="
                                    admin-input
                                    min-h-[130px]
                                    resize-y

                                    @error('description')
                                        !border-red-400
                                    @enderror
                                "
                                name="description"
                                maxlength="500"
                                rows="5"
                                placeholder="Optional supporting description..."
                            >{{ old('description', $banner->description) }}</textarea>


                            @error('description')

                                <span class="error">
                                    {{ $message }}
                                </span>

                            @enderror


                        </label>



                        {{-- BUTTON URL --}}
                        <label class="md:col-span-2">


                            <span class="admin-label">
                                Button URL
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
                                    class="
                                        admin-input
                                        !pl-10

                                        @error('button_url')
                                            !border-red-400
                                        @enderror
                                    "
                                    name="button_url"
                                    value="{{ old('button_url', $banner->button_url) }}"
                                    maxlength="255"
                                    placeholder="/collections/festive or https://example.com"
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
                                Relative storefront paths and full URLs are supported.
                            </span>


                            @error('button_url')

                                <span class="error">
                                    {{ $message }}
                                </span>

                            @enderror


                        </label>


                    </div>


                </section>



                {{-- =============================================
                    ARTWORK
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

                            <i class="fa-regular fa-images"></i>

                        </span>



                        <div>


                            <h3
                                class="
                                    font-semibold
                                    text-slate-900
                                "
                            >
                                Banner artwork
                            </h3>


                            <p
                                class="
                                    mt-0.5
                                    text-xs
                                    text-slate-500
                                "
                            >
                                Choose desktop and optional mobile artwork.
                                New files preview before saving.
                            </p>


                        </div>


                    </div>



                    <div
                        class="
                            grid
                            gap-5
                            p-5

                            sm:p-6
                            lg:grid-cols-2
                        "
                    >


                        {{-- =========================================
                            DESKTOP IMAGE
                        ========================================== --}}
                        <div>


                            <div
                                class="
                                    mb-2
                                    flex
                                    items-center
                                    justify-between
                                    gap-3
                                "
                            >


                                <span class="admin-label !mb-0">

                                    Desktop image

                                    <span class="text-red-500">
                                        *
                                    </span>

                                </span>


                                <span
                                    class="
                                        text-[10px]
                                        text-slate-400
                                    "
                                >
                                    JPG, PNG, WebP · max 6 MB
                                </span>


                            </div>



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
                                        aspect-[16/8]
                                        w-full
                                        bg-slate-100
                                    "
                                >


                                    <img
                                        id="desktop-image-preview"
                                        src="{{
                                            $banner->image
                                                ? asset($banner->image)
                                                : ''
                                        }}"
                                        alt="Desktop banner preview"
                                        class="
                                            {{
                                                $banner->image
                                                    ? ''
                                                    : 'hidden'
                                            }}

                                            h-full
                                            w-full
                                            object-cover
                                        "
                                    >



                                    <div
                                        id="desktop-image-empty"
                                        class="
                                            {{
                                                $banner->image
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
                                                    h-12
                                                    w-12
                                                    place-items-center
                                                    rounded-full
                                                    bg-white
                                                    text-slate-400
                                                    shadow-sm
                                                "
                                            >

                                                <i class="fa-regular fa-image"></i>

                                            </span>


                                            <p
                                                class="
                                                    mt-3
                                                    text-sm
                                                    font-medium
                                                    text-slate-600
                                                "
                                            >
                                                No desktop image selected
                                            </p>


                                            <p
                                                class="
                                                    mt-1
                                                    text-xs
                                                    text-slate-400
                                                "
                                            >
                                                Use a wide, high-quality image
                                                for hero presentation.
                                            </p>


                                        </div>


                                    </div>



                                    <span
                                        id="desktop-image-new-badge"
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
                                    </span>


                                </div>



                                <div
                                    class="
                                        border-t
                                        border-slate-200
                                        bg-white
                                        p-3
                                    "
                                >


                                    <input
                                        id="desktop-image-input"
                                        type="file"
                                        name="image"
                                        accept="image/jpeg,image/png,image/webp"
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
                                        "
                                        @required(!$banner->exists)
                                    >


                                    <p
                                        id="desktop-image-filename"
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



                        {{-- =========================================
                            MOBILE IMAGE
                        ========================================== --}}
                        <div>


                            <div
                                class="
                                    mb-2
                                    flex
                                    items-center
                                    justify-between
                                    gap-3
                                "
                            >


                                <span class="admin-label !mb-0">
                                    Mobile image
                                </span>


                                <span
                                    class="
                                        text-[10px]
                                        text-slate-400
                                    "
                                >
                                    Used below 680px · max 6 MB
                                </span>


                            </div>



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
                                        aspect-[4/5]
                                        max-h-[420px]
                                        w-full
                                        bg-slate-100
                                    "
                                >


                                    <img
                                        id="mobile-image-preview"
                                        src="{{
                                            $banner->mobile_image
                                                ? asset($banner->mobile_image)
                                                : ''
                                        }}"
                                        alt="Mobile banner preview"
                                        class="
                                            {{
                                                $banner->mobile_image
                                                    ? ''
                                                    : 'hidden'
                                            }}

                                            h-full
                                            w-full
                                            object-cover
                                        "
                                    >



                                    <div
                                        id="mobile-image-empty"
                                        class="
                                            {{
                                                $banner->mobile_image
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
                                                    h-12
                                                    w-12
                                                    place-items-center
                                                    rounded-full
                                                    bg-white
                                                    text-slate-400
                                                    shadow-sm
                                                "
                                            >

                                                <i
                                                    class="
                                                        fa-solid
                                                        fa-mobile-screen-button
                                                    "
                                                ></i>

                                            </span>


                                            <p
                                                class="
                                                    mt-3
                                                    text-sm
                                                    font-medium
                                                    text-slate-600
                                                "
                                            >
                                                No mobile image selected
                                            </p>


                                            <p
                                                class="
                                                    mt-1
                                                    text-xs
                                                    text-slate-400
                                                "
                                            >
                                                If empty, the desktop image is
                                                used on mobile.
                                            </p>


                                        </div>


                                    </div>



                                    <span
                                        id="mobile-image-new-badge"
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
                                    </span>


                                </div>



                                <div
                                    class="
                                        border-t
                                        border-slate-200
                                        bg-white
                                        p-3
                                    "
                                >


                                    <input
                                        id="mobile-image-input"
                                        type="file"
                                        name="mobile_image"
                                        accept="image/jpeg,image/png,image/webp"
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
                                        "
                                    >



                                    <p
                                        id="mobile-image-filename"
                                        class="
                                            mt-2
                                            hidden
                                            text-[11px]
                                            font-medium
                                            text-emerald-700
                                        "
                                    ></p>



                                    @if($banner->mobile_image)

                                        <label
                                            class="
                                                mt-3
                                                flex
                                                cursor-pointer
                                                items-center
                                                gap-2
                                                text-xs
                                                text-red-600
                                            "
                                        >


                                            <input
                                                type="hidden"
                                                name="remove_mobile_image"
                                                value="0"
                                            >


                                            <input
                                                id="remove-mobile-image"
                                                type="checkbox"
                                                name="remove_mobile_image"
                                                value="1"
                                                class="
                                                    rounded
                                                    border-slate-300
                                                    text-red-600
                                                    focus:ring-red-500
                                                "
                                                @checked(
                                                    old(
                                                        'remove_mobile_image'
                                                    )
                                                )
                                            >


                                            Remove current mobile image
                                            when saving


                                        </label>

                                    @else

                                        <input
                                            type="hidden"
                                            name="remove_mobile_image"
                                            value="0"
                                        >

                                    @endif


                                </div>


                            </div>



                            @error('mobile_image')

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


                    </div>


                </section>



                {{-- =============================================
                    PLACEMENT / SCHEDULE
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
                                bg-blue-50
                                text-blue-600
                            "
                        >

                            <i class="fa-regular fa-clock"></i>

                        </span>



                        <div>


                            <h3
                                class="
                                    font-semibold
                                    text-slate-900
                                "
                            >
                                Placement & schedule
                            </h3>


                            <p
                                class="
                                    mt-0.5
                                    text-xs
                                    text-slate-500
                                "
                            >
                                Control where the banner belongs,
                                its order and optional active period.
                            </p>


                        </div>


                    </div>



                    <div
                        class="
                            grid
                            gap-5
                            p-5

                            sm:p-6
                            md:grid-cols-2
                        "
                    >


                        {{-- PLACEMENT --}}
                        <label>


                            <span class="admin-label">

                                Placement

                                <span class="text-red-500">
                                    *
                                </span>

                            </span>


                            <select
                                name="placement"
                                class="
                                    admin-input

                                    @error('placement')
                                        !border-red-400
                                    @enderror
                                "
                                required
                            >


                                <option
                                    value="home_hero"
                                    @selected(
                                        old(
                                            'placement',
                                            $banner->placement
                                            ?:
                                            'home_hero'
                                        )
                                        ===
                                        'home_hero'
                                    )
                                >
                                    Home hero
                                </option>


                                <option
                                    value="home_promo"
                                    @selected(
                                        old(
                                            'placement',
                                            $banner->placement
                                        )
                                        ===
                                        'home_promo'
                                    )
                                >
                                    Home promo
                                </option>


                                <option
                                    value="category"
                                    @selected(
                                        old(
                                            'placement',
                                            $banner->placement
                                        )
                                        ===
                                        'category'
                                    )
                                >
                                    Category
                                </option>


                            </select>


                            @error('placement')

                                <span class="error">
                                    {{ $message }}
                                </span>

                            @enderror


                        </label>



                        {{-- ORDER --}}
                        <label>


                            <span class="admin-label">

                                Display order

                                <span class="text-red-500">
                                    *
                                </span>

                            </span>


                            <input
                                type="number"
                                name="sort_order"
                                min="0"
                                step="1"
                                value="{{
                                    old(
                                        'sort_order',
                                        $banner->sort_order
                                        ??
                                        0
                                    )
                                }}"
                                class="
                                    admin-input

                                    @error('sort_order')
                                        !border-red-400
                                    @enderror
                                "
                                required
                            >


                            <span
                                class="
                                    mt-1
                                    block
                                    text-[11px]
                                    text-slate-400
                                "
                            >
                                Lower numbers appear first within
                                the same placement.
                            </span>


                            @error('sort_order')

                                <span class="error">
                                    {{ $message }}
                                </span>

                            @enderror


                        </label>



                        {{-- START --}}
                        <label>


                            <span class="admin-label">
                                Starts at
                            </span>


                            <input
                                type="datetime-local"
                                name="starts_at"
                                value="{{
                                    old(
                                        'starts_at',
                                        $banner
                                            ->starts_at
                                            ?->format(
                                                'Y-m-d\TH:i'
                                            )
                                    )
                                }}"
                                class="
                                    admin-input

                                    @error('starts_at')
                                        !border-red-400
                                    @enderror
                                "
                            >


                            <span
                                class="
                                    mt-1
                                    block
                                    text-[11px]
                                    text-slate-400
                                "
                            >
                                Leave empty to allow it immediately
                                when active.
                            </span>


                            @error('starts_at')

                                <span class="error">
                                    {{ $message }}
                                </span>

                            @enderror


                        </label>



                        {{-- END --}}
                        <label>


                            <span class="admin-label">
                                Ends at
                            </span>


                            <input
                                type="datetime-local"
                                name="ends_at"
                                value="{{
                                    old(
                                        'ends_at',
                                        $banner
                                            ->ends_at
                                            ?->format(
                                                'Y-m-d\TH:i'
                                            )
                                    )
                                }}"
                                class="
                                    admin-input

                                    @error('ends_at')
                                        !border-red-400
                                    @enderror
                                "
                            >


                            <span
                                class="
                                    mt-1
                                    block
                                    text-[11px]
                                    text-slate-400
                                "
                            >
                                Leave empty if the banner should
                                not expire automatically.
                            </span>


                            @error('ends_at')

                                <span class="error">
                                    {{ $message }}
                                </span>

                            @enderror


                        </label>


                    </div>


                </section>


            </div>



            {{-- =================================================
                RIGHT
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
                                    Control banner visibility.
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

                                <i class="fa-solid fa-eye"></i>

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
                                        $banner->exists

                                            ? $banner->is_active

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
                                    Active banner
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
                                    The live scope also respects the
                                    optional start and end times.
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
                                            $banner->exists
                                                ? 'fa-floppy-disk'
                                                : 'fa-plus'
                                        }}
                                    "
                                ></i>


                                {{
                                    $banner->exists
                                        ? 'Save changes'
                                        : 'Create banner'
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
                                Banner guide
                            </h3>


                            <p
                                class="
                                    mt-0.5
                                    text-xs
                                    text-slate-500
                                "
                            >
                                How the current storefront handles
                                banner content.
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
                                Home hero:
                            </strong>

                            the current homepage slider reads active
                            <code>home_hero</code> banners.

                        </div>



                        <div
                            class="
                                rounded-xl
                                bg-slate-50
                                p-3
                            "
                        >

                            <strong class="text-slate-800">
                                Mobile artwork:
                            </strong>

                            when provided, it is used on screens
                            up to 680px; otherwise the desktop
                            artwork remains visible.

                        </div>



                        <div
                            class="
                                rounded-xl
                                bg-slate-50
                                p-3
                            "
                        >

                            <strong class="text-slate-800">
                                Scheduling:
                            </strong>

                            an active banner is shown only when its
                            start/end window allows it.

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
                    href="{{ route('admin.banners.index') }}"
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
                                $banner->exists
                                    ? 'fa-floppy-disk'
                                    : 'fa-plus'
                            }}
                        "
                    ></i>


                    {{
                        $banner->exists
                            ? 'Save changes'
                            : 'Create banner'
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
            DESCRIPTION COUNTER
        ========================================================== */

        const description =
            document.getElementById(
                'banner-description'
            );


        const descriptionCount =
            document.getElementById(
                'banner-description-count'
            );


        function updateDescriptionCount()
        {

            if (
                !description
                ||
                !descriptionCount
            ) {

                return;

            }


            descriptionCount.textContent =
                `${
                    description.value.length
                } / 500`;

        }


        description?.addEventListener(
            'input',
            updateDescriptionCount
        );


        updateDescriptionCount();



        /* =========================================================
            IMAGE PREVIEW
        ========================================================== */

        function bindImagePreview(
            inputId,
            previewId,
            emptyId,
            badgeId,
            filenameId,
            removeCheckboxId = null
        ) {

            const input =
                document.getElementById(
                    inputId
                );


            const preview =
                document.getElementById(
                    previewId
                );


            const empty =
                document.getElementById(
                    emptyId
                );


            const badge =
                document.getElementById(
                    badgeId
                );


            const filename =
                document.getElementById(
                    filenameId
                );


            const removeCheckbox =
                removeCheckboxId

                    ? document.getElementById(
                        removeCheckboxId
                    )

                    : null;


            if (
                !input
                ||
                !preview
                ||
                !empty
            ) {

                return;

            }


            let objectUrl =
                null;



            input.addEventListener(
                'change',
                function () {


                    const file =
                        this.files?.[0];


                    if (!file) {

                        return;

                    }


                    const allowedTypes = [
                        'image/jpeg',
                        'image/png',
                        'image/webp'
                    ];


                    if (
                        !allowedTypes.includes(
                            file.type
                        )
                    ) {

                        this.value =
                            '';


                        window.alert(
                            'Please choose a JPG, PNG or WebP image.'
                        );


                        return;

                    }


                    if (
                        file.size
                        >
                        6 * 1024 * 1024
                    ) {

                        this.value =
                            '';


                        window.alert(
                            'The selected image is larger than 6 MB.'
                        );


                        return;

                    }


                    if (objectUrl) {

                        URL.revokeObjectURL(
                            objectUrl
                        );

                    }


                    objectUrl =
                        URL.createObjectURL(
                            file
                        );


                    preview.src =
                        objectUrl;


                    preview.classList.remove(
                        'hidden'
                    );


                    empty.classList.add(
                        'hidden'
                    );


                    badge?.classList.remove(
                        'hidden'
                    );


                    if (filename) {

                        filename.textContent =
                            `Selected: ${file.name}`;


                        filename.classList.remove(
                            'hidden'
                        );

                    }


                    if (removeCheckbox) {

                        removeCheckbox.checked =
                            false;

                    }


                }
            );



            window.addEventListener(
                'beforeunload',
                function () {


                    if (objectUrl) {

                        URL.revokeObjectURL(
                            objectUrl
                        );

                    }


                }
            );

        }



        bindImagePreview(

            'desktop-image-input',

            'desktop-image-preview',

            'desktop-image-empty',

            'desktop-image-new-badge',

            'desktop-image-filename'

        );



        bindImagePreview(

            'mobile-image-input',

            'mobile-image-preview',

            'mobile-image-empty',

            'mobile-image-new-badge',

            'mobile-image-filename',

            'remove-mobile-image'

        );


    }
);

</script>

@endpush