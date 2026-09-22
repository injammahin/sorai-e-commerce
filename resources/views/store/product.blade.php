@extends('layouts.store')

{{-- SARAI PRODUCT SHOW V3 FINAL: gallery + single-open animated accordion --}}

@section('title', $product->meta_title ?: $product->name . ' | SARAI')
@section('description', $product->meta_description ?: $product->short_description)
@section('canonical', $product->canonical_url ?: route('products.show', $product))
@section('og_type', 'product')
@section('og_image', asset($product->primaryImage->path))

@php
    $galleryImages = $product->images->isNotEmpty()
        ? $product->images->values()
        : collect([$product->primaryImage]);

    $firstGalleryImage = $galleryImages->first();

    $galleryPayload = $galleryImages
        ->map(fn ($image) => [
            'src' => asset($image->path),
            'alt' => $image->alt_text ?: $product->name,
        ])
        ->values();
@endphp

@push('schema')
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->name,
            'image' => $galleryImages->map(fn ($image) => asset($image->path))->all(),
            'description' => $product->short_description,
            'sku' => $product->sku,
            'brand' => ['@type' => 'Brand', 'name' => 'SARAI'],
            'offers' => [
                '@type' => 'Offer',
                'url' => route('products.show', $product),
                'priceCurrency' => 'BDT',
                'price' => (float) $product->price,
                'availability' => $product->stock > 0
                    ? 'https://schema.org/InStock'
                    : 'https://schema.org/OutOfStock',
            ],
        ], JSON_UNESCAPED_SLASHES) !!}
    </script>
@endpush

