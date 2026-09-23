@extends('layouts.store')

@section('title', 'AATCHALA Journal — Craft, Culture & Style')
@section('description', 'Stories from Bangladeshi artisans, handloom traditions, materials, style and culture.')

@section('content')

@php
    $featured = $posts->onFirstPage() ? $posts->getCollection()->first() : null;
    $stories  = $featured ? $posts->getCollection()->slice(1)->values() : $posts->getCollection();
    $offset   = $posts->firstItem() + ($featured ? 1 : 0);

    $readTime = function ($post) {
        $text  = trim(preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags(str_replace('<', ' <', $post->content ?? '')))));
        $words = $text === '' ? 0 : count(preg_split('/\s+/u', $text));
        return max(1, (int) ceil($words / 200));
    };
@endphp

<script>document.documentElement.classList.add('j-anim');</script>

<div class="wrap section">

    {{-- =========================================================
        MASTHEAD
    ========================================================== --}}
    <header class="mb-14 grid gap-8 border-b j-border pb-10 md:mb-20 md:grid-cols-12 md:items-end">

        <div class="md:col-span-7">
            <p class="eyebrow j-reveal">Craft · Culture · Style</p>
            <h1 class="display j-masthead mt-3">
                <span class="j-line"><span>The</span></span>
                <span class="j-line"><span style="--i: 1"><em>Journal</em></span></span>
            </h1>
        </div>

        <div class="md:col-span-5 md:pb-3">
            <p class="j-reveal text-lg leading-relaxed j-muted" style="--d: .3s">
                Stories from Bangladeshi artisans, handloom traditions, the materials we love and the culture that shapes every piece.
            </p>
            <p class="j-reveal mt-5 text-[11px] uppercase tracking-[0.25em] j-muted" style="--d: .45s">
                {{ $posts->total() }} {{ Str::plural('story', $posts->total()) }}
                @if($posts->lastPage() > 1)
                    · Page {{ $posts->currentPage() }} of {{ $posts->lastPage() }}
                @endif
            </p>
        </div>

    </header>


    @if($posts->isEmpty())

        {{-- =========================================================
            EMPTY STATE
        ========================================================== --}}
        <div class="j-reveal py-24 text-center">
            <p class="eyebrow">Coming soon</p>
            <h2 class="mt-3 text-3xl">Our first stories are being written.</h2>
            <p class="mt-3 j-muted">Check back shortly for tales of craft and culture.</p>
        </div>

    @else

        {{-- =========================================================
            FEATURED STORY (page 1 only)
        ========================================================== --}}
        @if($featured)
            <article class="j-card j-featured mb-20 md:mb-28">
                <a href="{{ route('journal.show', $featured) }}" class="grid gap-8 md:grid-cols-12 md:items-center md:gap-12">

                    <div class="j-reveal j-card-img j-img-reveal aspect-[4/3] md:col-span-7 md:aspect-[5/4]">
                        @if($featured->image)
                            <img src="{{ asset($featured->image) }}" alt="{{ $featured->title }}" fetchpriority="high">
                        @endif
                        <span class="j-badge">Featured story</span>
                    </div>

                    <div class="md:col-span-5">
                        <p class="j-reveal j-number" style="--d: .15s">01</p>

                        <p class="j-reveal mt-4 text-[11px] uppercase tracking-[0.22em] j-muted" style="--d: .2s">
                            {{ $featured->category }}
                            @if($featured->category && $featured->published_at) · @endif
                            {{ $featured->published_at?->format('d F Y') }}
                        </p>

                        <h2 class="j-reveal j-card-title mt-3 text-4xl leading-[1.15] md:text-5xl" style="--d: .3s">
                            <span>{{ $featured->title }}</span>
                        </h2>

                        @if($featured->excerpt)
                            <p class="j-reveal mt-5 text-lg leading-relaxed j-muted" style="--d: .4s">
                                {{ $featured->excerpt }}
                            </p>
                        @endif

                        <p class="j-reveal j-read mt-8" style="--d: .5s">
                            Read the story
                            <span class="j-arrow">→</span>
                            <span class="ml-3 text-[11px] uppercase tracking-[0.2em] j-muted">{{ $readTime($featured) }} min</span>
                        </p>
                    </div>

                </a>
            </article>
        @endif


        {{-- =========================================================
            STAGGERED EDITORIAL GRID
        ========================================================== --}}
        @if($stories->isNotEmpty())
            <div class="j-grid grid gap-x-10 gap-y-16 md:grid-cols-2 lg:grid-cols-3">

                @foreach($stories as $post)
                    @php
                        $number = str_pad($offset + $loop->index, 2, '0', STR_PAD_LEFT);
                        $aspect = $loop->index % 3 === 1 ? 'aspect-[4/5]' : 'aspect-[4/3]';
                    @endphp

                    <article class="j-card j-reveal" style="--d: {{ ($loop->index % 3) * 0.12 }}s">
                        <a href="{{ route('journal.show', $post) }}" class="block">

                            <div class="mb-5 flex items-center gap-4">
                                <span class="j-number text-base">{{ $number }}</span>
                                <span class="h-px flex-1 j-rule"></span>
                                <span class="text-[11px] uppercase tracking-[0.2em] j-muted">{{ $readTime($post) }} min</span>
                            </div>

                            <div class="j-card-img {{ $aspect }}">
                                @if($post->image)
                                    <img src="{{ asset($post->image) }}" alt="{{ $post->title }}" loading="lazy">
                                @endif
                            </div>

                            <p class="mt-5 text-[11px] uppercase tracking-[0.22em] j-muted">
                                {{ $post->category }}
                                @if($post->category && $post->published_at) · @endif
                                {{ $post->published_at?->format('d F Y') }}
                            </p>

                            <h2 class="j-card-title mt-2 text-2xl leading-snug md:text-[1.75rem]">
                                <span>{{ $post->title }}</span>
                            </h2>

                            @if($post->excerpt)
                                <p class="mt-3 line-clamp-3 leading-relaxed j-muted">{{ $post->excerpt }}</p>
                            @endif

                            <p class="j-read mt-5 text-sm">
                                Read more <span class="j-arrow">→</span>
                            </p>

                        </a>
                    </article>
                @endforeach

            </div>
        @endif


        {{-- =========================================================
            PAGINATION
        ========================================================== --}}
        @if($posts->hasPages())
            <div class="pagination mt-20 border-t j-border pt-10 lg:mt-28">
                {{ $posts->links() }}
            </div>
        @endif

    @endif

