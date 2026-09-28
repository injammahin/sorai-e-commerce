@extends('layouts.store')


@section(
    'title',
    'AATCHALA — Authentic Bangladeshi Craft, Handloom & Lifestyle'
)


@section(
    'description',
    'Discover curated Jamdani, Nakshi Kantha, Shital Pati, handloom fashion, pottery, jewellery, jute, cane and home décor made by Bangladeshi artisans.'
)



@push('head')

<style>

    /* =========================================================
       MAKER STORY SECTION
    ========================================================= */

    .home-maker-story {
        width: 100%;

        padding:
            clamp(4.5rem, 7vw, 7rem)
            0;

        background: #173d32;

        color: #ffffff;
    }


    .home-maker-story-grid {
        display: grid;

        grid-template-columns:
            minmax(0, 1.08fr)
            minmax(0, .92fr);

        align-items: center;

        gap:
            clamp(
                2.5rem,
                5vw,
                5rem
            );
    }


    /* =========================================================
       IMAGE
    ========================================================= */

    .home-maker-story-media {
        position: relative;

        width: 100%;

        /*
        |--------------------------------------------------------------------------
        | Taller desktop image
        |--------------------------------------------------------------------------
        */

        height:
            clamp(
                460px,
                32vw,
                540px
            );

        overflow: hidden;

        background: #102f28;
    }


    .home-maker-story-media img {
        display: block;

        width: 100%;
        height: 100%;

        object-fit: cover;
        object-position: center;

        margin: 0;
        padding: 0;

        transition:
            transform
            .7s
            cubic-bezier(.22, 1, .36, 1);
    }


    .home-maker-story-media:hover img {
        transform: scale(1.025);
    }


    /* =========================================================
       CONTENT
    ========================================================= */

    .home-maker-story-content {
        width: 100%;

        max-width: 620px;
    }


    .home-maker-story-eyebrow {
        color:
            rgba(
                255,
                255,
                255,
                .62
            );

        font-size: 10px;

        font-weight: 600;

        letter-spacing: .2em;

        text-transform: uppercase;
    }


    .home-maker-story-title {
        max-width: 640px;

        margin-top: 1rem;

        font-family:
            'Cormorant Garamond',
            Georgia,
            serif;

        font-size:
            clamp(
                3.5rem,
                5vw,
                5.5rem
            );

        font-weight: 400;

        line-height: .98;

        color: #ffffff;
    }


    .home-maker-story-description {
        max-width: 550px;

        margin-top: 1.6rem;

        color:
            rgba(
                255,
                255,
                255,
                .76
            );

        font-size: 14px;

        line-height: 1.75;
    }


    /* =========================================================
       BUTTON
    ========================================================= */

    .home-maker-story-button {
        min-height: 50px;

        display: inline-flex;

        align-items: center;
        justify-content: center;

        gap: .7rem;

        margin-top: 2rem;

        padding:
            0
            1.7rem;

        background: #faf8f5;

        color: #171512;

        font-size: 9px;

        font-weight: 600;

        letter-spacing: .16em;

        text-transform: uppercase;

        transition:
            color .25s ease,
            background .25s ease,
            transform .25s ease;
    }


    .home-maker-story-button i {
        font-size: 10px;

        transition:
            transform .25s ease;
    }


    .home-maker-story-button:hover {
        background: #c4622f;

        color: #ffffff;

        transform: translateY(-2px);
    }


    .home-maker-story-button:hover i {
        transform: translateX(4px);
    }


    /* =========================================================
       LARGE DESKTOP
    ========================================================= */

    @media (min-width: 1500px) {

        .home-maker-story-media {
            height: 560px;
        }

    }


    /* =========================================================
       TABLET
    ========================================================= */

    @media (max-width: 1024px) {

        .home-maker-story {
            padding:
                4rem
                0;
        }


        .home-maker-story-grid {
            grid-template-columns:
                minmax(0, 1fr)
                minmax(0, .9fr);

            gap: 2.5rem;
        }


        .home-maker-story-media {
            height: 420px;
        }


        .home-maker-story-title {
            font-size:
                clamp(
                    3rem,
                    5vw,
                    4rem
                );
        }

    }


    /* =========================================================
       MOBILE
    ========================================================= */

    @media (max-width: 760px) {

        .home-maker-story {
            padding:
                3rem
                0;
        }


        .home-maker-story-grid {
            display: flex;

            flex-direction: column;

            gap: 2rem;
        }


        .home-maker-story-media {
            width: 100%;

            height:
                clamp(
                    310px,
                    82vw,
                    390px
                );
        }


        .home-maker-story-content {
            max-width: none;
        }


        .home-maker-story-eyebrow {
            font-size: 8px;

            letter-spacing: .16em;
        }


        .home-maker-story-title {
            margin-top: .75rem;

            font-size:
                clamp(
                    2.8rem,
                    11vw,
                    4rem
                );

            line-height: .98;
        }


        .home-maker-story-description {
            margin-top: 1.2rem;

            font-size: 12px;

            line-height: 1.7;
        }


        .home-maker-story-button {
            min-height: 46px;

            margin-top: 1.5rem;

            padding:
                0
                1.4rem;
        }

    }


    /* =========================================================
       SMALL MOBILE
    ========================================================= */

    @media (max-width: 390px) {

        .home-maker-story {
            padding:
                2.5rem
                0;
        }


        .home-maker-story-media {
            height: 290px;
        }


        .home-maker-story-title {
            font-size: 2.7rem;
        }


        .home-maker-story-description {
            font-size: 11px;
        }

    }

