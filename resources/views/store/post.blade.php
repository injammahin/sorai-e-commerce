@extends('layouts.store')

@section('title', $post->meta_title ?: $post->title . ' | SARAI Journal')
@section('description', $post->meta_description ?: $post->excerpt)
@section('og_type', 'article')
@if($post->image)
    @section('og_image', asset($post->image))
@endif

@section('content')

@php
    $journalUrl  = Route::has('journal.index') ? route('journal.index') : url('journal');
    $plainText   = trim(preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags(str_replace('<', ' <', $post->content ?? '')))));
    $wordCount   = $plainText === '' ? 0 : count(preg_split('/\s+/u', $plainText));
    $readMinutes = max(1, (int) ceil($wordCount / 200));
    $shareUrl    = urlencode(url()->current());
    $shareTitle  = urlencode($post->title);
    $authorName  = $post->author?->name;
    $titleWords  = preg_split('/\s+/u', trim($post->title));
@endphp

<script>document.documentElement.classList.add('j-anim');</script>

{{-- =========================================================
    READING PROGRESS
========================================================== --}}
<div class="j-progress" aria-hidden="true">
    <span id="j-progress-bar"></span>
</div>


<article id="j-article" class="j-article">

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <header class="wrap max-w-5xl pt-14 pb-12 text-center md:pt-24 md:pb-16">

        <nav class="j-reveal mb-8 flex items-center justify-center gap-3 text-[11px] uppercase tracking-[0.25em] j-muted">
            <a href="{{ $journalUrl }}" class="j-link">The Journal</a>
            @if($post->category)
                <span class="j-dot"></span>
                <span>{{ $post->category }}</span>
            @endif
        </nav>

        <h1 class="display j-title-words mx-auto max-w-4xl" aria-label="{{ $post->title }}">
            @foreach($titleWords as $word)
                <span class="j-word" aria-hidden="true"><span style="--i: {{ $loop->index }}">{{ $word }}</span></span>
            @endforeach
        </h1>

        @if($post->excerpt)
            <p class="j-reveal mx-auto mt-7 max-w-2xl text-lg leading-relaxed j-muted md:text-xl" style="--d: .35s">
                {{ $post->excerpt }}
            </p>
        @endif

        <div class="j-reveal mt-9 flex flex-wrap items-center justify-center gap-x-4 gap-y-2 text-xs uppercase tracking-[0.18em] j-muted" style="--d: .5s">
            @if($authorName)
                <span>By <span class="j-ink">{{ $authorName }}</span></span>
                <span class="j-dot"></span>
            @endif

            @if($post->published_at)
                <time datetime="{{ $post->published_at->toIso8601String() }}">
                    {{ $post->published_at->format('d F Y') }}
                </time>
                <span class="j-dot"></span>
            @endif

            <span>{{ $readMinutes }} min read</span>
        </div>

    </header>


    {{-- =========================================================
        HERO IMAGE
    ========================================================== --}}
    @if($post->image)
        <figure class="wrap max-w-6xl">
            <div class="j-hero">
                <img
                    id="j-hero-img"
                    src="{{ asset($post->image) }}"
                    alt="{{ $post->title }}"
                    fetchpriority="high"
                >
            </div>
        </figure>
    @endif


    {{-- =========================================================
        BODY
    ========================================================== --}}
    <div class="wrap max-w-6xl py-14 md:py-20">

        <div class="grid gap-10 lg:grid-cols-[110px_minmax(0,1fr)_110px]">

            {{-- Sticky share rail (desktop) --}}
            <aside class="hidden lg:block">
                <div class="sticky top-28 flex flex-col gap-3 text-[11px] uppercase tracking-[0.2em] j-muted">
                    <span class="mb-2 j-ink">Share</span>
                    <a class="j-link" target="_blank" rel="noopener" href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}">Facebook</a>
                    <a class="j-link" target="_blank" rel="noopener" href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareTitle }}">X</a>
                    <a class="j-link" target="_blank" rel="noopener" href="https://www.linkedin.com/sharing/share-offsite/?url={{ $shareUrl }}">LinkedIn</a>
                    <a class="j-link" target="_blank" rel="noopener" href="https://wa.me/?text={{ $shareTitle }}%20{{ $shareUrl }}">WhatsApp</a>
                    <button type="button" class="j-link js-copy-link text-left uppercase">Copy link</button>
                </div>
            </aside>

            {{-- Summernote content — rendered exactly as stored --}}
            <div id="j-content" class="j-content mx-auto w-full max-w-[720px]">
                {!! $post->content !!}
            </div>

            <div class="hidden lg:block"></div>

        </div>


        {{-- =====================================================
            ARTICLE FOOTER
        ====================================================== --}}
        <div class="j-reveal mx-auto mt-16 max-w-[720px] border-t j-border pt-8">

            <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">

                <div class="flex flex-wrap items-center gap-2">
                    <span class="mr-2 text-[11px] uppercase tracking-[0.2em] j-muted">Share</span>
                    <a class="j-pill" target="_blank" rel="noopener" href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}">Facebook</a>
                    <a class="j-pill" target="_blank" rel="noopener" href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareTitle }}">X</a>
                    <a class="j-pill" target="_blank" rel="noopener" href="https://wa.me/?text={{ $shareTitle }}%20{{ $shareUrl }}">WhatsApp</a>
                    <button type="button" class="j-pill js-copy-link">Copy link</button>
                </div>

                <a href="{{ $journalUrl }}" class="j-back text-sm">
                    <span class="j-arrow-left">←</span> Back to the Journal
                </a>

            </div>

        </div>

    </div>