@push('head')
    <style>
        /* ================================================================
           SARAI PRODUCT SHOW PAGE
        ================================================================= */
        .product-show-section {
            padding-top: clamp(1.5rem, 3vw, 2.5rem);
            padding-bottom: clamp(4rem, 7vw, 7rem);
        }

        .product-breadcrumbs {
            display: flex;
            align-items: center;
            gap: .55rem;
            margin-bottom: clamp(1.5rem, 3vw, 2.4rem);
            overflow: hidden;
            color: #716c66;
            font-size: 11px;
            white-space: nowrap;
        }

        .product-breadcrumbs a { transition: color .25s ease; }
        .product-breadcrumbs a:hover { color: var(--copper); }
        .product-breadcrumbs i { color: #aaa39a; font-size: 7px; }
        .product-breadcrumbs span:last-child {
            min-width: 0;
            overflow: hidden;
            color: #302d2a;
            text-overflow: ellipsis;
        }

        .product-show-layout {
            display: grid;
            grid-template-columns: 84px minmax(0, 1fr) minmax(340px, 410px);
            grid-template-areas: "thumbs stage details";
            align-items: start;
            gap: clamp(1rem, 2vw, 2rem);
        }

        /* Side thumbnails */
        .product-gallery-thumbnails {
            grid-area: thumbs;
            display: flex;
            flex-direction: column;
            gap: .7rem;
            max-height: min(74vh, 760px);
            padding: 1px 5px 1px 1px;
            overflow-x: hidden;
            overflow-y: auto;
            scrollbar-color: #b9b0a5 transparent;
            scrollbar-width: thin;
        }

        .product-gallery-thumbnail {
            position: relative;
            flex: 0 0 auto;
            width: 100%;
            padding: 0;
            overflow: hidden;
            border: 1px solid transparent;
            background: var(--linen);
            cursor: pointer;
            opacity: .64;
            transition: opacity .25s ease, border-color .25s ease;
        }

        .product-gallery-thumbnail::after {
            position: absolute;
            right: 0;
            bottom: 0;
            left: 0;
            height: 2px;
            background: var(--copper);
            content: "";
            transform: scaleX(0);
            transform-origin: left;
            transition: transform .35s cubic-bezier(.22, 1, .36, 1);
        }

        .product-gallery-thumbnail:hover,
        .product-gallery-thumbnail.is-active {
            border-color: #999086;
            opacity: 1;
        }

        .product-gallery-thumbnail.is-active::after { transform: scaleX(1); }
        .product-gallery-thumbnail img {
            width: 100%;
            aspect-ratio: 4 / 5;
            object-fit: cover;
        }

        /* Main image */
        .product-gallery-stage {
            position: relative;
            grid-area: stage;
            min-width: 0;
            overflow: hidden;
            background: #eee8df;
            isolation: isolate;
        }

        .product-gallery-main-button {
            position: relative;
            display: block;
            width: 100%;
            padding: 0;
            overflow: hidden;
            border: 0;
            background: transparent;
            cursor: zoom-in;
        }

        .product-gallery-main-image {
            width: 100%;
            aspect-ratio: 4 / 5;
            object-fit: cover;
            transform: scale(1);
            transform-origin: 50% 50%;
            transition: opacity .22s ease, transform .5s cubic-bezier(.22, 1, .36, 1);
            will-change: transform, opacity;
        }

        .product-gallery-main-image.is-changing { opacity: .25; }
        .product-gallery-stage.is-zooming .product-gallery-main-image {
            transform: scale(1.85);
            transition: transform .18s ease-out;
        }

        .product-gallery-badge {
            position: absolute;
            z-index: 3;
            top: 1rem;
            left: 1rem;
            display: inline-flex;
            align-items: center;
            min-height: 28px;
            padding: .35rem .75rem;
            background: rgba(18, 18, 18, .92);
            color: #fff;
            font-size: 9px;
            letter-spacing: .16em;
            text-transform: uppercase;
            pointer-events: none;
        }

        .product-gallery-zoom-hint {
            position: absolute;
            z-index: 3;
            right: 1rem;
            bottom: 1rem;
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .55rem .75rem;
            background: rgba(250, 248, 245, .92);
            box-shadow: 0 6px 20px rgba(18, 18, 18, .08);
            color: #37332f;
            font-size: 9px;
            letter-spacing: .13em;
            text-transform: uppercase;
            pointer-events: none;
            transition: opacity .2s ease;
        }

        .product-gallery-stage.is-zooming .product-gallery-zoom-hint { opacity: 0; }

        .product-gallery-arrow {
            position: absolute;
            z-index: 4;
            top: 50%;
            display: grid;
            width: 42px;
            height: 42px;
            place-items: center;
            border: 1px solid rgba(18, 18, 18, .12);
            border-radius: 50%;
            background: rgba(250, 248, 245, .92);
            box-shadow: 0 8px 24px rgba(18, 18, 18, .08);
            color: var(--ink);
            opacity: 0;
            transform: translateY(-50%);
            transition: color .25s ease, background .25s ease, opacity .25s ease;
        }

        .product-gallery-stage:hover .product-gallery-arrow,
        .product-gallery-arrow:focus-visible { opacity: 1; }
        .product-gallery-arrow:hover { background: var(--ink); color: #fff; }
        .product-gallery-arrow--previous { left: 1rem; }
        .product-gallery-arrow--next { right: 1rem; }

        .product-gallery-position {
            position: absolute;
            z-index: 3;
            top: 1rem;
            right: 1rem;
            min-width: 54px;
            padding: .4rem .6rem;
            background: rgba(250, 248, 245, .9);
            color: #4c4742;
            font-size: 10px;
            letter-spacing: .12em;
            text-align: center;
            pointer-events: none;
        }

        .product-gallery-dots {
            display: none;
            align-items: center;
            justify-content: center;
            gap: .45rem;
            padding-top: .85rem;
        }

        .product-gallery-dot {
            width: 6px;
            height: 6px;
            padding: 0;
            border: 0;
            border-radius: 50%;
            background: #c7c0b8;
            transition: width .25s ease, border-radius .25s ease, background .25s ease;
        }

        .product-gallery-dot.is-active {
            width: 20px;
            border-radius: 10px;
            background: var(--copper);
        }

        /* Product information */
        .product-show-summary {
            position: sticky;
            top: 165px;
            grid-area: details;
            align-self: start;
            padding-left: clamp(.4rem, 1.5vw, 1.25rem);
        }

        .product-show-title {
            margin-top: .55rem;
            font-size: clamp(2.6rem, 4vw, 4.4rem);
            letter-spacing: -.025em;
        }

        .product-show-price-row {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: .7rem 1rem;
            margin-top: 1.2rem;
        }

        .product-show-price { font-size: 1.1rem; }

        .product-show-description {
            margin-top: 1.5rem;
            color: #6e6a65;
            line-height: 1.8;
        }

        .product-option-section + .product-option-section { margin-top: 1.5rem; }
        .product-colour-option {
            position: relative;
            display: inline-grid;
            width: 34px;
            height: 34px;
            place-items: center;
            border-radius: 50%;
            cursor: pointer;
        }

        .product-colour-option::after {
            width: 27px;
            height: 27px;
            border: 4px solid var(--bone);
            border-radius: 50%;
            background: var(--swatch-colour, #ccc);
            box-shadow: 0 0 0 1px #c8c1b8;
            content: "";
        }

        .product-colour-option:has(input:checked)::after {
            box-shadow: 0 0 0 1px var(--bone), 0 0 0 2px var(--ink);
        }

        .product-add-row {
            display: grid;
            grid-template-columns: 140px minmax(0, 1fr);
            gap: .75rem;
            margin-top: 1.8rem;
        }

        .product-add-row .qty {
            width: 100%;
            grid-template-columns: 42px minmax(44px, 1fr) 42px;
        }
        .product-add-row .btn { min-height: 45px; }
        .product-form-error { margin-top: .8rem; color: #a52f27; font-size: 12px; }
        .product-stock-line {
            display: flex;
            align-items: center;
            gap: .45rem;
            margin-top: 1.1rem;
            color: #77716b;
            font-size: 11px;
        }
        .product-stock-dot { width: 6px; height: 6px; border-radius: 50%; background: #3f755e; }
        .product-stock-dot.is-sold-out { background: #a52f27; }

        /* Smooth single-open product accordion */
        .product-information-accordions {
            margin-top: 2rem;
            border-bottom: 1px solid var(--hairline);
        }

        .product-information-accordion {
            border-top: 1px solid var(--hairline);
        }

        .product-information-trigger {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            min-height: 56px;
            padding: .9rem 0;
            border: 0;
            background: transparent;
            color: var(--ink);
            font-size: 14px;
            text-align: left;
            cursor: pointer;
        }

        .product-information-trigger:hover {
            color: var(--copper);
        }

        .product-information-icon {
            position: relative;
            flex: 0 0 18px;
            width: 18px;
            height: 18px;
            margin-left: 1rem;
            transform: rotate(0deg);
            transition: transform .48s cubic-bezier(.22, 1, .36, 1);
        }

        .product-information-icon::before,
        .product-information-icon::after {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 12px;
            height: 1.5px;
            background: currentColor;
            content: "";
            transform: translate(-50%, -50%);
            transition: opacity .38s ease, transform .48s cubic-bezier(.22, 1, .36, 1);
        }

        .product-information-icon::after {
            transform: translate(-50%, -50%) rotate(90deg) scaleX(1);
        }

        .product-information-accordion.is-open .product-information-icon {
            transform: rotate(180deg);
        }

        .product-information-accordion.is-open .product-information-icon::after {
            opacity: 0;
            transform: translate(-50%, -50%) rotate(90deg) scaleX(0);
        }

        .product-information-panel {
            height: 0;
            overflow: hidden;
            opacity: 0;
            transition: height .45s cubic-bezier(.22, 1, .36, 1), opacity .3s ease;
        }

        .product-information-panel-inner {
            padding: 0 0 1.35rem;
            color: #67615a;
            line-height: 1.75;
            transform: translateY(-7px);
            transition: transform .4s cubic-bezier(.22, 1, .36, 1);
        }

        .product-information-accordion.is-open .product-information-panel {
            opacity: 1;
        }

        .product-information-accordion.is-open .product-information-panel-inner {
            transform: translateY(0);
        }

        @media (max-width: 1180px) {
            .product-show-layout {
                grid-template-columns: 76px minmax(0, 1fr);
                grid-template-areas: "thumbs stage" ". details";
            }
            .product-show-summary {
                position: static;
                max-width: 760px;
                padding-top: 1.5rem;
                padding-left: 0;
            }
        }

        @media (max-width: 720px) {
            .product-show-section { padding-top: 1rem; }
            .product-breadcrumbs { margin-bottom: 1rem; }
            .product-show-layout { display: flex; flex-direction: column; gap: 0; }
            .product-gallery-stage { width: 100%; order: 1; }
            .product-gallery-main-button { cursor: default; }
            .product-gallery-main-image { aspect-ratio: 4 / 5; }
            .product-gallery-thumbnails {
                width: 100%;
                max-height: none;
                order: 2;
                flex-direction: row;
                gap: .55rem;
                padding: .75rem 1px 2px;
                overflow-x: auto;
                overflow-y: hidden;
            }
            .product-gallery-thumbnail { width: 62px; }
            .product-gallery-dots { display: flex; }
            .product-gallery-zoom-hint { display: none; }
            .product-gallery-arrow { width: 38px; height: 38px; opacity: 1; }
            .product-gallery-arrow--previous { left: .75rem; }
            .product-gallery-arrow--next { right: .75rem; }
            .product-show-summary { width: 100%; order: 3; padding-top: 2rem; }
            .product-show-title { font-size: clamp(2.45rem, 12vw, 3.4rem); }
        }

        @media (max-width: 480px) {
            .product-add-row { grid-template-columns: 120px minmax(0, 1fr); gap: .55rem; }
            .product-add-row .btn { padding-right: .8rem; padding-left: .8rem; letter-spacing: .13em; }
        }

        @media (hover: none) {
            .product-gallery-main-button { cursor: default; }
            .product-gallery-stage.is-zooming .product-gallery-main-image { transform: none; }
        }

        @media (prefers-reduced-motion: reduce) {
            .product-gallery-main-image,
            .product-gallery-thumbnail,
            .product-gallery-dot { transition: none; }
        }
    </style>
@endpush

@section('content')
    <!-- SARAI PRODUCT SHOW V3 -->
    <section class="product-show-section">
        <div class="wrap">
            <nav class="product-breadcrumbs" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                <a href="{{ route('categories.show', $product->category) }}">
                    {{ $product->category->name }}
                </a>
                <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                <span aria-current="page">{{ $product->name }}</span>
            </nav>

            <div class="product-show-layout" data-product-gallery tabindex="-1">
                {{-- Side images --}}
                <div class="product-gallery-thumbnails" aria-label="Product images">
                    @foreach ($galleryImages as $index => $image)
                        <button
                            type="button"
                            class="product-gallery-thumbnail {{ $index === 0 ? 'is-active' : '' }}"
                            data-gallery-thumbnail
                            data-gallery-index="{{ $index }}"
                            aria-label="View image {{ $index + 1 }} of {{ $galleryImages->count() }}"
                            aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                        >
                            <img
                                src="{{ asset($image->path) }}"
                                alt="{{ $image->alt_text ?: $product->name . ' image ' . ($index + 1) }}"
                                width="160"
                                height="200"
                                loading="{{ $index < 3 ? 'eager' : 'lazy' }}"
                            >
                        </button>
                    @endforeach
                </div>

                {{-- Main image --}}
                <div class="product-gallery-stage" data-gallery-stage>
                    @if ($product->discount_percent)
                        <span class="product-gallery-badge">Save {{ $product->discount_percent }}%</span>
                    @elseif ($product->is_new)
                        <span class="product-gallery-badge">New arrival</span>
                    @elseif ($product->is_limited)
                        <span class="product-gallery-badge">Limited piece</span>
                    @endif

                    <button
                        type="button"
                        class="product-gallery-main-button"
                        data-gallery-zoom-area
                        aria-label="Hover over the image to zoom"
                    >
                        <img
                            class="product-gallery-main-image"
                            data-gallery-main-image
                            src="{{ asset($firstGalleryImage->path) }}"
                            alt="{{ $firstGalleryImage->alt_text ?: $product->name }}"
                            width="900"
                            height="1125"
                            fetchpriority="high"
                        >
                    </button>

                    @if ($galleryImages->count() > 1)
                        <button
                            type="button"
                            class="product-gallery-arrow product-gallery-arrow--previous"
                            data-gallery-previous
                            aria-label="Previous product image"
                        >
                            <i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
                        </button>

                        <button
                            type="button"
                            class="product-gallery-arrow product-gallery-arrow--next"
                            data-gallery-next
                            aria-label="Next product image"
                        >
                            <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                        </button>

                        <span class="product-gallery-position" aria-live="polite">
                            <span data-gallery-current>1</span> / {{ $galleryImages->count() }}
                        </span>
                    @endif

                    <span class="product-gallery-zoom-hint">
                        <i class="fa-solid fa-magnifying-glass-plus" aria-hidden="true"></i>
                        Hover to zoom
                    </span>
                </div>

                @if ($galleryImages->count() > 1)
                    <div class="product-gallery-dots" aria-label="Choose product image">
                        @foreach ($galleryImages as $index => $image)
                            <button
                                type="button"
                                class="product-gallery-dot {{ $index === 0 ? 'is-active' : '' }}"
                                data-gallery-dot
                                data-gallery-index="{{ $index }}"
                                aria-label="Go to image {{ $index + 1 }}"
                                aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                            ></button>
                        @endforeach
                    </div>
                @endif

                {{-- Product details --}}
                <aside class="product-show-summary">
                    <p class="eyebrow">
                        {{ $product->category->name }}
                        @if ($product->material) · {{ $product->material }} @endif
                    </p>

                    <h1 class="product-show-title">{{ $product->name }}</h1>

                    <div class="product-show-price-row">
                        <p class="product-show-price">
                            <strong class="{{ $product->compare_price ? 'price-sale' : '' }}">
                                ৳{{ number_format($product->price) }}
                            </strong>
                            @if ($product->compare_price)
                                <span class="price-old">৳{{ number_format($product->compare_price) }}</span>
                            @endif
                        </p>

                    </div>

                    @if ($product->short_description)
                        <p class="product-show-description">{{ $product->short_description }}</p>
                    @endif

                    <form action="{{ route('cart.store', $product) }}" method="post" class="mt-8">
                        @csrf

                        @if (count($product->colors ?? []))
                            <fieldset class="product-option-section">
                                <legend class="label">Colour</legend>
                                <div class="swatches">
                                    @foreach ($product->colors as $index => $color)
                                        <label
                                            class="product-colour-option"
                                            style="--swatch-colour: {{ $color['hex'] ?? '#ccc' }}"
                                            title="{{ $color['name'] }}"
                                        >
                                            <input
                                                class="sr-only"
                                                type="radio"
                                                name="color"
                                                value="{{ $color['name'] }}"
                                                @checked(old('color', $product->colors[0]['name'] ?? null) === $color['name'])
                                            >
                                            <span class="sr-only">{{ $color['name'] }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </fieldset>
                        @endif

                        @if (count($product->sizes ?? []))
                            <fieldset class="product-option-section">
                                <legend class="label w-full">
                                    Size
                                    <a
                                        class="float-right normal-case tracking-normal underline"
                                        href="{{ route('pages.show', 'size-guide') }}"
                                    >Size guide</a>
                                </legend>
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($product->sizes as $index => $size)
                                        <label class="option relative cursor-pointer">
                                            <input
                                                type="radio"
                                                name="size"
                                                value="{{ $size }}"
                                                @checked(old('size', $product->sizes[0] ?? null) === $size)
                                            >
                                            <span>{{ $size }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </fieldset>
                        @endif

                        <div class="product-add-row">
                            <div class="qty" data-qty>
                                <button type="button" data-minus aria-label="Decrease quantity">−</button>
                                <input
                                    class="text-center"
                                    name="quantity"
                                    value="{{ old('quantity', 1) }}"
                                    inputmode="numeric"
                                    aria-label="Quantity"
                                >
                                <button type="button" data-plus aria-label="Increase quantity">+</button>
                            </div>

                            <button class="btn btn-dark" @disabled($product->stock < 1)>
                                {{ $product->stock > 0 ? 'Add to bag' : 'Sold out' }}
                            </button>
                        </div>

                        @if ($errors->any())
                            <div class="product-form-error" role="alert">{{ $errors->first() }}</div>
                        @endif
                    </form>

                    <p class="product-stock-line">
                        <span class="product-stock-dot {{ $product->stock < 1 ? 'is-sold-out' : '' }}"></span>
                        @if ($product->stock > 0)
                            In stock · {{ $product->stock }} available
                        @else
                            Currently out of stock
                        @endif
                    </p>

                    <div class="product-information-accordions" data-product-accordions>
                        <section class="product-information-accordion is-open" data-product-accordion>
                            <button
                                type="button"
                                class="product-information-trigger"
                                data-product-accordion-trigger
                                aria-expanded="true"
                                aria-controls="product-description-panel"
                            >
                                <span>Description</span>
                                <span class="product-information-icon" aria-hidden="true"></span>
                            </button>

                            <div
                                id="product-description-panel"
                                class="product-information-panel"
                                data-product-accordion-panel
                                style="height: auto;"
                            >
                                <div class="product-information-panel-inner">
                                    {!! nl2br(e($product->description)) !!}
                                </div>
                            </div>
                        </section>

                        <section class="product-information-accordion" data-product-accordion>
                            <button
                                type="button"
                                class="product-information-trigger"
                                data-product-accordion-trigger
                                aria-expanded="false"
                                aria-controls="product-materials-panel"
                            >
                                <span>Materials &amp; care</span>
                                <span class="product-information-icon" aria-hidden="true"></span>
                            </button>

                            <div
                                id="product-materials-panel"
                                class="product-information-panel"
                                data-product-accordion-panel
                            >
                                <div class="product-information-panel-inner">
                                    {{ $product->material }}. Handcrafted pieces may show small variations.
                                    Follow the care label supplied with your item.
                                </div>
                            </div>
                        </section>

                        <section class="product-information-accordion" data-product-accordion>
                            <button
                                type="button"
                                class="product-information-trigger"
                                data-product-accordion-trigger
                                aria-expanded="false"
                                aria-controls="product-delivery-panel"
                            >
                                <span>Delivery &amp; returns</span>
                                <span class="product-information-icon" aria-hidden="true"></span>
                            </button>

                            <div
                                id="product-delivery-panel"
                                class="product-information-panel"
                                data-product-accordion-panel
                            >
                                <div class="product-information-panel-inner">
                                    Delivery across Bangladesh in 2–5 working days. Eligible unused items
                                    may be returned within 7 days.
                                </div>
                            </div>
                        </section>
                    </div>

                    <p class="mt-5 text-xs muted">SKU: {{ $product->sku }}</p>
                </aside>
            </div>
        </div>
    </section>

    @if ($related->isNotEmpty())
        <section class="section bg-white">
            <div class="wrap">
                <p class="eyebrow">You may also like</p>
                <h2 class="heading mt-2 mb-10">Related pieces</h2>
                <div class="product-grid">
                    @foreach ($related as $item)
                        <x-product-card :product="$item" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            /* Single-open animated product accordion. */
            const accordions = [
                ...document.querySelectorAll('[data-product-accordion]')
            ];

            const closeAccordion = (accordion) => {
                if (!accordion.classList.contains('is-open')) return;

                const trigger = accordion.querySelector('[data-product-accordion-trigger]');
                const panel = accordion.querySelector('[data-product-accordion-panel]');
                if (!trigger || !panel) return;

                panel.style.height = `${panel.scrollHeight}px`;
                panel.offsetHeight;
                accordion.classList.remove('is-open');
                trigger.setAttribute('aria-expanded', 'false');

                requestAnimationFrame(() => {
                    panel.style.height = '0px';
                });
            };

            const openAccordion = (accordion) => {
                if (accordion.classList.contains('is-open')) return;

                const trigger = accordion.querySelector('[data-product-accordion-trigger]');
                const panel = accordion.querySelector('[data-product-accordion-panel]');
                if (!trigger || !panel) return;

                panel.style.height = '0px';
                accordion.classList.add('is-open');
                trigger.setAttribute('aria-expanded', 'true');

                requestAnimationFrame(() => {
                    panel.style.height = `${panel.scrollHeight}px`;
                });
            };

            accordions.forEach((accordion) => {
                const trigger = accordion.querySelector('[data-product-accordion-trigger]');
                const panel = accordion.querySelector('[data-product-accordion-panel]');
                if (!trigger || !panel) return;

                trigger.addEventListener('click', () => {
                    const wasOpen = accordion.classList.contains('is-open');

                    accordions.forEach((otherAccordion) => {
                        if (otherAccordion !== accordion) {
                            closeAccordion(otherAccordion);
                        }
                    });

                    wasOpen ? closeAccordion(accordion) : openAccordion(accordion);
                });

                panel.addEventListener('transitionend', (event) => {
                    if (
                        event.propertyName === 'height'
                        && accordion.classList.contains('is-open')
                    ) {
                        panel.style.height = 'auto';
                    }
                });
            });

            const gallery = document.querySelector('[data-product-gallery]');
            if (!gallery) return;

            const images = {{ Illuminate\Support\Js::from($galleryPayload) }};
            const stage = gallery.querySelector('[data-gallery-stage]');
            const zoomArea = gallery.querySelector('[data-gallery-zoom-area]');
            const mainImage = gallery.querySelector('[data-gallery-main-image]');
            const previousButton = gallery.querySelector('[data-gallery-previous]');
            const nextButton = gallery.querySelector('[data-gallery-next]');
            const currentLabel = gallery.querySelector('[data-gallery-current]');
            const thumbnails = [...gallery.querySelectorAll('[data-gallery-thumbnail]')];
            const dots = [...gallery.querySelectorAll('[data-gallery-dot]')];
            const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
            const canHover = window.matchMedia('(hover: hover) and (pointer: fine)');

            let activeIndex = 0;
            let autoChangeTimer = null;
            let changeTimer = null;
            let touchStartX = null;
            let touchStartY = null;

            const normaliseIndex = (index) => (index + images.length) % images.length;

            const preloadImages = () => {
                images.slice(1).forEach((image) => {
                    const preload = new Image();
                    preload.src = image.src;
                });
            };

            const updateControls = () => {
                thumbnails.forEach((thumbnail, index) => {
                    const isActive = index === activeIndex;
                    thumbnail.classList.toggle('is-active', isActive);
                    thumbnail.setAttribute('aria-current', isActive ? 'true' : 'false');

                    if (isActive) {
                        thumbnail.scrollIntoView({
                            behavior: reduceMotion.matches ? 'auto' : 'smooth',
                            block: 'nearest',
                            inline: 'nearest',
                        });
                    }
                });

                dots.forEach((dot, index) => {
                    const isActive = index === activeIndex;
                    dot.classList.toggle('is-active', isActive);
                    dot.setAttribute('aria-current', isActive ? 'true' : 'false');
                });

                if (currentLabel) currentLabel.textContent = activeIndex + 1;
            };

            const showImage = (requestedIndex) => {
                if (!images.length || !mainImage) return;

                const nextIndex = normaliseIndex(requestedIndex);
                if (nextIndex === activeIndex && mainImage.src === images[nextIndex].src) return;

                activeIndex = nextIndex;
                updateControls();
                window.clearTimeout(changeTimer);
                mainImage.classList.add('is-changing');

                changeTimer = window.setTimeout(() => {
                    mainImage.src = images[activeIndex].src;
                    mainImage.alt = images[activeIndex].alt;
                    requestAnimationFrame(() => mainImage.classList.remove('is-changing'));
                }, reduceMotion.matches ? 0 : 120);
            };

            const stopAutoChange = () => {
                window.clearInterval(autoChangeTimer);
                autoChangeTimer = null;
            };

            const startAutoChange = () => {
                stopAutoChange();
                if (
                    images.length < 2
                    || reduceMotion.matches
                    || document.hidden
                    || (canHover.matches && stage?.matches(':hover'))
                ) return;
                autoChangeTimer = window.setInterval(() => showImage(activeIndex + 1), 5000);
            };

            const selectImage = (index) => {
                showImage(index);
                startAutoChange();
            };

            thumbnails.forEach((thumbnail) => {
                thumbnail.addEventListener('click', () => {
                    selectImage(Number(thumbnail.dataset.galleryIndex));
                });
            });

            dots.forEach((dot) => {
                dot.addEventListener('click', () => selectImage(Number(dot.dataset.galleryIndex)));
            });

            previousButton?.addEventListener('click', () => selectImage(activeIndex - 1));
            nextButton?.addEventListener('click', () => selectImage(activeIndex + 1));

            gallery.addEventListener('keydown', (event) => {
                if (event.key === 'ArrowLeft') {
                    event.preventDefault();
                    selectImage(activeIndex - 1);
                }
                if (event.key === 'ArrowRight') {
                    event.preventDefault();
                    selectImage(activeIndex + 1);
                }
            });

            stage?.addEventListener('mouseenter', stopAutoChange);
            stage?.addEventListener('mouseleave', startAutoChange);
            stage?.addEventListener('focusin', stopAutoChange);
            stage?.addEventListener('focusout', startAutoChange);

            /* Cursor-position zoom on desktop. */
            zoomArea?.addEventListener('mouseenter', () => {
                if (!canHover.matches) return;
                stage.classList.add('is-zooming');
                stopAutoChange();
            });

            zoomArea?.addEventListener('mousemove', (event) => {
                if (!canHover.matches || !mainImage) return;
                const rectangle = zoomArea.getBoundingClientRect();
                const x = ((event.clientX - rectangle.left) / rectangle.width) * 100;
                const y = ((event.clientY - rectangle.top) / rectangle.height) * 100;
                mainImage.style.transformOrigin = `${x}% ${y}%`;
            });

            zoomArea?.addEventListener('mouseleave', () => {
                stage.classList.remove('is-zooming');
                mainImage.style.transformOrigin = '50% 50%';
                startAutoChange();
            });

            /* Swipe navigation on phones/tablets. */
            zoomArea?.addEventListener('touchstart', (event) => {
                touchStartX = event.changedTouches[0].clientX;
                touchStartY = event.changedTouches[0].clientY;
                stopAutoChange();
            }, { passive: true });

            zoomArea?.addEventListener('touchend', (event) => {
                if (touchStartX === null || touchStartY === null) return;

                const differenceX = event.changedTouches[0].clientX - touchStartX;
                const differenceY = event.changedTouches[0].clientY - touchStartY;

                if (Math.abs(differenceX) > 45 && Math.abs(differenceX) > Math.abs(differenceY)) {
                    showImage(activeIndex + (differenceX < 0 ? 1 : -1));
                }

                touchStartX = null;
                touchStartY = null;
                startAutoChange();
            }, { passive: true });

            document.addEventListener('visibilitychange', () => {
                document.hidden ? stopAutoChange() : startAutoChange();
            });

            const handleMotionPreference = () => {
                reduceMotion.matches ? stopAutoChange() : startAutoChange();
            };

            if (typeof reduceMotion.addEventListener === 'function') {
                reduceMotion.addEventListener('change', handleMotionPreference);
            }

            preloadImages();
            updateControls();
            startAutoChange();
        });
    </script>
@endpush
