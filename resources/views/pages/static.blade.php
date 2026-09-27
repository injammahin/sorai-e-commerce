@extends('layouts.store')

@section('title', ($page['title'] ?? 'AATCHALA') . ' | AATCHALA')

@section(
    'description',
    $page['description'] ?? 'AATCHALA'
)


@section('content')


<style>

    .static-page {

        --static-ink: #171512;

        --static-muted: #756f68;

        --static-line: #ded8cf;

        --static-paper: #faf8f5;

        --static-paper-2: #f3eee7;

        --static-forest: #173d32;

        --static-copper: #c4622f;


        background:
            var(--static-paper);

        color:
            var(--static-ink);

    }



    /* =========================================================
       HERO
    ========================================================= */

    .static-page-hero {

        padding:
            clamp(3.5rem, 7vw, 7rem)
            0
            clamp(3rem, 6vw, 5.5rem);

        border-bottom:
            1px solid
            var(--static-line);

        text-align:
            center;

    }



    .static-page-hero
    .static-eyebrow {

        color:
            var(--static-copper);

        font-size:
            10px;

        font-weight:
            600;

        letter-spacing:
            .24em;

        text-transform:
            uppercase;

    }



    .static-page-hero h1 {

        margin-top:
            .7rem;

        font-family:
            var(
                --font-display,
                Georgia,
                serif
            );

        font-size:
            clamp(
                3rem,
                6vw,
                6.5rem
            );

        font-weight:
            400;

        line-height:
            .96;

        letter-spacing:
            -.025em;

    }



    .static-page-intro {

        width:
            min(
                760px,
                100%
            );

        margin:
            1.5rem
            auto
            0;

        color:
            var(--static-muted);

        font-size:
            clamp(
                1rem,
                1.4vw,
                1.17rem
            );

        line-height:
            1.8;

    }



    /* =========================================================
       MAIN LAYOUT
    ========================================================= */

    .static-page-shell {

        display:
            grid;

        grid-template-columns:
            220px
            minmax(
                0,
                820px
            );

        justify-content:
            center;

        gap:
            clamp(
                3rem,
                7vw,
                7rem
            );

        padding-top:
            clamp(
                3rem,
                6vw,
                6rem
            );

        padding-bottom:
            clamp(
                5rem,
                8vw,
                8rem
            );

    }



    /* =========================================================
       SIDE NAVIGATION
    ========================================================= */

    .static-page-nav {

        position:
            sticky;

        top:
            135px;

        align-self:
            start;

    }



    .static-page-nav-label {

        margin-bottom:
            1rem;

        color:
            #958e86;

        font-size:
            9px;

        font-weight:
            600;

        letter-spacing:
            .2em;

        text-transform:
            uppercase;

    }



    .static-page-nav a {

        display:
            flex;

        align-items:
            center;

        justify-content:
            space-between;

        gap:
            1rem;

        padding:
            .7rem
            0;

        border-bottom:
            1px solid
            var(--static-line);

        color:
            #605a54;

        font-size:
            13px;

        transition:
            color .25s ease,
            padding-left .25s ease;

    }



    .static-page-nav a::after {

        content:
            "";

        width:
            5px;

        height:
            5px;

        border-top:
            1px solid
            currentColor;

        border-right:
            1px solid
            currentColor;

        opacity:
            .35;

        transform:
            rotate(45deg);

    }



    .static-page-nav a:hover,

    .static-page-nav a.is-active {

        padding-left:
            .35rem;

        color:
            var(--static-copper);

    }



    /* =========================================================
       CONTENT
    ========================================================= */

    .static-page-content {

        min-width:
            0;

    }



    .static-block {

        padding:
            0
            0
            2.3rem;

        margin-bottom:
            2.3rem;

        border-bottom:
            1px solid
            var(--static-line);

    }



    .static-block:last-child {

        margin-bottom:
            0;

    }



    .static-block h2 {

        margin-bottom:
            1rem;

        font-family:
            var(
                --font-display,
                Georgia,
                serif
            );

        font-size:
            clamp(
                1.7rem,
                3vw,
                2.45rem
            );

        font-weight:
            400;

        line-height:
            1.1;

    }



    .static-block p {

        margin-top:
            .85rem;

        color:
            #5f5953;

        font-size:
            15px;

        line-height:
            1.85;

    }



    /* =========================================================
       LISTS
    ========================================================= */

    .static-list {

        display:
            grid;

        gap:
            .8rem;

        margin-top:
            1rem;

    }



    .static-list li {

        position:
            relative;

        padding-left:
            1.4rem;

        color:
            #5f5953;

        font-size:
            15px;

        line-height:
            1.7;

    }



    .static-list li::before {

        content:
            "";

        position:
            absolute;

        left:
            0;

        top:
            .72em;

        width:
            8px;

        height:
            1px;

        background:
            var(--static-copper);

    }



    /* =========================================================
       NOTE BOX
    ========================================================= */

    .static-note {

        margin-top:
            1.25rem;

        padding:
            1rem
            1.1rem;

        border-left:
            2px solid
            var(--static-copper);

        background:
            var(--static-paper-2);

        color:
            #625c56;

        font-size:
            12px;

        line-height:
            1.7;

    }



    /* =========================================================
       TABLE
    ========================================================= */

    .static-table-wrap {

        margin-top:
            1.2rem;

        overflow-x:
            auto;

        border:
            1px solid
            var(--static-line);

    }



    .static-table {

        width:
            100%;

        min-width:
            580px;

        border-collapse:
            collapse;

        background:
            #ffffff;

    }



    .static-table th,

    .static-table td {

        padding:
            .95rem
            1rem;

        border-bottom:
            1px solid
            var(--static-line);

        text-align:
            left;

        vertical-align:
            top;

    }



    .static-table th {

        background:
            var(--static-paper-2);

        font-size:
            10px;

        font-weight:
            600;

        letter-spacing:
            .14em;

        text-transform:
            uppercase;

    }



    .static-table td {

        color:
            #5f5953;

        font-size:
            13px;

        line-height:
            1.6;

    }



    .static-table
    tr:last-child
    td {

        border-bottom:
            0;

    }



    /* =========================================================
       CONTACT
    ========================================================= */

    .static-contact-card {

        margin-top:
            2rem;

        padding:
            clamp(
                1.25rem,
                3vw,
                2rem
            );

        border:
            1px solid
            var(--static-line);

        background:
            #ffffff;

    }



    .static-contact-meta {

        display:
            grid;

        grid-template-columns:
            repeat(
                2,
                minmax(0, 1fr)
            );

        gap:
            1rem;

        margin-bottom:
            1.5rem;

    }



    .static-contact-meta > div {

        padding:
            1rem;

        background:
            var(--static-paper-2);

    }



    .static-contact-meta small {

        display:
            block;

        margin-bottom:
            .35rem;

        color:
            #8d867e;

        font-size:
            9px;

        letter-spacing:
            .15em;

        text-transform:
            uppercase;

    }



    .static-contact-meta a,

    .static-contact-meta span {

        overflow-wrap:
            anywhere;

        font-size:
            13px;

    }



    /* =========================================================
       CONTACT FORM
    ========================================================= */

    .static-form-grid {

        display:
            grid;

        grid-template-columns:
            repeat(
                2,
                minmax(0, 1fr)
            );

        gap:
            1rem;

    }



    .static-form-grid
    .full {

        grid-column:
            1 / -1;

    }



    .static-field-label {

        display:
            block;

        margin-bottom:
            .45rem;

        font-size:
            9px;

        font-weight:
            600;

        letter-spacing:
            .14em;

        text-transform:
            uppercase;

    }



    .static-field {

        width:
            100%;

        padding:
            .85rem
            .9rem;

        border:
            1px solid
            #d8d1c8;

        background:
            #ffffff;

        color:
            var(--static-ink);

        font-size:
            14px;

        outline:
            none;

        transition:
            border-color .2s ease,
            box-shadow .2s ease;

    }



    .static-field:focus {

        border-color:
            var(--static-forest);

        box-shadow:
            0
            0
            0
            2px
            rgba(23, 61, 50, .07);

    }



    textarea.static-field {

        min-height:
            150px;

        resize:
            vertical;

    }



    .static-error {

        display:
            block;

        margin-top:
            .35rem;

        color:
            #a33a2a;

        font-size:
            11px;

    }



    .static-submit {

        display:
            inline-flex;

        align-items:
            center;

        justify-content:
            center;

        gap:
            .6rem;

        min-height:
            46px;

        margin-top:
            1.2rem;

        padding:
            0
            1.4rem;

        border:
            1px solid
            var(--static-forest);

        background:
            var(--static-forest);

        color:
            #ffffff;

        font-size:
            10px;

        font-weight:
            600;

        letter-spacing:
            .15em;

        text-transform:
            uppercase;

        transition:
            background .25s ease,
            border-color .25s ease,
            transform .25s ease;

    }



    .static-submit:hover {

        border-color:
            var(--static-copper);

        background:
            var(--static-copper);

        transform:
            translateY(-1px);

    }



    .static-updated {

        margin-top:
            2rem;

        color:
            #989087;

        font-size:
            10px;

        letter-spacing:
            .08em;

        text-transform:
            uppercase;

    }



    /* =========================================================
       TABLET
    ========================================================= */

    @media (max-width: 900px) {

        .static-page-shell {

            grid-template-columns:
                1fr;

            gap:
                2.5rem;

        }


        .static-page-nav {

            position:
                static;

            display:
                grid;

            grid-template-columns:
                repeat(
                    2,
                    minmax(0, 1fr)
                );

            gap:
                0
                1.25rem;

        }


        .static-page-nav-label {

            grid-column:
                1 / -1;

        }

    }



    /* =========================================================
       MOBILE
    ========================================================= */

    @media (max-width: 600px) {

        .static-page-hero {

            padding-top:
                3rem;

        }


        .static-page-nav {

            grid-template-columns:
                1fr;

        }


        .static-contact-meta,

        .static-form-grid {

            grid-template-columns:
                1fr;

        }


        .static-form-grid
        .full {

            grid-column:
                auto;

        }

    }