</div>



{{-- =========================================================
    STYLES
========================================================== --}}
<style>
    :root {
        --j-accent: #b87333;
        --j-ink: #1c1917;
        --j-muted: #78716c;
        --j-border: #e7e5e4;
        --j-soft: #f5f1ec;
        --j-ease: cubic-bezier(.22, 1, .36, 1);
    }

    .j-muted  { color: var(--j-muted); }
    .j-border { border-color: var(--j-border); }
    .j-rule   { background: var(--j-border); }

    /* ---------- Masthead ---------- */
    .j-masthead { line-height: .95; }
    .j-masthead em { font-style: italic; color: var(--j-accent); }
    .j-line { display: block; overflow: hidden; padding-bottom: .06em; }
    .j-anim .j-line > span {
        display: inline-block; transform: translateY(105%);
        animation: j-up 1.1s var(--j-ease) forwards;
        animation-delay: calc(var(--i, 0) * 120ms + 100ms);
    }
    @keyframes j-up { to { transform: translateY(0); } }

    /* ---------- Numbers ---------- */
    .j-number {
        font-family: Georgia, 'Times New Roman', serif;
        font-style: italic;
        font-size: 1.5rem;
        color: var(--j-accent);
        line-height: 1;
    }
    .j-featured .j-number { font-size: 3.5rem; }

    /* ---------- Cards ---------- */
    .j-card-img {
        position: relative; overflow: hidden;
        border-radius: .75rem; background: var(--j-soft);
    }
    .j-card-img img {
        width: 100%; height: 100%; object-fit: cover;
        transition: transform 1.2s var(--j-ease), filter .6s;
    }
    .j-card:hover .j-card-img img { transform: scale(1.06); }

    .j-badge {
        position: absolute; left: 1rem; top: 1rem;
        background: rgba(255, 255, 255, .92); color: var(--j-ink);
        font-size: 10px; font-weight: 600; letter-spacing: .2em; text-transform: uppercase;
        padding: .45rem .8rem; border-radius: 999px; backdrop-filter: blur(6px);
    }

    .j-card-title span {
        background-image: linear-gradient(currentColor, currentColor);
        background-size: 0% 1px; background-repeat: no-repeat; background-position: 0 100%;
        transition: background-size .7s var(--j-ease);
    }
    .j-card:hover .j-card-title span { background-size: 100% 1px; }

    .j-read { display: inline-flex; align-items: center; gap: .5rem; font-weight: 500; }
    .j-arrow { display: inline-block; transition: transform .45s var(--j-ease); color: var(--j-accent); }
    .j-card:hover .j-arrow { transform: translateX(6px); }

    /* ---------- Staggered layout: middle column drops down ---------- */
    @media (min-width: 1024px) {
        .j-grid > :nth-child(3n + 2) { margin-top: 6rem; }
        .j-grid { padding-bottom: 2rem; }
    }
    @media (min-width: 768px) and (max-width: 1023px) {
        .j-grid > :nth-child(2n) { margin-top: 4rem; }
    }

    /* ---------- Animations ---------- */
    .j-anim .j-reveal {
        opacity: 0; transform: translateY(32px);
        transition: opacity 1s var(--j-ease), transform 1s var(--j-ease);
        transition-delay: var(--d, 0s);
    }
    .j-anim .j-reveal.is-visible { opacity: 1; transform: none; }

    .j-anim .j-img-reveal { clip-path: inset(0 0 100% 0 round .75rem); opacity: 1; transform: none; }
    .j-anim .j-img-reveal img { transform: scale(1.15); }
    .j-anim .j-img-reveal.is-visible {
        clip-path: inset(0 0 0 0 round .75rem);
        transition: clip-path 1.3s var(--j-ease);
    }
    .j-anim .j-img-reveal.is-visible img { transform: scale(1); }
    .j-anim .j-card:hover .j-img-reveal.is-visible img { transform: scale(1.06); }

    @media (prefers-reduced-motion: reduce) {
        .j-anim .j-line > span { animation: none; transform: none; }
        .j-anim .j-reveal,
        .j-anim .j-img-reveal { opacity: 1; transform: none; clip-path: none; transition: none; }
        .j-anim .j-img-reveal img { transform: none; }
    }
</style>



{{-- =========================================================
    SCRIPTS
========================================================== --}}
<script>
(function () {
    const els = document.querySelectorAll('.j-reveal');
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (reduceMotion || !('IntersectionObserver' in window)) {
        els.forEach(el => el.classList.add('is-visible'));
        return;
    }

    const observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -5% 0px' });

    els.forEach(el => observer.observe(el));
})();
</script>

@endsection