</article>


{{-- =========================================================
    RELATED STORIES (optional — pass $related from controller)
========================================================== --}}
@if(isset($related) && $related->isNotEmpty())
    <section class="j-related py-20 md:py-28">
        <div class="wrap max-w-6xl">

            <div class="j-reveal mb-12 flex items-end justify-between gap-6 border-b j-border pb-6">
                <div>
                    <p class="eyebrow">Keep reading</p>
                    <h2 class="mt-2 text-3xl md:text-4xl">More from the Journal</h2>
                </div>
                <a href="{{ $journalUrl }}" class="j-link hidden text-sm sm:inline">View all →</a>
            </div>

            <div class="grid gap-10 md:grid-cols-3">
                @foreach($related as $item)
                    <article class="j-reveal j-card" style="--d: {{ $loop->index * 0.12 }}s">
                        <a href="{{ route('journal.show', $item) }}" class="block">
                            <div class="j-card-img aspect-[4/3]">
                                @if($item->image)
                                    <img src="{{ asset($item->image) }}" alt="{{ $item->title }}" loading="lazy">
                                @endif
                            </div>
                            <p class="mt-5 text-[11px] uppercase tracking-[0.2em] j-muted">
                                {{ $item->category }}
                                @if($item->category && $item->published_at) · @endif
                                {{ $item->published_at?->format('d M Y') }}
                            </p>
                            <h3 class="j-card-title mt-2 text-2xl leading-snug">
                                <span>{{ $item->title }}</span>
                            </h3>
                        </a>
                    </article>
                @endforeach
            </div>

        </div>
    </section>
@endif