</style>

@endpush



@section('content')


{{-- ============================================================
    POPUP
============================================================ --}}

<x-home-popup :banner="$popupBanner" />



{{-- ============================================================
    HERO
============================================================ --}}

<section
    class="hero"
    aria-label="Featured collections"
>


    @forelse($banners as $i => $banner)


        <article
            class="hero-slide {{ $i === 0 ? 'is-active' : '' }}"
        >


            <picture>


                @if($banner->mobile_image)

                    <source
                        media="(max-width:680px)"
                        srcset="{{ asset($banner->mobile_image) }}"
                    >

                @endif


                <img
                    src="{{ asset($banner->image) }}"
                    alt="{{ $banner->title }}"
                    fetchpriority="{{ $i === 0 ? 'high' : 'low' }}"
                >


            </picture>



            <div class="hero-copy">


                <p class="eyebrow !text-copper-300 mb-4">

                    {{ $banner->eyebrow }}

                </p>


                <h1 class="display">

                    {{ $banner->title }}

                </h1>


                <h2 class="text-2xl md:text-3xl mt-4 text-white">

                    {{ $banner->subtitle }}

                </h2>


                <p class="max-w-xl mt-5 text-white/75">

                    {{ $banner->description }}

                </p>



                <div class="flex flex-wrap gap-3 mt-8">


                    <a
                        class="btn btn-light"
                        href="{{ url($banner->button_url ?: '/new-arrivals') }}"
                    >

                        {{ $banner->button_text ?: 'Explore' }}

                        <i class="fa-solid fa-arrow-right"></i>

                    </a>


                </div>


            </div>


        </article>


    @empty


        <article class="hero-slide is-active">


            <img
                src="{{ asset('images/hero/hero-festive.webp') }}"
                alt="AATCHALA festive collection"
            >


            <div class="hero-copy">


                <p class="eyebrow text-white">

                    Bangladeshi Craft

                </p>


                <h1 class="display">

                    Tradition, gathered.

                </h1>


            </div>


        </article>


    @endforelse



    {{-- HERO DOTS --}}

    <div class="hero-dots">


        @foreach($banners as $i => $banner)

            <button
                class="hero-dot {{ $i === 0 ? 'is-active' : '' }}"
                aria-label="Show {{ $banner->title }}"
            ></button>

        @endforeach


    </div>


</section>



{{-- ============================================================
    CATEGORIES
============================================================ --}}

<section class="section">


    <div class="wrap">


        <div
            class="
                text-center
                max-w-2xl
                mx-auto
                mb-12
                reveal
            "
        >


            <p class="eyebrow">

                From across Bangladesh

            </p>


            <h2 class="heading mt-3">

                Made by hand. Chosen with care.

            </h2>


            <p class="muted mt-4">

                A modern gathering place for the work of weavers,
                potters, embroiderers, rural families and independent
                workshops.

            </p>


        </div>



        <div class="category-grid">


            @foreach($navCategories->take(8) as $category)


                <a
                    class="category-tile"
                    href="{{ route('categories.show', $category) }}"
                >


                    <img
                        src="{{ asset($category->image) }}"
                        loading="lazy"
                        alt="{{ $category->name }} — {{ $category->tagline }}"
                    >



                    <div class="category-caption">


                        <p class="eyebrow !text-white/70">

                            {{ $category->tagline }}

                        </p>


                        <h3 class="text-3xl mt-2">

                            {{ $category->name }}

                        </h3>


                        <span
                            class="
                                text-[10px]
                                uppercase
                                tracking-widest2
                            "
                        >

                            Discover

                            <i
                                class="
                                    fa-solid
                                    fa-arrow-right
                                    ml-2
                                "
                            ></i>

                        </span>


                    </div>


                </a>


            @endforeach


        </div>


    </div>


