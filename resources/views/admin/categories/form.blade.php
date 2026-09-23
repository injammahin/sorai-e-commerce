@extends('admin.layouts.app')

@section(
    'title',
    $category->exists
        ? 'Edit Category'
        : 'New Category'
)

@section(
    'page_title',
    $category->exists
        ? 'Edit Category'
        : 'Create Category'
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
                    href="{{ route('admin.categories.index') }}"
                    class="
                        transition
                        hover:text-copper-500
                    "
                >
                    Categories
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
                        $category->exists
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
                    $category->exists
                        ? $category->name
                        : 'Add a new category'
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
                    $category->exists

                        ? 'Update category content, imagery, SEO and storefront visibility.'

                        : 'Create a category or subcategory for organizing products in the storefront.'
                }}

            </p>


        </div>



        <a
            href="{{ route('admin.categories.index') }}"
            class="
                admin-btn
                admin-btn-light
            "
        >

            <i class="fa-solid fa-arrow-left"></i>

            Back to categories

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
        id="category-form"
        method="POST"
        enctype="multipart/form-data"
        action="{{
            $category->exists

                ? route(
                    'admin.categories.update',
                    $category
                )

                : route(
                    'admin.categories.store'
                )
        }}"
    >

        @csrf


        @if($category->exists)

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
                    INFORMATION
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

                            <i class="fa-solid fa-layer-group"></i>

                        </span>


                        <div>

                            <h3
                                class="
                                    font-semibold
                                    text-slate-900
                                "
                            >
                                Category information
                            </h3>


                            <p
                                class="
                                    mt-0.5
                                    text-xs
                                    text-slate-500
                                "
                            >
                                The main content used across category pages and navigation.
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


                            {{-- NAME --}}
                            <label>

                                <span class="admin-label">

                                    Name

                                    <span class="text-red-500">
                                        *
                                    </span>

                                </span>


                                <input
                                    id="category-name"
                                    class="
                                        admin-input

                                        @error('name')
                                            !border-red-400
                                        @enderror
                                    "
                                    name="name"
                                    value="{{ old('name', $category->name) }}"
                                    maxlength="191"
                                    autocomplete="off"
                                    required
                                >


                                @error('name')

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
                                        id="category-slug"
                                        class="
                                            admin-input
                                            !pl-10

                                            @error('slug')
                                                !border-red-400
                                            @enderror
                                        "
                                        name="slug"
                                        value="{{ old('slug', $category->slug) }}"
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
                                    Used in the storefront category URL.
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



                            {{-- PARENT --}}
                            <label>

                                <span class="admin-label">
                                    Parent category
                                </span>


                                <select
                                    class="
                                        admin-input

                                        @error('parent_id')
                                            !border-red-400
                                        @enderror
                                    "
                                    name="parent_id"
                                >

                                    <option value="">
                                        Top-level category
                                    </option>


                                    @foreach($parents as $parent)

                                        <option
                                            value="{{ $parent->id }}"
                                            @selected(
                                                (string)
                                                old(
                                                    'parent_id',
                                                    $category->parent_id
                                                )
                                                ===
                                                (string)
                                                $parent->id
                                            )
                                        >

                                            {{ $parent->name }}

                                        </option>

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
                                    Leave empty to create a top-level category.
                                </span>


                                @error('parent_id')

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



                            {{-- SORT --}}
                            <label>

                                <span class="admin-label">

                                    Sort order

                                    <span class="text-red-500">
                                        *
                                    </span>

                                </span>


                                <input
                                    class="
                                        admin-input

                                        @error('sort_order')
                                            !border-red-400
                                        @enderror
                                    "
                                    type="number"
                                    min="0"
                                    step="1"
                                    name="sort_order"
                                    value="{{
                                        old(
                                            'sort_order',
                                            $category->sort_order
                                            ??
                                            0
                                        )
                                    }}"
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
                                    Lower numbers appear earlier in ordered category lists.
                                </span>


                                @error('sort_order')

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
                            <label>

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
                                    value="{{ old('tagline', $category->tagline) }}"
                                    maxlength="191"
                                    placeholder="A short supporting phrase"
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



                            {{-- HEADING --}}
                            <label>

                                <span class="admin-label">
                                    Page heading
                                </span>


                                <input
                                    class="
                                        admin-input

                                        @error('heading')
                                            !border-red-400
                                        @enderror
                                    "
                                    name="heading"
                                    value="{{ old('heading', $category->heading) }}"
                                    maxlength="191"
                                    placeholder="Optional display heading"
                                >


                                @error('heading')

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

                                <span class="admin-label">
                                    Description
                                </span>


                                <textarea
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
                                    placeholder="Describe this category, its products, style or craftsmanship..."
                                >{{ old('description', $category->description) }}</textarea>


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
                    CATEGORY IMAGES
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
                                Category images
                            </h3>


                            <p
                                class="
                                    mt-0.5
                                    text-xs
                                    text-slate-500
                                "
                            >
                                Selected images are previewed immediately before saving.
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


                        {{-- TILE --}}
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
                                    Tile image
                                </span>


                                <span
                                    class="
                                        text-[10px]
                                        text-slate-400
                                    "
                                >
                                    JPG, PNG, WebP · max 4 MB
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
                                        aspect-square
                                        max-h-[360px]
                                        w-full
                                        bg-slate-100
                                    "
                                >


                                    <img
                                        id="tile-image-preview"
                                        src="{{
                                            $category->image
                                                ? asset($category->image)
                                                : ''
                                        }}"
                                        alt="Tile image preview"
                                        class="
                                            {{
                                                $category->image
                                                    ? ''
                                                    : 'hidden'
                                            }}

                                            h-full
                                            w-full
                                            object-cover
                                        "
                                    >



                                    <div
                                        id="tile-image-empty"
                                        class="
                                            {{
                                                $category->image
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
                                                No tile image selected
                                            </p>


                                            <p
                                                class="
                                                    mt-1
                                                    text-xs
                                                    text-slate-400
                                                "
                                            >
                                                Square or portrait imagery works best.
                                            </p>


                                        </div>


                                    </div>


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
                                        id="tile-image-input"
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
                                        type="file"
                                        name="image"
                                        accept="image/jpeg,image/png,image/webp"
                                    >



                                    @if($category->image)

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
                                                type="checkbox"
                                                name="remove_image"
                                                value="1"
                                                class="
                                                    rounded
                                                    border-slate-300
                                                    text-red-600
                                                    focus:ring-red-500
                                                "
                                            >

                                            Remove current tile image when saving

                                        </label>

                                    @endif


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



                        {{-- BANNER --}}
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
                                    Wide banner
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
                                        aspect-[16/7]
                                        w-full
                                        bg-slate-100
                                    "
                                >


                                    <img
                                        id="banner-image-preview"
                                        src="{{
                                            $category->banner_image
                                                ? asset($category->banner_image)
                                                : ''
                                        }}"
                                        alt="Banner image preview"
                                        class="
                                            {{
                                                $category->banner_image
                                                    ? ''
                                                    : 'hidden'
                                            }}

                                            h-full
                                            w-full
                                            object-cover
                                        "
                                    >



                                    <div
                                        id="banner-image-empty"
                                        class="
                                            {{
                                                $category->banner_image
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
                                                No banner selected
                                            </p>


                                            <p
                                                class="
                                                    mt-1
                                                    text-xs
                                                    text-slate-400
                                                "
                                            >
                                                Use a wide image for category hero areas.
                                            </p>


                                        </div>


                                    </div>


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
                                        id="banner-image-input"
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
                                        type="file"
                                        name="banner_image"
                                        accept="image/jpeg,image/png,image/webp"
                                    >



                                    @if($category->banner_image)

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
                                                type="checkbox"
                                                name="remove_banner_image"
                                                value="1"
                                                class="
                                                    rounded
                                                    border-slate-300
                                                    text-red-600
                                                    focus:ring-red-500
                                                "
                                            >

                                            Remove current banner when saving

                                        </label>

                                    @endif


                                </div>


                            </div>



                            @error('banner_image')

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

                            <h3
                                class="
                                    font-semibold
                                    text-slate-900
                                "
                            >
                                Search engine optimization
                            </h3>


                            <p
                                class="
                                    mt-0.5
                                    text-xs
                                    text-slate-500
                                "
                            >
                                Optional metadata for category search results.
                            </p>

                        </div>


                    </div>



                    <div
                        class="
                            grid
                            gap-5
                            p-5

                            sm:p-6
                        "
                    >


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
                                    class="
                                        text-[10px]
                                        text-slate-400
                                    "
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
                                name="meta_title"
                                maxlength="70"
                                value="{{ old('meta_title', $category->meta_title) }}"
                            >


                            @error('meta_title')

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
                                    class="
                                        text-[10px]
                                        text-slate-400
                                    "
                                >
                                    0 / 170
                                </span>

                            </div>


                            <textarea
                                id="meta-description"
                                class="
                                    admin-input
                                    min-h-[110px]
                                    resize-y

                                    @error('meta_description')
                                        !border-red-400
                                    @enderror
                                "
                                name="meta_description"
                                maxlength="170"
                                rows="4"
                            >{{ old('meta_description', $category->meta_description) }}</textarea>


                            @error('meta_description')

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
                                    Control storefront visibility.
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


                        <div class="divide-y divide-slate-100">


                            {{-- ACTIVE --}}
                            <label
                                class="
                                    flex
                                    cursor-pointer
                                    items-start
                                    gap-3
                                    pb-4
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
                                            $category->exists
                                                ? $category->is_active
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
                                        Active category
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
                                        Allows this category to be used on the storefront.
                                    </span>

                                </span>


                            </label>



                            {{-- MENU --}}
                            <label
                                class="
                                    flex
                                    cursor-pointer
                                    items-start
                                    gap-3
                                    pt-4
                                "
                            >

                                <input
                                    type="hidden"
                                    name="show_in_menu"
                                    value="0"
                                >


                                <input
                                    type="checkbox"
                                    name="show_in_menu"
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
                                            'show_in_menu',
                                            $category->exists
                                                ? $category->show_in_menu
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
                                        Show in menu
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
                                        Include this category in storefront navigation where supported.
                                    </span>

                                </span>


                            </label>


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
                                            $category->exists
                                                ? 'fa-floppy-disk'
                                                : 'fa-plus'
                                        }}
                                    "
                                ></i>


                                {{
                                    $category->exists
                                        ? 'Save changes'
                                        : 'Create category'
                                }}

                            </button>


                        </div>


                    </div>


                </section>



                {{-- STRUCTURE INFO --}}
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

                            <i class="fa-solid fa-circle-info"></i>

                        </span>


                        <div>

                            <h3
                                class="
                                    text-sm
                                    font-semibold
                                    text-slate-900
                                "
                            >
                                Category structure
                            </h3>


                            <p
                                class="
                                    mt-0.5
                                    text-xs
                                    text-slate-500
                                "
                            >
                                AATCHALA uses top-level categories and subcategories.
                            </p>

                        </div>


                    </div>



                    <div
                        class="
                            mt-4
                            rounded-xl
                            bg-slate-50
                            p-4
                            text-xs
                            leading-5
                            text-slate-600
                        "
                    >

                        <p>

                            <strong class="text-slate-800">
                                Top-level:
                            </strong>

                            leave Parent category empty.

                        </p>


                        <p class="mt-2">

                            <strong class="text-slate-800">
                                Subcategory:
                            </strong>

                            select a top-level parent.

                        </p>


                    </div>


                </section>


            </aside>


        </div>



        {{-- =====================================================
            BOTTOM BAR
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
                    href="{{ route('admin.categories.index') }}"
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
                                $category->exists
                                    ? 'fa-floppy-disk'
                                    : 'fa-plus'
                            }}
                        "
                    ></i>


                    {{
                        $category->exists
                            ? 'Save changes'
                            : 'Create category'
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

        const categoryExists =
            @json($category->exists);


        const nameInput =
            document.getElementById(
                'category-name'
            );


        const slugInput =
            document.getElementById(
                'category-slug'
            );


        let slugManuallyChanged =
            categoryExists
            ||
            Boolean(
                slugInput?.value
            );


        function makeSlug(value)
        {
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


        nameInput?.addEventListener(
            'input',
            function () {

                if (
                    ! slugManuallyChanged
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
            IMAGE PREVIEW
        ========================================================== */

        function bindImagePreview(
            inputId,
            previewId,
            emptyId,
            removeSelector
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

            const removeCheckbox =
                document.querySelector(
                    removeSelector
                );


            if (
                ! input
                ||
                ! preview
                ||
                ! empty
            ) {
                return;
            }


            let objectUrl = null;


            input.addEventListener(
                'change',
                function () {

                    const file =
                        this.files?.[0];


                    if (! file) {
                        return;
                    }


                    if (
                        ! file.type.startsWith(
                            'image/'
                        )
                    ) {
                        this.value = '';

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
            'tile-image-input',
            'tile-image-preview',
            'tile-image-empty',
            'input[name="remove_image"]'
        );


        bindImagePreview(
            'banner-image-input',
            'banner-image-preview',
            'banner-image-empty',
            'input[name="remove_banner_image"]'
        );



        /* =========================================================
            SEO COUNTERS
        ========================================================== */

        function bindCounter(
            inputId,
            counterId,
            max
        ) {
            const input =
                document.getElementById(
                    inputId
                );

            const counter =
                document.getElementById(
                    counterId
                );


            if (
                ! input
                ||
                ! counter
            ) {
                return;
            }


            function update()
            {
                counter.textContent =
                    `${input.value.length} / ${max}`;
            }


            input.addEventListener(
                'input',
                update
            );


            update();
        }


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


    }
);

</script>

@endpush