{{-- =========================================================
    COPY TOAST
========================================================== --}}
<div id="j-toast" class="j-toast" role="status" aria-live="polite">Link copied</div>



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

    .j-ink    { color: var(--j-ink); }
    .j-muted  { color: var(--j-muted); }
    .j-border { border-color: var(--j-border); }
    .j-dot    { width: 4px; height: 4px; border-radius: 999px; background: var(--j-accent); display: inline-block; }

    .j-link { position: relative; transition: color .3s; }
    .j-link:hover { color: var(--j-accent); }

    .j-pill {
        border: 1px solid var(--j-border);
        border-radius: 999px;
        padding: .45rem .95rem;
        font-size: 12px;
        transition: all .3s var(--j-ease);
    }
    .j-pill:hover { border-color: var(--j-ink); background: var(--j-ink); color: #fff; }

    .j-back { display: inline-flex; align-items: center; gap: .5rem; font-weight: 500; }
    .j-back .j-arrow-left { transition: transform .4s var(--j-ease); }
    .j-back:hover .j-arrow-left { transform: translateX(-6px); }

    /* ---------- Progress bar ---------- */
    .j-progress { position: fixed; inset: 0 0 auto 0; height: 3px; z-index: 60; pointer-events: none; }
    .j-progress span {
        display: block; height: 100%; width: 100%;
        background: var(--j-accent);
        transform: scaleX(0); transform-origin: 0 50%;
    }

    /* ---------- Hero image ---------- */
    .j-hero { overflow: hidden; border-radius: 1rem; background: var(--j-soft); }
    .j-hero img {
        width: 100%; max-height: 78vh; object-fit: cover;
        transform: scale(1.08); will-change: transform;
    }

    /* ---------- Related cards ---------- */
    .j-related { background: var(--j-soft); }
    .j-card-img { overflow: hidden; border-radius: .75rem; background: #ebe5dd; }
    .j-card-img img { width: 100%; height: 100%; object-fit: cover; transition: transform 1.1s var(--j-ease); }
    .j-card:hover .j-card-img img { transform: scale(1.06); }
    .j-card-title span {
        background-image: linear-gradient(currentColor, currentColor);
        background-size: 0% 1px; background-repeat: no-repeat; background-position: 0 100%;
        transition: background-size .6s var(--j-ease);
    }
    .j-card:hover .j-card-title span { background-size: 100% 1px; }

    /* ---------- Toast ---------- */
    .j-toast {
        position: fixed; left: 50%; bottom: 2rem; z-index: 70;
        transform: translate(-50%, 20px); opacity: 0; pointer-events: none;
        background: var(--j-ink); color: #fff; font-size: 13px;
        padding: .7rem 1.2rem; border-radius: 999px;
        transition: all .4s var(--j-ease);
    }
    .j-toast.is-visible { opacity: 1; transform: translate(-50%, 0); }

    /* =========================================================
        ANIMATIONS
    ========================================================== */
    .j-title-words .j-word { display: inline-block; overflow: hidden; vertical-align: top; padding-bottom: .08em; margin-right: .25em; }
    .j-title-words .j-word:last-child { margin-right: 0; }
    .j-anim .j-title-words .j-word > span {
        display: inline-block; transform: translateY(105%);
        animation: j-word-up 1s var(--j-ease) forwards;
        animation-delay: calc(var(--i) * 60ms + 100ms);
    }
    @keyframes j-word-up { to { transform: translateY(0); } }

    .j-anim .j-reveal {
        opacity: 0; transform: translateY(28px);
        transition: opacity .9s var(--j-ease), transform .9s var(--j-ease);
        transition-delay: var(--d, 0s);
    }
    .j-anim .j-reveal.is-visible { opacity: 1; transform: none; }

    .j-anim .j-hero { clip-path: inset(12% 6% 12% 6% round 1rem); animation: j-hero-in 1.4s var(--j-ease) .35s forwards; }
    @keyframes j-hero-in { to { clip-path: inset(0 0 0 0 round 1rem); } }

    @media (prefers-reduced-motion: reduce) {
        .j-anim .j-title-words .j-word > span,
        .j-anim .j-hero { animation: none; transform: none; clip-path: none; }
        .j-anim .j-reveal { opacity: 1; transform: none; transition: none; }
        .j-hero img { transform: none; }
    }

    /* =========================================================
        SUMMERNOTE CONTENT — restores everything Tailwind resets
        so the article looks exactly as written in the editor.
        Inline styles (colour, alignment, image width, float)
        from Summernote always win.
    ========================================================== */
    .j-content {
        display: flow-root;
        font-family: Georgia, 'Times New Roman', serif;
        font-size: 1.2rem;
        line-height: 1.85;
        color: #292524;
        overflow-wrap: break-word;
    }
    .j-content > :first-child { margin-top: 0; }

    .j-content p          { margin: 0 0 1.4em; }
    .j-content h1,
    .j-content h2,
    .j-content h3,
    .j-content h4,
    .j-content h5,
    .j-content h6         { color: var(--j-ink); font-weight: 600; line-height: 1.3; margin: 2em 0 .7em; }
    .j-content h1         { font-size: 2.25rem; }
    .j-content h2         { font-size: 1.85rem; }
    .j-content h3         { font-size: 1.5rem; }
    .j-content h4         { font-size: 1.25rem; }
    .j-content h5         { font-size: 1.1rem; }
    .j-content h6         { font-size: 1rem; text-transform: uppercase; letter-spacing: .08em; }

    .j-content b,
    .j-content strong     { font-weight: 700; }
    .j-content i,
    .j-content em         { font-style: italic; }
    .j-content u          { text-decoration: underline; }
    .j-content s,
    .j-content strike     { text-decoration: line-through; }
    .j-content sub,
    .j-content sup        { font-size: .75em; }

    .j-content a {
        color: var(--j-accent);
        text-decoration: underline;
        text-underline-offset: 3px;
        text-decoration-thickness: 1px;
        transition: opacity .2s;
    }
    .j-content a:hover { opacity: .75; }

    .j-content ul,
    .j-content ol         { margin: 0 0 1.4em; padding-left: 1.6em; }
    .j-content ul         { list-style: disc; }
    .j-content ol         { list-style: decimal; }
    .j-content ul ul      { list-style: circle; margin-bottom: 0; }
    .j-content ol ol      { list-style: lower-alpha; margin-bottom: 0; }
    .j-content li         { margin: .35em 0; }
    .j-content li::marker { color: var(--j-accent); }

    .j-content blockquote {
        margin: 2em 0;
        padding: .2em 0 .2em 1.4em;
        border-left: 3px solid var(--j-accent);
        font-size: 1.35rem;
        font-style: italic;
        color: var(--j-ink);
    }
    .j-content blockquote p:last-child { margin-bottom: 0; }

    .j-content pre {
        margin: 1.8em 0; padding: 1.2em 1.4em;
        background: #1c1917; color: #f5f5f4;
        border-radius: .6rem; overflow-x: auto;
        font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
        font-size: .9rem; line-height: 1.7;
    }
    .j-content code {
        font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
        font-size: .88em; background: var(--j-soft);
        padding: .15em .4em; border-radius: .3rem;
    }
    .j-content pre code { background: none; padding: 0; }

    .j-content hr { border: 0; border-top: 1px solid var(--j-border); margin: 3em auto; width: 40%; }

    .j-content img {
        max-width: 100%;
        height: auto !important;
        border-radius: .6rem;
        display: inline-block;
    }
    .j-content p > img:only-child { display: block; margin: 2em auto; }
    .j-content img[style*="float: left"],
    .j-content img[style*="float:left"]  { margin: .4em 1.6em 1em 0; }
    .j-content img[style*="float: right"],
    .j-content img[style*="float:right"] { margin: .4em 0 1em 1.6em; }

    .j-content iframe,
    .j-content video {
        display: block; width: 100%; max-width: 100%;
        height: auto; aspect-ratio: 16 / 9;
        margin: 2em 0; border: 0; border-radius: .6rem;
    }

    .j-content table {
        width: 100%; margin: 2em 0;
        border-collapse: collapse; font-size: 1rem;
        display: block; overflow-x: auto;
    }
    .j-content th,
    .j-content td { border: 1px solid var(--j-border); padding: .65em .9em; text-align: left; vertical-align: top; }
    .j-content th,
    .j-content thead td { background: var(--j-soft); font-weight: 600; }

    /* Empty Summernote paragraphs keep their spacing */
    .j-content p:empty,
    .j-content p > br:only-child { min-height: 1em; }

    @media (max-width: 640px) {
        .j-content { font-size: 1.08rem; }
        .j-content h1 { font-size: 1.8rem; }
        .j-content h2 { font-size: 1.55rem; }
        .j-content h3 { font-size: 1.3rem; }
        .j-content img[style*="float"] {
            float: none !important; width: 100% !important;
            margin: 1.5em 0 !important; display: block;
        }
    }
</style>



{{-- =========================================================
    SCRIPTS
========================================================== --}}
<script>
(function () {
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* ---------- Reveal content blocks one by one ---------- */
    const content = document.getElementById('j-content');
    if (content) {
        Array.from(content.children).forEach(function (el) {
            el.classList.add('j-reveal');
        });
    }

    const revealEls = document.querySelectorAll('.j-reveal');

    if (reduceMotion || !('IntersectionObserver' in window)) {
        revealEls.forEach(el => el.classList.add('is-visible'));
    } else {
        const observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0, rootMargin: '0px 0px -8% 0px' });

        revealEls.forEach(el => observer.observe(el));
    }

    /* ---------- Reading progress + hero parallax ---------- */
    const bar     = document.getElementById('j-progress-bar');
    const article = document.getElementById('j-article');
    const heroImg = document.getElementById('j-hero-img');
    let ticking   = false;

    function onScroll() {
        if (article && bar) {
            const rect  = article.getBoundingClientRect();
            const total = rect.height - window.innerHeight;
            const done  = Math.min(1, Math.max(0, -rect.top / (total > 0 ? total : 1)));
            bar.style.transform = `scaleX(${done})`;
        }

        if (heroImg && !reduceMotion) {
            const r = heroImg.parentElement.getBoundingClientRect();
            if (r.bottom > 0 && r.top < window.innerHeight) {
                const shift = (r.top / window.innerHeight) * -40;
                heroImg.style.transform = `scale(1.08) translateY(${shift}px)`;
            }
        }

        ticking = false;
    }

    window.addEventListener('scroll', function () {
        if (!ticking) {
            requestAnimationFrame(onScroll);
            ticking = true;
        }
    }, { passive: true });

    onScroll();

    /* ---------- Copy link ---------- */
    const toast = document.getElementById('j-toast');

    document.querySelectorAll('.js-copy-link').forEach(function (btn) {
        btn.addEventListener('click', async function () {
            try {
                await navigator.clipboard.writeText(window.location.href);
                toast.textContent = 'Link copied';
            } catch (e) {
                toast.textContent = 'Could not copy link';
            }
            toast.classList.add('is-visible');
            setTimeout(() => toast.classList.remove('is-visible'), 2000);
        });
    });
})();
</script>

@endsection