</section>



{{-- ============================================================
    NEW ARRIVALS
============================================================ --}}

<section class="section bg-white">


    <div class="wrap">


        <div
            class="
                flex
                items-end
                justify-between
                mb-10
                reveal
            "
        >


            <div>


                <p class="eyebrow">

                    Just arrived

                </p>


                <h2 class="heading mt-2">

                    New to the floor

                </h2>


            </div>



            <a
                class="
                    hidden
                    md:block
                    text-xs
                    uppercase
                    tracking-widest2
                "
                href="{{ route('new-arrivals') }}"
            >

                View all

                <i
                    class="
                        fa-solid
                        fa-arrow-right
                        ml-2
                    "
                ></i>

            </a>


        </div>



        <div class="product-grid">


            @foreach($newArrivals as $product)

                <x-product-card
                    :product="$product"
                />

            @endforeach


        </div>


    </div>


</section>



{{-- ============================================================
    CURATED COLLECTIONS
============================================================ --}}

<section class="section">


    <div class="wrap">


        <div class="text-center mb-10 reveal">


            <p class="eyebrow">

                Stories in cloth and clay

            </p>


            <h2 class="heading mt-2">

                Curated collections

            </h2>


        </div>



        <div class="collection-grid">


            @foreach($collections as $collection)


                <a
                    class="collection-card reveal"
                    href="{{ route('collections.show', $collection) }}"
                >


                    <img
                        src="{{ asset($collection->image) }}"
                        loading="lazy"
                        alt="{{ $collection->title }}"
                    >



                    <div class="category-caption">


                        <p class="eyebrow !text-white/70">

                            {{ $collection->tagline }}

                        </p>


                        <h3 class="text-4xl mt-2">

                            {{ $collection->title }}

                        </h3>


                    </div>


                </a>


            @endforeach


        </div>


    </div>


</section>



{{-- ============================================================
    MAKER STORY
============================================================ --}}

<section class="home-maker-story">


    <div class="wrap home-maker-story-grid">


        {{-- IMAGE --}}

        <div class="home-maker-story-media reveal">


            <img
                src="{{ asset('images/editorial/craft.webp') }}"
                loading="lazy"
                alt="Bangladeshi artisan craft process"
            >


        </div>



        {{-- CONTENT --}}

        <div class="home-maker-story-content reveal">


            <p class="home-maker-story-eyebrow">

                Maker → creation → AATCHALA → you

            </p>


            <h2 class="home-maker-story-title">

                The maker stays in the story.

            </h2>


            <p class="home-maker-story-description">

                We do not erase where a piece comes from.
                Regional technique, material, time and maker
                remain part of how each object is presented
                and valued.

            </p>


            <a
                href="{{ route('pages.show', 'about') }}"
                class="home-maker-story-button"
            >

                Our approach

                <i class="fa-solid fa-arrow-right"></i>

            </a>


        </div>


    </div>


</section>



{{-- ============================================================
    JOURNAL
============================================================ --}}

<section class="section">


    <div class="wrap">


        <div
            class="
                flex
                items-end
                justify-between
                mb-10
            "
        >


            <div>


                <p class="eyebrow">

                    The journal

                </p>


                <h2 class="heading mt-2">

                    Notes on making

                </h2>


            </div>



            <a
                href="{{ route('journal') }}"
                class="
                    text-xs
                    uppercase
                    tracking-widest2
                "
            >

                Read all

            </a>


        </div>



        <div
            class="
                grid
                md:grid-cols-3
                gap-8
            "
        >


            @foreach($posts as $post)


                <article class="reveal">


                    <a
                        href="{{ route('journal.show', $post) }}"
                    >


                        <div class="aspect-[4/3] overflow-hidden">


                            <img
                                class="
                                    w-full
                                    h-full
                                    object-cover
                                    transition
                                    duration-700
                                    hover:scale-105
                                "
                                src="{{ asset($post->image) }}"
                                alt="{{ $post->title }}"
                                loading="lazy"
                            >


                        </div>



                        <p class="eyebrow mt-5">

                            {{ $post->category }}

                            ·

                            {{ $post->published_at->format('d M Y') }}

                        </p>


                        <h3 class="text-3xl mt-2">

                            {{ $post->title }}

                        </h3>


                        <p class="muted mt-3">

                            {{ $post->excerpt }}

                        </p>


                    </a>


                </article>


            @endforeach


        </div>


    </div>


</section>


@endsection