</style>



<div class="static-page">


    {{-- ========================================================
        HERO
    ======================================================== --}}

    <header class="static-page-hero">


        <div class="wrap">


            <p class="static-eyebrow">

                {{ $page['eyebrow'] ?? 'AATCHALA' }}

            </p>



            <h1>

                {{ $page['title'] }}

            </h1>



            @if(!empty($page['intro']))

                <p class="static-page-intro">

                    {{ $page['intro'] }}

                </p>

            @endif


        </div>


    </header>



    {{-- ========================================================
        PAGE
    ======================================================== --}}

    <div class="wrap static-page-shell">


        {{-- ====================================================
            STATIC PAGE NAVIGATION
        ==================================================== --}}

        <aside
            class="static-page-nav"
            aria-label="Information pages"
        >


            <p class="static-page-nav-label">

                Information

            </p>



            @foreach($staticPages as $navSlug => $navPage)


                <a
                    href="{{ route('pages.show', $navSlug) }}"
                    class="{{ $slug === $navSlug ? 'is-active' : '' }}"
                    @if($slug === $navSlug)
                        aria-current="page"
                    @endif
                >

                    <span>

                        {{ $navPage['title'] }}

                    </span>

                </a>


            @endforeach


        </aside>



        {{-- ====================================================
            MAIN CONTENT
        ==================================================== --}}

        <main class="static-page-content">


            @foreach($page['blocks'] ?? [] as $block)


                <section class="static-block">


                    <h2>

                        {{ $block['title'] }}

                    </h2>



                    {{-- PARAGRAPHS --}}

                    @foreach($block['paragraphs'] ?? [] as $paragraph)

                        <p>

                            {{ $paragraph }}

                        </p>

                    @endforeach



                    {{-- LIST --}}

                    @if(!empty($block['items']))


                        <ul class="static-list">


                            @foreach($block['items'] as $item)


                                <li>

                                    {{ $item }}

                                </li>


                            @endforeach


                        </ul>


                    @endif



                    {{-- TABLE --}}

                    @if(!empty($block['table']))


                        <div class="static-table-wrap">


                            <table class="static-table">


                                <thead>

                                    <tr>


                                        @foreach($block['table']['headers'] as $header)


                                            <th>

                                                {{ $header }}

                                            </th>


                                        @endforeach


                                    </tr>

                                </thead>



                                <tbody>


                                    @foreach($block['table']['rows'] as $row)


                                        <tr>


                                            @foreach($row as $cell)


                                                <td>

                                                    {{ $cell }}

                                                </td>


                                            @endforeach


                                        </tr>


                                    @endforeach


                                </tbody>


                            </table>


                        </div>


                    @endif



                    {{-- NOTE --}}

                    @if(!empty($block['note']))


                        <div class="static-note">

                            {{ $block['note'] }}

                        </div>


                    @endif


                </section>


            @endforeach



            {{-- =================================================
                CONTACT FORM
            ================================================== --}}

            @if($slug === 'contact')


                <section class="static-contact-card">


                    <div class="static-contact-meta">


                        <div>

                            <small>
                                Email
                            </small>


                            <a
                                href="mailto:{{ $siteSettings['contact_email'] ?? 'hello@aatchala.com' }}"
                            >

                                {{ $siteSettings['contact_email'] ?? 'hello@aatchala.com' }}

                            </a>

                        </div>



                        <div>

                            <small>
                                Phone
                            </small>


                            <span>

                                {{
                                    $siteSettings['contact_phone']
                                    ??
                                    'Contact us through the form'
                                }}

                            </span>

                        </div>


                    </div>



                    <form
                        action="{{ route('contact.store') }}"
                        method="POST"
                    >


                        @csrf



                        {{-- HONEYPOT --}}

                        <div
                            aria-hidden="true"
                            style="
                                position:absolute;
                                left:-10000px;
                                width:1px;
                                height:1px;
                                overflow:hidden;
                            "
                        >

                            <label for="contact-website">

                                Leave this field empty

                            </label>


                            <input
                                id="contact-website"
                                type="text"
                                name="website"
                                value=""
                                tabindex="-1"
                                autocomplete="off"
                            >

                        </div>



                        <div class="static-form-grid">


                            {{-- NAME --}}

                            <label>


                                <span class="static-field-label">

                                    Name *

                                </span>


                                <input
                                    class="static-field"
                                    type="text"
                                    name="name"
                                    value="{{ old('name') }}"
                                    required
                                    maxlength="191"
                                >


                                @error('name')

                                    <span class="static-error">

                                        {{ $message }}

                                    </span>

                                @enderror


                            </label>



                            {{-- EMAIL --}}

                            <label>


                                <span class="static-field-label">

                                    Email *

                                </span>


                                <input
                                    class="static-field"
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    maxlength="191"
                                >


                                @error('email')

                                    <span class="static-error">

                                        {{ $message }}

                                    </span>

                                @enderror


                            </label>



                            {{-- PHONE --}}

                            <label>


                                <span class="static-field-label">

                                    Phone

                                </span>


                                <input
                                    class="static-field"
                                    type="text"
                                    name="phone"
                                    value="{{ old('phone') }}"
                                    maxlength="30"
                                >


                                @error('phone')

                                    <span class="static-error">

                                        {{ $message }}

                                    </span>

                                @enderror


                            </label>



                            {{-- SUBJECT --}}

                            <label>


                                <span class="static-field-label">

                                    Subject

                                </span>


                                <input
                                    class="static-field"
                                    type="text"
                                    name="subject"
                                    value="{{ old('subject') }}"
                                    maxlength="191"
                                >


                                @error('subject')

                                    <span class="static-error">

                                        {{ $message }}

                                    </span>

                                @enderror


                            </label>



                            {{-- MESSAGE --}}

                            <label class="full">


                                <span class="static-field-label">

                                    Message *

                                </span>


                                <textarea
                                    class="static-field"
                                    name="message"
                                    required
                                    maxlength="3000"
                                >{{ old('message') }}</textarea>


                                @error('message')

                                    <span class="static-error">

                                        {{ $message }}

                                    </span>

                                @enderror


                            </label>


                        </div>



                        <button
                            class="static-submit"
                            type="submit"
                        >

                            Send message


                            <i
                                class="fa-solid fa-arrow-right-long"
                                aria-hidden="true"
                            ></i>

                        </button>


                    </form>


                </section>


            @endif



            {{-- LAST UPDATED --}}

            @if(!empty($page['last_updated']))


                <p class="static-updated">

                    Last updated:
                    {{ $page['last_updated'] }}

                </p>


            @endif


        </main>


    </div>


</div>


@endsection