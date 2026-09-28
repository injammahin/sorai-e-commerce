{{-- ============================================================

    AATCHALA HEADER V3.5

    Laravel 9 Safe



    - Stable scroll header

    - Smooth announcement close/open

    - Smooth logo resize

    - Full-width desktop mega menu

    - Portrait-friendly images

    - Smooth mobile drawer

    - Smooth mobile category accordion

    - + smoothly becomes -

    - Only one mobile category open at a time

============================================================ --}}



@php
    $cartService = app(\App\Services\CartService::class);

    // Current total quantity in the shopping bag.
    $cartCount = $cartService->count();

    // Reuse the same request-level wishlist cache used by product cards.
    $wishlistProductIds = collect();

    if (auth()->check()) {
        if (!request()->attributes->has('aatchala_wishlist_product_ids')) {
            request()->attributes->set(
                'aatchala_wishlist_product_ids',
                auth()->user()
                    ->wishlistProducts()
                    ->pluck('products.id')
            );
        }

        $wishlistProductIds = request()->attributes->get(
            'aatchala_wishlist_product_ids',
            collect()
        );
    }

    $wishlistCount = $wishlistProductIds->count();
@endphp





<style>



    /* =========================================================

       HEADER SHELL

    ========================================================= */



    .aatchala-header-shell {

        position: sticky;

        top: 0;

        z-index: 100;

        width: 100%;

        background: #faf8f5;

    }





    /* =========================================================

       ANNOUNCEMENT BAR

    ========================================================= */



    .aatchala-header-shell .announcement {

        height: 32px;

        max-height: 32px;



        display: flex;

        align-items: center;



        overflow: hidden;



        background: #173d32;

        color: #ffffff;



        opacity: 1;



        font-size: 10px;

        line-height: 1;

        letter-spacing: .16em;

        text-transform: uppercase;



        transition:

            height .5s cubic-bezier(.22, 1, .36, 1),

            max-height .5s cubic-bezier(.22, 1, .36, 1),

            opacity .28s ease;



        will-change:

            height,

            max-height,

            opacity;

    }





    .aatchala-header-shell.is-compact .announcement {

        height: 0;

        max-height: 0;

        opacity: 0;

    }





    /* =========================================================

       SITE HEADER

    ========================================================= */



    .aatchala-header-shell .site-header {

        position: relative !important;

        top: auto !important;



        z-index: 20;



        width: 100%;



        background: rgba(250, 248, 245, .98);



        border-bottom: 1px solid #e0d9d0;



        backdrop-filter: blur(14px);

        -webkit-backdrop-filter: blur(14px);

    }





    /* =========================================================

       MAIN ROW

    ========================================================= */



    .aatchala-header-shell .main-row {

        height: 84px;



        display: grid;

        grid-template-columns: 1fr auto 1fr;



        align-items: center;



        transition:

            height .5s cubic-bezier(.22, 1, .36, 1);

    }





    .aatchala-header-shell.is-compact .main-row {

        height: 66px;

    }





    /* =========================================================

       LOGO

    ========================================================= */



    .aatchala-header-shell .logo {

        display: block;



        width: auto;

        height: 64px;



        transition:

            height .5s cubic-bezier(.22, 1, .36, 1);



        will-change: height;

    }





    .aatchala-header-shell.is-compact .logo {

        height: 48px;

    }





    /* =========================================================

       ICONS

    ========================================================= */



    .aatchala-header-shell .icon-link {

        position: relative;

        transition:

            color .25s ease,

            transform .25s ease;

    }





    .aatchala-header-shell .icon-link:hover {

        color: #c4622f;

        transform: translateY(-1px);

    }



    /* =========================================================
       WISHLIST + BAG COUNTERS
    ========================================================= */

    .aatchala-header-shell .wishlist-header-link.has-items {
        color: #c4622f;
    }


    .aatchala-header-shell .header-icon-count {
        position: absolute;

        top: -8px;
        right: -9px;

        z-index: 5;

        min-width: 17px;
        height: 17px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        padding: 0 4px;

        border: 2px solid #faf8f5;
        border-radius: 999px;

        background: #173d32;
        color: #ffffff;

        font-size: 8px;
        font-weight: 700;
        line-height: 1;

        letter-spacing: 0;

        pointer-events: none;
    }


    .aatchala-header-shell .wishlist-header-link.has-items .header-icon-count {
        background: #c4622f;
    }





    /* =========================================================

       DESKTOP NAVIGATION

    ========================================================= */



    .aatchala-header-shell .main-nav {

        position: static !important;

    }





    .aatchala-header-shell .nav-wrap {

        position: static !important;

    }





    .aatchala-header-shell .nav-item {

        transition: color .25s ease;

    }





    .aatchala-header-shell .nav-item:hover,

    .aatchala-header-shell .nav-item:focus-visible,

    .aatchala-header-shell .nav-wrap.is-open > .nav-item {

        color: #c4622f;

    }





    /* =========================================================

       DESKTOP MEGA MENU

    ========================================================= */



    .aatchala-header-shell .mega-menu {

        position: absolute;



        top: 100%;

        left: 0 !important;

        right: 0 !important;



        width: 100% !important;



        background: #faf8f5;



        border-top: 1px solid #e2ddd5;



        box-shadow:

            0 22px 45px rgba(0, 0, 0, .09);



        transition:

            opacity .25s ease,

            transform .32s cubic-bezier(.22, 1, .36, 1),

            visibility 0s linear .32s;

    }





    .aatchala-header-shell

    .nav-wrap.is-open

    .mega-menu {

        transition-delay: 0s;

    }





    /* =========================================================

       MEGA GRID

    ========================================================= */



    .aatchala-header-shell .mega-grid {

        display: grid;



        grid-template-columns:

            minmax(300px, 1.15fr)

            minmax(250px, .85fr)

            minmax(250px, .85fr);



        gap: clamp(2rem, 3vw, 3.5rem);



        align-items: start;



        width: 100%;

        max-width: 90rem;



        margin-left: auto;

        margin-right: auto;



        padding-top: 1.6rem !important;

        padding-bottom: 1.8rem !important;



        padding-left:

            clamp(2rem, 4vw, 4rem) !important;



        padding-right:

            clamp(2rem, 4vw, 4rem) !important;

    }





    /* =========================================================

       MEGA LINKS

    ========================================================= */



    .aatchala-header-shell .mega-links {

        columns: 2;

        column-gap: 2rem;

    }





    .aatchala-header-shell .mega-link {

        display: block;



        padding: .27rem 0;



        position: relative;



        break-inside: avoid;



        transform: translateX(0);



        transition:

            color .25s ease,

            transform .3s cubic-bezier(.22, 1, .36, 1);

    }





    .aatchala-header-shell .mega-link:hover,

    .aatchala-header-shell .mega-link:focus-visible,

    .aatchala-header-shell .mega-link.is-preview-active {

        color: #c4622f;



        transform: translateX(4px);

    }





    /* =========================================================

       MEGA IMAGE

    ========================================================= */



    .aatchala-header-shell .mega-image {

        width: 100%;

        height: clamp(270px, 18vw, 315px);



        position: relative;



        overflow: hidden;



        display: flex;

        align-items: center;

        justify-content: center;



        background: #efe9df;

    }





    .aatchala-header-shell .mega-image img {

        display: block;



        width: 100%;

        height: 100%;



        object-fit: fill;

        object-position: center center;



        transition:

            opacity .2s ease,

            transform .6s cubic-bezier(.22, 1, .36, 1);

    }





    .aatchala-header-shell

    .mega-product-card

    .mega-image img {

        padding: 3px;

    }





    .aatchala-header-shell

    .menu-preview-card:hover

    .mega-image img,



    .aatchala-header-shell

    .mega-product-card:hover

    .mega-image img {

        transform: scale(1.015);

    }





    /* =========================================================

       PREVIEW CARDS

    ========================================================= */



    .aatchala-header-shell .menu-preview-card,

    .aatchala-header-shell .mega-product-card {

        display: block;

        min-width: 0;

    }





    .aatchala-header-shell .menu-preview-title {

        line-height: 1.08;

    }





    .aatchala-header-shell .menu-preview-title,

    .aatchala-header-shell

    .menu-preview-card

    [data-menu-preview-eyebrow],

    .aatchala-header-shell .menu-preview-cta {



        opacity: 1;



        transform: translateY(0);



        transition:

            color .25s ease,

            opacity .2s ease,

            transform .3s ease;

    }





    /* =========================================================

       PREVIEW SWITCH

    ========================================================= */



    .aatchala-header-shell

    .menu-preview-card.is-switching

    .mega-image img {



        opacity: 0;



        transform: scale(1.01);

    }





    .aatchala-header-shell

    .menu-preview-card.is-switching

    .menu-preview-title,



    .aatchala-header-shell

    .menu-preview-card.is-switching

    [data-menu-preview-eyebrow],



    .aatchala-header-shell

    .menu-preview-card.is-switching

    .menu-preview-cta {



        opacity: 0;



        transform: translateY(5px);

    }





    /* =========================================================

       PREVIEW CTA

    ========================================================= */



    .aatchala-header-shell .menu-preview-cta {

        display: inline-flex;



        align-items: center;



        gap: .55rem;



        margin-top: .45rem;



        color: #746f68;



        font-size: 9px;



        letter-spacing: .17em;



        text-transform: uppercase;

    }





    .aatchala-header-shell .menu-preview-cta i {

        transition:

            transform .3s cubic-bezier(.22, 1, .36, 1);

    }





    .aatchala-header-shell

    .menu-preview-card:hover

    .menu-preview-title,



    .aatchala-header-shell

    .menu-preview-card:hover

    .menu-preview-cta {



        color: #c4622f;

    }





    .aatchala-header-shell

    .menu-preview-card:hover

    .menu-preview-cta i {



        transform: translateX(5px);

    }





    /* =========================================================

       FEATURED PRODUCT

    ========================================================= */



    .aatchala-header-shell

    .mega-product-card > p,



    .aatchala-header-shell

    .mega-product-card > strong {



        transition: color .25s ease;

    }





    .aatchala-header-shell

    .mega-product-card:hover > p,



    .aatchala-header-shell

    .mega-product-card:hover > strong {



        color: #c4622f;

    }





    /* =========================================================

       MOBILE DRAWER

    ========================================================= */



    #mobile-menu.drawer-backdrop {

        transition:

            opacity .35s ease,

            visibility .35s ease;

    }





    #mobile-menu .drawer {

        transition:

            transform .48s cubic-bezier(.22, 1, .36, 1);

    }





    /* =========================================================

       MOBILE ACCORDION

    ========================================================= */



    .mobile-accordion {

        width: 100%;

    }





    .mobile-accordion-item {

        border-bottom: 1px solid #ded8cf;

    }





    /* =========================================================

       ACCORDION BUTTON

    ========================================================= */



    .mobile-accordion-trigger {

        width: 100%;



        display: flex;

        align-items: center;

        justify-content: space-between;



        gap: 1rem;



        padding: 14px 0;



        background: transparent;

        border: 0;



        color: #121212;



        font: inherit;



        text-align: left;



        cursor: pointer;



        transition:

            color .25s ease,

            padding-left .3s cubic-bezier(.22, 1, .36, 1);

    }





    .mobile-accordion-trigger:hover,

    .mobile-accordion-trigger:focus-visible,

    .mobile-accordion-item.is-open

    .mobile-accordion-trigger {



        color: #c4622f;

    }





    .mobile-accordion-trigger:focus-visible {

        outline: none;

    }





    /* =========================================================

       PLUS / MINUS ICON

    ========================================================= */



    .mobile-accordion-icon {

        position: relative;



        display: block;



        width: 16px;

        height: 16px;



        flex: 0 0 16px;



        transition:

            transform .4s cubic-bezier(.22, 1, .36, 1);

    }





    /* Horizontal line */



    .mobile-accordion-icon::before {

        content: "";



        position: absolute;



        left: 50%;

        top: 50%;



        width: 12px;

        height: 1px;



        background: currentColor;



        transform:

            translate(-50%, -50%)

            rotate(0deg);



        transition:

            transform .4s cubic-bezier(.22, 1, .36, 1);

    }





    /* Vertical line */



    .mobile-accordion-icon::after {

        content: "";



        position: absolute;



        left: 50%;

        top: 50%;



        width: 1px;

        height: 12px;



        background: currentColor;



        opacity: 1;



        transform:

            translate(-50%, -50%)

            scaleY(1);



        transform-origin: center;



        transition:

            transform .35s cubic-bezier(.22, 1, .36, 1),

            opacity .22s ease;

    }





    /*

    |--------------------------------------------------------------------------

    | OPEN:

    | vertical line disappears,

    | resulting in a minus.

    |--------------------------------------------------------------------------

    */



    .mobile-accordion-item.is-open

    .mobile-accordion-icon {



        transform: rotate(180deg);

    }





    .mobile-accordion-item.is-open

    .mobile-accordion-icon::before {



        transform:

            translate(-50%, -50%)

            rotate(180deg);

    }





    .mobile-accordion-item.is-open

    .mobile-accordion-icon::after {



        opacity: 0;



        transform:

            translate(-50%, -50%)

            scaleY(0);

    }





    /* =========================================================

       ACCORDION PANEL

    ========================================================= */



    .mobile-accordion-panel {

        display: grid;



        grid-template-rows: 0fr;



        opacity: 0;



        visibility: hidden;



        transition:

            grid-template-rows .42s cubic-bezier(.22, 1, .36, 1),

            opacity .28s ease,

            visibility 0s linear .42s;

    }





    .mobile-accordion-panel-inner {

        min-height: 0;



        overflow: hidden;



        transform: translateY(-8px);



        transition:

            transform .42s cubic-bezier(.22, 1, .36, 1);

    }





    /* =========================================================

       OPEN PANEL

    ========================================================= */



    .mobile-accordion-item.is-open

    .mobile-accordion-panel {



        grid-template-rows: 1fr;



        opacity: 1;



        visibility: visible;



        transition:

            grid-template-rows .42s cubic-bezier(.22, 1, .36, 1),

            opacity .3s ease,

            visibility 0s linear 0s;

    }





    .mobile-accordion-item.is-open

    .mobile-accordion-panel-inner {



        transform: translateY(0);

    }





    /* =========================================================

       ACCORDION LINKS

    ========================================================= */



    .mobile-accordion-links {

        padding:

            0

            0

            14px

            15px;

    }





    .mobile-accordion-link {

        display: block;



        padding: 7px 0;



        color: #6f6a64;



        font-size: 14px;



        transition:

            color .25s ease,

            transform .25s ease;

    }





    .mobile-accordion-link:hover {

        color: #c4622f;



        transform: translateX(4px);

    }





    .mobile-accordion-view-all {

        color: #121212;

        font-weight: 500;

    }





    /* =========================================================

       MOBILE STATIC LINKS

    ========================================================= */



    .mobile-new-arrivals {

        display: block;



        padding: 14px 0;



        border-bottom: 1px solid #ded8cf;



        transition:

            color .25s ease,

            transform .25s ease;

    }





    .mobile-new-arrivals:hover {

        color: #c4622f;

    }





    /* =========================================================

       LARGE DESKTOP

    ========================================================= */



    @media (min-width: 1500px) {



        .aatchala-header-shell .mega-image {

            height: 305px;

        }



    }





    /* =========================================================

       SMALL DESKTOP

    ========================================================= */



    @media (max-width: 1200px) {



        .aatchala-header-shell .mega-grid {



            grid-template-columns:

                1fr

                .9fr

                .9fr;



            gap: 1.8rem;



            padding-left: 2rem !important;

            padding-right: 2rem !important;

        }





        .aatchala-header-shell .mega-image {

            height: 275px;

        }



    }





    /* =========================================================

       TABLET

    ========================================================= */



    @media (max-width: 1024px) {



        .aatchala-header-shell .main-row {

            height: 72px;

        }





        .aatchala-header-shell .logo {

            height: 52px;

        }





        .aatchala-header-shell.is-compact .main-row {

            height: 62px;

        }





        .aatchala-header-shell.is-compact .logo {

            height: 43px;

        }



    }





    /* =========================================================

       MOBILE

    ========================================================= */



    @media (max-width: 680px) {



        .aatchala-header-shell .announcement {

            height: 29px;

            max-height: 29px;



            font-size: 8px;



            letter-spacing: .11em;

        }





        .aatchala-header-shell.is-compact .announcement {

            height: 0;

            max-height: 0;

        }





        .aatchala-header-shell .main-row {

            height: 68px;

        }





        .aatchala-header-shell .logo {

            height: 48px;

        }





        .aatchala-header-shell.is-compact .main-row {

            height: 59px;

        }





        .aatchala-header-shell.is-compact .logo {

            height: 40px;

        }





        /*

        |--------------------------------------------------------------------------

        | Slightly cleaner mobile drawer spacing

        |--------------------------------------------------------------------------

        */



        #mobile-menu .drawer {

            padding:

                1.75rem

                2rem

                2.5rem;

        }


        .aatchala-header-shell .header-icon-count {
            top: -7px;
            right: -8px;

            min-width: 16px;
            height: 16px;

            padding: 0 4px;

            font-size: 7px;
        }



    }





    /* =========================================================

       REDUCED MOTION

    ========================================================= */



    @media (prefers-reduced-motion: reduce) {



        .aatchala-header-shell *,

        .aatchala-header-shell *::before,

        .aatchala-header-shell *::after,

        #mobile-menu *,

        #mobile-menu *::before,

        #mobile-menu *::after {



            transition-duration: .001ms !important;

        }



    }





    /* =========================================================
       PREMIUM LIVE SEARCH
    ========================================================= */

    #search-layer.premium-search-layer {
        overflow-y: auto;
        overscroll-behavior: contain;
        background: rgba(250, 248, 245, .985);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
    }

    #search-layer .premium-search-close {
        position: fixed;
        top: clamp(1rem, 2.5vw, 2rem);
        right: clamp(1rem, 2.5vw, 2rem);
        z-index: 5;
        width: 44px;
        height: 44px;
        display: grid;
        place-items: center;
        border: 1px solid #d8d1c8;
        border-radius: 50%;
        background: rgba(250, 248, 245, .92);
        color: #171512;
        font-size: 17px;
        transition: background .25s ease, color .25s ease, transform .3s ease;
    }

    #search-layer .premium-search-close:hover {
        background: #171512;
        color: #fff;
        transform: rotate(90deg);
    }

    #search-layer .premium-search-shell {
        width: min(1180px, calc(100% - 3rem));
        margin: 0 auto;
        padding: clamp(5.5rem, 10vh, 8rem) 0 5rem;
    }

    #search-layer .premium-search-head {
        width: min(900px, 100%);
        margin: 0 auto;
    }

    #search-layer .premium-search-eyebrow {
        color: #8d867e;
        font-size: 9px;
        font-weight: 600;
        letter-spacing: .22em;
        text-transform: uppercase;
    }

    #search-layer .premium-search-form {
        margin-top: 1rem;
    }

    #search-layer .premium-search-input-row {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 52px;
        align-items: center;
        border-bottom: 1px solid #171512;
    }

    #search-layer .premium-search-input {
        width: 100%;
        min-width: 0;
        padding: .85rem 0 1rem;
        border: 0;
        background: transparent;
        color: #171512;
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: clamp(2rem, 4vw, 3.6rem);
        font-weight: 400;
        line-height: 1;
        outline: none;
    }

    #search-layer .premium-search-input::placeholder {
        color: #aaa39c;
        opacity: 1;
    }

    #search-layer .premium-search-submit {
        width: 52px;
        height: 52px;
        display: grid;
        place-items: center;
        border: 0;
        background: transparent;
        color: #171512;
        font-size: 15px;
        transition: color .25s ease, transform .25s ease;
    }

    #search-layer .premium-search-submit:hover {
        color: #c4622f;
        transform: translateX(3px);
    }

    #search-layer .premium-search-hint {
        margin-top: .8rem;
        color: #827b73;
        font-size: 10px;
        line-height: 1.6;
    }

    #search-layer .premium-search-suggestions {
        display: flex;
        flex-wrap: wrap;
        gap: .5rem;
        margin-top: 1.15rem;
    }

    #search-layer .premium-search-chip {
        display: inline-flex;
        align-items: center;
        min-height: 32px;
        padding: .4rem .75rem;
        border: 1px solid #ddd6cd;
        background: #fff;
        color: #6d665f;
        font-size: 9px;
        letter-spacing: .04em;
        transition: border-color .25s ease, color .25s ease, background .25s ease;
    }

    #search-layer .premium-search-chip:hover {
        border-color: #171512;
        background: #171512;
        color: #fff;
    }

    #search-layer .live-search-area {
        width: min(1080px, 100%);
        margin: clamp(2rem, 5vw, 3.5rem) auto 0;
    }

    #search-layer .live-search-status-row {
        min-height: 28px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    #search-layer .live-search-title {
        color: #171512;
        font-size: 10px;
        font-weight: 600;
        letter-spacing: .17em;
        text-transform: uppercase;
    }

    #search-layer .live-search-count {
        color: #8b847c;
        font-size: 10px;
    }

    #search-layer .live-search-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1.6rem 1rem;
    }

    #search-layer .live-search-card {
        min-width: 0;
        animation: aatchalaSearchResultIn .34s cubic-bezier(.22,1,.36,1) both;
    }

    @keyframes aatchalaSearchResultIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    #search-layer .live-search-card-media {
        position: relative;
        width: 100%;
        aspect-ratio: 4 / 5;
        overflow: hidden;
        background: #eee8df;
    }

    #search-layer .live-search-card-media img {
        width: 100%;
        height: 100%;
        display: block;
        object-fit: contain;
        object-position: center;
        transition: transform .55s cubic-bezier(.22,1,.36,1);
    }

    #search-layer .live-search-card:hover .live-search-card-media img {
        transform: scale(1.025);
    }

    #search-layer .live-search-badge {
        position: absolute;
        top: .55rem;
        left: .55rem;
        z-index: 2;
        padding: .3rem .45rem;
        background: #171512;
        color: #fff;
        font-size: 7px;
        font-weight: 600;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    #search-layer .live-search-card-body {
        padding-top: .75rem;
    }

    #search-layer .live-search-card-name {
        display: block;
        color: #171512;
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: 18px;
        line-height: 1.1;
        transition: color .25s ease;
    }

    #search-layer .live-search-card:hover .live-search-card-name {
        color: #c4622f;
    }

    #search-layer .live-search-card-meta {
        margin-top: .3rem;
        color: #817a72;
        font-size: 9px;
        line-height: 1.4;
    }

    #search-layer .live-search-card-price {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: .45rem;
        margin-top: .45rem;
        font-size: 11px;
    }

    #search-layer .live-search-card-price strong {
        color: #171512;
        font-weight: 600;
    }

    #search-layer .live-search-card-price strong.is-sale {
        color: #c4622f;
    }

    #search-layer .live-search-card-price del {
        color: #928b83;
        font-size: 9px;
    }

    #search-layer .live-search-message {
        min-height: 220px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        border-top: 1px solid #e0d9d0;
        text-align: center;
    }

    #search-layer .live-search-message-icon {
        width: 48px;
        height: 48px;
        display: grid;
        place-items: center;
        margin-bottom: 1rem;
        border: 1px solid #d9d2c9;
        border-radius: 50%;
        color: #8a837b;
    }

    #search-layer .live-search-message h3 {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: 28px;
        font-weight: 400;
    }

    #search-layer .live-search-message p {
        width: min(430px, 100%);
        margin-top: .45rem;
        color: #817a72;
        font-size: 10px;
        line-height: 1.6;
    }

    #search-layer .live-search-loading {
        display: none;
        align-items: center;
        gap: .55rem;
        color: #777069;
        font-size: 10px;
    }

    #search-layer .live-search-loading.is-visible {
        display: inline-flex;
    }

    #search-layer .live-search-spinner {
        width: 14px;
        height: 14px;
        border: 1px solid #c9c1b8;
        border-top-color: #173d32;
        border-radius: 50%;
        animation: aatchalaSearchSpin .7s linear infinite;
    }

    @keyframes aatchalaSearchSpin {
        to { transform: rotate(360deg); }
    }

    #search-layer .live-search-footer {
        display: none;
        justify-content: center;
        margin-top: 1.75rem;
    }

    #search-layer .live-search-footer.is-visible {
        display: flex;
    }

    #search-layer .live-search-view-all {
        display: inline-flex;
        align-items: center;
        gap: .65rem;
        min-height: 42px;
        padding: 0 1.1rem;
        border-bottom: 1px solid #171512;
        color: #171512;
        font-size: 9px;
        font-weight: 600;
        letter-spacing: .13em;
        text-transform: uppercase;
        transition: color .25s ease, border-color .25s ease;
    }

    #search-layer .live-search-view-all:hover {
        color: #c4622f;
        border-color: #c4622f;
    }

    @media (max-width: 900px) {
        #search-layer .live-search-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (max-width: 680px) {
        #search-layer .premium-search-shell {
            width: 100%;
            padding: 5.25rem 1rem 3rem;
        }

        #search-layer .premium-search-close {
            top: 1rem;
            right: 1rem;
            width: 38px;
            height: 38px;
            font-size: 14px;
        }

        #search-layer .premium-search-input-row {
            grid-template-columns: minmax(0, 1fr) 42px;
        }

        #search-layer .premium-search-input {
            padding-bottom: .8rem;
            font-size: 2rem;
        }

        #search-layer .premium-search-submit {
            width: 42px;
            height: 42px;
        }

        #search-layer .premium-search-suggestions {
            gap: .4rem;
        }

        #search-layer .premium-search-chip {
            min-height: 29px;
            padding: .35rem .6rem;
            font-size: 8px;
        }

        #search-layer .live-search-area {
            margin-top: 1.8rem;
        }

        #search-layer .live-search-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1.5rem .65rem;
        }

        #search-layer .live-search-card-name {
            font-size: 16px;
        }

        #search-layer .live-search-card-meta {
            font-size: 8px;
        }

        #search-layer .live-search-status-row {
            margin-bottom: .8rem;
        }
    }

</style>







{{-- ============================================================

    HEADER

============================================================ --}}



<div

    class="aatchala-header-shell"

    data-aatchala-header

>





    {{-- ANNOUNCEMENT --}}



    <div class="announcement">



        <div class="wrap text-center">



            {{ $siteSettings['announcement'] ?? 'Complimentary delivery across Bangladesh on orders over ৳5,000' }}



        </div>



    </div>







    <header class="site-header">





        {{-- ====================================================

            MAIN ROW

        ==================================================== --}}



        <div class="wrap main-row">





            {{-- LEFT --}}



            <div class="flex items-center">





                <button

                    class="icon-link mobile-only"

                    type="button"

                    data-open="mobile-menu"

                    aria-label="Open menu"

                >



                    <i class="fa-solid fa-bars"></i>



                </button>







                <button

                    class="icon-link desktop-nav"

                    type="button"

                    data-open="search-layer"

                    aria-label="Search"

                >



                    <i class="fa-solid fa-magnifying-glass"></i>



                </button>





            </div>







            {{-- LOGO --}}



            <a

                href="{{ route('home') }}"

                aria-label="AATCHALA home"

            >



                <img

                    class="logo"

                    src="{{ asset('images/logo/aatchala-lockup.webp') }}"

                    alt="AATCHALA"

                >



            </a>







            {{-- RIGHT --}}



            <div class="flex justify-end">





                <button

                    class="icon-link mobile-only"

                    type="button"

                    data-open="search-layer"

                    aria-label="Search"

                >



                    <i class="fa-solid fa-magnifying-glass"></i>



                </button>







                <a

                    class="icon-link desktop-nav"

                    href="{{ auth()->check() ? route('account.index') : route('login') }}"

                    aria-label="Account"

                >



                    <i class="fa-regular fa-user"></i>



                </a>







                <a
                    class="icon-link wishlist-header-link {{ $wishlistCount > 0 ? 'has-items' : '' }}"
                    href="{{ auth()->check() ? route('wishlist.index') : route('login') }}"
                    aria-label="Wishlist{{ $wishlistCount > 0 ? ' (' . $wishlistCount . ' items)' : '' }}"
                    title="Favourites"
                >

                    <i class="{{ $wishlistCount > 0 ? 'fa-solid' : 'fa-regular' }} fa-heart"></i>

                    @if($wishlistCount > 0)

                        <span class="header-icon-count" aria-hidden="true">
                            {{ $wishlistCount > 99 ? '99+' : $wishlistCount }}
                        </span>

                    @endif

                </a>







                <a
                    class="icon-link"
                    href="{{ route('cart.index') }}"
                    aria-label="Bag{{ $cartCount > 0 ? ' (' . $cartCount . ' items)' : '' }}"
                    title="Shopping bag"
                >

                    <i class="fa-solid fa-bag-shopping"></i>

                    @if($cartCount > 0)

                        <span class="header-icon-count" aria-hidden="true">
                            {{ $cartCount > 99 ? '99+' : $cartCount }}
                        </span>

                    @endif

                </a>





            </div>





        </div>







        {{-- ====================================================

            DESKTOP NAV

        ==================================================== --}}



        <nav

            class="desktop-nav border-t hairline"

            aria-label="Main navigation"

        >



            <div class="main-nav wrap">





                @foreach($navCategories as $nav)



                    @php



                        $defaultPreviewImage = asset(

                            $nav->image ?: 'images/placeholder.webp'

                        );



                        $defaultPreviewTitle =

                            $nav->heading ?: $nav->name;



                        $defaultPreviewUrl =

                            route('categories.show', $nav);



                        $defaultPreviewEyebrow =

                            'Featured collection';





                        $highlight = \App\Models\Product::active()

                            ->where('category_id', $nav->id)

                            ->where('is_featured', true)

                            ->with('primaryImage')

                            ->first();





                        if (!$highlight) {



                            $highlight = \App\Models\Product::active()

                                ->where('category_id', $nav->id)

                                ->with('primaryImage')

                                ->first();



                        }



                    @endphp







                    <div

                        class="nav-wrap"

                        data-mega-menu

                    >





                        <a

                            class="nav-item"

                            href="{{ route('categories.show', $nav) }}"

                            aria-haspopup="true"

                            aria-expanded="false"

                        >



                            {{ $nav->name }}



                        </a>







                        <div class="mega-menu">





                            <div class="mega-grid wrap">





                                {{-- LEFT --}}



                                <div>





                                    @if(!empty($nav->tagline))



                                        <p class="eyebrow mb-3">



                                            {{ $nav->tagline }}



                                        </p>



                                    @endif







                                    <div class="mega-links">





                                        @foreach($nav->children as $child)



                                            <a

                                                class="mega-link"

                                                href="{{ route('products.index', [$nav, $child]) }}"

                                                data-menu-preview

                                                data-preview-image="{{ asset($child->image ?: $nav->image ?: 'images/placeholder.webp') }}"

                                                data-preview-title="{{ $child->name }}"

                                                data-preview-eyebrow="{{ $nav->name }} collection"

                                                data-preview-alt="{{ $child->name }} from AATCHALA"

                                                data-preview-url="{{ route('products.index', [$nav, $child]) }}"

                                            >



                                                {{ $child->name }}



                                            </a>



                                        @endforeach





                                    </div>







                                    <a

                                        class="btn btn-outline mt-5 !py-2.5"

                                        href="{{ route('categories.show', $nav) }}"

                                    >



                                        View all {{ $nav->name }}



                                    </a>





                                </div>







                                {{-- CENTER PREVIEW --}}



                                <a

                                    class="menu-preview-card"

                                    href="{{ $defaultPreviewUrl }}"

                                    data-menu-preview-card

                                    data-default-image="{{ $defaultPreviewImage }}"

                                    data-default-title="{{ $defaultPreviewTitle }}"

                                    data-default-eyebrow="{{ $defaultPreviewEyebrow }}"

                                    data-default-alt="{{ $nav->name }} collection"

                                    data-default-url="{{ $defaultPreviewUrl }}"

                                    data-current-preview-image="{{ $defaultPreviewImage }}"

                                >





                                    <p

                                        class="eyebrow mb-3"

                                        data-menu-preview-eyebrow

                                    >



                                        {{ $defaultPreviewEyebrow }}



                                    </p>







                                    <div class="mega-image">



                                        <img

                                            src="{{ $defaultPreviewImage }}"

                                            alt="{{ $nav->name }} collection"

                                            data-menu-preview-image

                                        >



                                    </div>







                                    <h3

                                        class="menu-preview-title text-2xl mt-2"

                                        data-menu-preview-title

                                    >



                                        {{ $defaultPreviewTitle }}



                                    </h3>







                                    <span class="menu-preview-cta">



                                        Explore collection



                                        <i

                                            class="fa-solid fa-arrow-right-long"

                                            aria-hidden="true"

                                        ></i>



                                    </span>





                                </a>







                                {{-- FEATURED PRODUCT --}}



                                @if($highlight)



                                    <a

                                        class="mega-product-card"

                                        href="{{ route('products.show', $highlight) }}"

                                    >





                                        <p class="eyebrow mb-3">



                                            On the floor now



                                        </p>







                                        <div class="mega-image">



                                            <img

                                                src="{{ asset(optional($highlight->primaryImage)->path ?: 'images/placeholder.webp') }}"

                                                alt="{{ $highlight->name }}"

                                            >



                                        </div>







                                        <p class="mt-2 text-sm">



                                            {{ $highlight->name }}



                                        </p>







                                        <strong class="text-xs">



                                            ৳{{ number_format($highlight->price) }}



                                        </strong>





                                    </a>



                                @endif





                            </div>





                        </div>





                    </div>



                @endforeach







                {{-- NEW ARRIVALS ONLY ONCE --}}



                <a

                    class="nav-item"

                    href="{{ route('new-arrivals') }}"

                >



                    New Arrivals



                </a>





            </div>





        </nav>





    </header>



</div>







{{-- ============================================================

    PREMIUM LIVE SEARCH OVERLAY

============================================================ --}}

<div
    id="search-layer"
    class="search-layer premium-search-layer"
    data-layer
>

    <button
        class="premium-search-close"
        type="button"
        data-close
        aria-label="Close search"
    >
        <i class="fa-solid fa-xmark"></i>
    </button>

    <div class="premium-search-shell">

        <div class="premium-search-head">

            <p class="premium-search-eyebrow">
                Search AATCHALA
            </p>

            <form
                class="premium-search-form"
                action="{{ route('search') }}"
                method="GET"
                data-live-search-form
                data-live-search-url="{{ route('search.live') }}"
            >

                <div class="premium-search-input-row">

                    <input
                        class="premium-search-input"
                        type="search"
                        name="q"
                        placeholder="What are you looking for?"
                        autocomplete="off"
                        spellcheck="false"
                        enterkeyhint="search"
                        aria-label="Search AATCHALA products"
                        aria-controls="live-search-results"
                        aria-autocomplete="list"
                        data-live-search-input
                    >

                    <button
                        class="premium-search-submit"
                        type="submit"
                        aria-label="Search"
                    >
                        <i class="fa-solid fa-arrow-right-long"></i>
                    </button>

                </div>

                <p class="premium-search-hint">
                    Search by product name, material, SKU or collection.
                </p>

                <div
                    class="premium-search-suggestions"
                    aria-label="Popular searches"
                >
                    <button
                        type="button"
                        class="premium-search-chip"
                        data-search-suggestion="Jamdani"
                    >
                        Jamdani
                    </button>

                    <button
                        type="button"
                        class="premium-search-chip"
                        data-search-suggestion="Nakshi Kantha"
                    >
                        Nakshi Kantha
                    </button>

                    <button
                        type="button"
                        class="premium-search-chip"
                        data-search-suggestion="Shital Pati"
                    >
                        Shital Pati
                    </button>

                    <button
                        type="button"
                        class="premium-search-chip"
                        data-search-suggestion="Jute"
                    >
                        Jute
                    </button>

                    <button
                        type="button"
                        class="premium-search-chip"
                        data-search-suggestion="Panjabi"
                    >
                        Panjabi
                    </button>
                </div>

            </form>

        </div>

        <section
            class="live-search-area"
            aria-live="polite"
            aria-busy="false"
            data-live-search-area
        >

            <div class="live-search-status-row">

                <h2
                    class="live-search-title"
                    data-live-search-title
                >
                    Discover products
                </h2>

                <div
                    class="live-search-loading"
                    data-live-search-loading
                >
                    <span class="live-search-spinner"></span>
                    Searching
                </div>

                <span
                    class="live-search-count"
                    data-live-search-count
                ></span>

            </div>

            <div
                id="live-search-results"
                class="live-search-grid"
                data-live-search-results
            ></div>

            <div
                class="live-search-message"
                data-live-search-message
            >
                <span class="live-search-message-icon">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>

                <h3>
                    Find something made with a story.
                </h3>

                <p>
                    Start typing to see matching AATCHALA products instantly.
                </p>
            </div>

            <div
                class="live-search-footer"
                data-live-search-footer
            >
                <a
                    class="live-search-view-all"
                    href="{{ route('search') }}"
                    data-live-search-view-all
                >
                    View all search results
                    <i class="fa-solid fa-arrow-right-long"></i>
                </a>
            </div>

        </section>

    </div>

</div>


{{-- ============================================================

    MOBILE MENU

============================================================ --}}



<div

    id="mobile-menu"

    class="drawer-backdrop"

    data-layer

>





    <nav

        class="drawer mobile-nav"

        aria-label="Mobile navigation"

    >





        {{-- ====================================================

            MOBILE TOP

        ==================================================== --}}



        <div class="flex items-center justify-between mb-8">





            <a href="{{ route('home') }}">



                <img

                    class="h-12"

                    src="{{ asset('images/logo/aatchala-lockup.webp') }}"

                    alt="AATCHALA"

                >



            </a>







            <button

                type="button"

                data-close

                class="text-xl"

                aria-label="Close menu"

            >



                <i class="fa-solid fa-xmark"></i>



            </button>





        </div>







        {{-- ====================================================

            NEW ARRIVALS

        ==================================================== --}}



        <a

            class="mobile-new-arrivals"

            href="{{ route('new-arrivals') }}"

        >



            New Arrivals



        </a>







        {{-- ====================================================

            SMOOTH MOBILE ACCORDION

        ==================================================== --}}



        <div

            class="mobile-accordion"

            data-mobile-accordion

        >





            @foreach($navCategories as $nav)





                <div

                    class="mobile-accordion-item"

                    data-mobile-accordion-item

                >





                    {{-- BUTTON --}}



                    <button

                        type="button"

                        class="mobile-accordion-trigger"

                        data-mobile-accordion-trigger

                        aria-expanded="false"

                    >





                        <span>



                            {{ $nav->name }}



                        </span>







                        <span

                            class="mobile-accordion-icon"

                            aria-hidden="true"

                        ></span>





                    </button>







                    {{-- EXPANDING CONTENT --}}



                    <div

                        class="mobile-accordion-panel"

                        data-mobile-accordion-panel

                        aria-hidden="true"

                    >





                        <div

                            class="mobile-accordion-panel-inner"

                        >





                            <div

                                class="mobile-accordion-links"

                            >





                                {{-- VIEW ALL --}}



                                <a

                                    class="

                                        mobile-accordion-link

                                        mobile-accordion-view-all

                                    "

                                    href="{{ route('categories.show', $nav) }}"

                                >



                                    View all {{ $nav->name }}



                                </a>







                                {{-- CHILD CATEGORIES --}}



                                @foreach($nav->children as $child)





                                    <a

                                        class="mobile-accordion-link"

                                        href="{{ route('products.index', [$nav, $child]) }}"

                                    >



                                        {{ $child->name }}



                                    </a>





                                @endforeach





                            </div>





                        </div>





                    </div>





                </div>





            @endforeach





        </div>







        {{-- ====================================================

            BOTTOM LINKS

        ==================================================== --}}



        <div class="grid gap-3 mt-8 text-sm">





            <a

                href="{{ auth()->check() ? route('account.index') : route('login') }}"

            >



                My Account



            </a>







            <a

                href="{{ route('pages.show', 'customer-service') }}"

            >



                Customer Service



            </a>







            <a

                href="{{ route('pages.show', 'stores') }}"

            >



                Find a Store



            </a>





        </div>





    </nav>



</div>







{{-- ============================================================

    JAVASCRIPT

============================================================ --}}



<script>



(function () {



    'use strict';





    /* =========================================================

       STABLE HEADER SCROLL

    ========================================================= */



    const header =

        document.querySelector(

            '[data-aatchala-header]'

        );





    if (header) {





        const TOP_LIMIT = 4;



        const DIRECTION_THRESHOLD = 18;



        const ANIMATION_LOCK_TIME = 540;



        const INITIAL_COMPACT_POSITION = 90;





        let lastScrollY =

            Math.max(

                window.scrollY || 0,

                0

            );





        let currentDirection = null;



        let accumulatedMovement = 0;



        let animationLocked = false;



        let ticking = false;



        let unlockTimer = null;







        /* =====================================================

           RESET

        ===================================================== */



        function resetTracking() {



            lastScrollY =

                Math.max(

                    window.scrollY || 0,

                    0

                );





            currentDirection = null;



            accumulatedMovement = 0;



        }







        /* =====================================================

           LOCK

        ===================================================== */



        function lockAnimation() {



            animationLocked = true;





            if (unlockTimer) {



                window.clearTimeout(

                    unlockTimer

                );



            }





            unlockTimer =

                window.setTimeout(

                    function () {



                        animationLocked = false;



                        resetTracking();



                    },

                    ANIMATION_LOCK_TIME

                );



        }







        /* =====================================================

           OPEN

        ===================================================== */



        function expandHeader() {



            if (

                !header.classList.contains(

                    'is-compact'

                )

            ) {



                resetTracking();



                return;



            }





            lockAnimation();





            header.classList.remove(

                'is-compact'

            );



        }







        /* =====================================================

           CLOSE

        ===================================================== */



        function compactHeader() {



            if (

                header.classList.contains(

                    'is-compact'

                )

            ) {



                resetTracking();



                return;



            }





            lockAnimation();





            header.classList.add(

                'is-compact'

            );



        }







        /* =====================================================

           INITIAL STATE

        ===================================================== */



        if (

            lastScrollY >

            INITIAL_COMPACT_POSITION

        ) {



            header.classList.add(

                'is-compact'

            );



        } else {



            header.classList.remove(

                'is-compact'

            );



        }







        /* =====================================================

           UPDATE

        ===================================================== */



        function updateHeader() {



            const currentScrollY =

                Math.max(

                    window.scrollY ||

                    document.documentElement.scrollTop ||

                    0,

                    0

                );







            /*

            |--------------------------------------------------------------------------

            | Always open at page top

            |--------------------------------------------------------------------------

            */



            if (

                currentScrollY <=

                TOP_LIMIT

            ) {



                lastScrollY =

                    currentScrollY;



                currentDirection =

                    null;



                accumulatedMovement =

                    0;





                if (

                    !animationLocked &&

                    header.classList.contains(

                        'is-compact'

                    )

                ) {



                    expandHeader();



                }





                ticking = false;



                return;



            }







            /*

            |--------------------------------------------------------------------------

            | No reversing while animation is running

            |--------------------------------------------------------------------------

            */



            if (animationLocked) {



                lastScrollY =

                    currentScrollY;



                ticking = false;



                return;



            }







            const delta =

                currentScrollY -

                lastScrollY;







            /*

            |--------------------------------------------------------------------------

            | Ignore tiny movements

            |--------------------------------------------------------------------------

            */



            if (

                Math.abs(delta) < 1

            ) {



                ticking = false;



                return;



            }







            const direction =

                delta > 0

                    ? 'down'

                    : 'up';







            /*

            |--------------------------------------------------------------------------

            | Direction changed

            |--------------------------------------------------------------------------

            */



            if (

                direction !==

                currentDirection

            ) {



                currentDirection =

                    direction;



                accumulatedMovement =

                    0;



            }







            accumulatedMovement +=

                Math.abs(delta);







            /*

            |--------------------------------------------------------------------------

            | Scroll down

            |--------------------------------------------------------------------------

            */



            if (

                currentDirection === 'down' &&

                accumulatedMovement >=

                    DIRECTION_THRESHOLD

            ) {



                accumulatedMovement = 0;





                if (

                    !header.classList.contains(

                        'is-compact'

                    )

                ) {



                    compactHeader();



                }



            }







            /*

            |--------------------------------------------------------------------------

            | Scroll up

            |--------------------------------------------------------------------------

            */



            else if (

                currentDirection === 'up' &&

                accumulatedMovement >=

                    DIRECTION_THRESHOLD

            ) {



                accumulatedMovement = 0;





                if (

                    header.classList.contains(

                        'is-compact'

                    )

                ) {



                    expandHeader();



                }



            }







            lastScrollY =

                currentScrollY;





            ticking = false;



        }







        /* =====================================================

           SCROLL EVENT

        ===================================================== */



        function onScroll() {



            if (ticking) {

                return;

            }





            ticking = true;





            window.requestAnimationFrame(

                updateHeader

            );



        }







        window.addEventListener(

            'scroll',

            onScroll,

            {

                passive: true

            }

        );







        window.addEventListener(

            'resize',

            function () {



                resetTracking();



            },

            {

                passive: true

            }

        );







        window.addEventListener(

            'pageshow',

            function () {



                resetTracking();





                if (

                    window.scrollY <=

                    TOP_LIMIT

                ) {



                    header.classList.remove(

                        'is-compact'

                    );



                }



            }

        );



    }







    /* =========================================================

       MOBILE ACCORDION

    ========================================================= */



    const accordion =

        document.querySelector(

            '[data-mobile-accordion]'

        );





    if (!accordion) {

        return;

    }







    const accordionItems =

        Array.from(

            accordion.querySelectorAll(

                '[data-mobile-accordion-item]'

            )

        );







    /* =========================================================

       CLOSE ITEM

    ========================================================= */



    function closeAccordionItem(item) {



        if (!item) {

            return;

        }





        const trigger =

            item.querySelector(

                '[data-mobile-accordion-trigger]'

            );





        const panel =

            item.querySelector(

                '[data-mobile-accordion-panel]'

            );





        item.classList.remove(

            'is-open'

        );





        if (trigger) {



            trigger.setAttribute(

                'aria-expanded',

                'false'

            );



        }





        if (panel) {



            panel.setAttribute(

                'aria-hidden',

                'true'

            );



        }



    }







    /* =========================================================

       OPEN ITEM

    ========================================================= */



    function openAccordionItem(item) {



        if (!item) {

            return;

        }





        const trigger =

            item.querySelector(

                '[data-mobile-accordion-trigger]'

            );





        const panel =

            item.querySelector(

                '[data-mobile-accordion-panel]'

            );





        item.classList.add(

            'is-open'

        );





        if (trigger) {



            trigger.setAttribute(

                'aria-expanded',

                'true'

            );



        }





        if (panel) {



            panel.setAttribute(

                'aria-hidden',

                'false'

            );



        }



    }







    /* =========================================================

       ACCORDION CLICKS

    ========================================================= */



    accordionItems.forEach(

        function (item) {





            const trigger =

                item.querySelector(

                    '[data-mobile-accordion-trigger]'

                );





            if (!trigger) {

                return;

            }





            trigger.addEventListener(

                'click',

                function () {





                    const alreadyOpen =

                        item.classList.contains(

                            'is-open'

                        );





                    /*

                    |--------------------------------------------------------------------------

                    | Close every other menu.

                    |--------------------------------------------------------------------------

                    */



                    accordionItems.forEach(

                        function (otherItem) {



                            if (

                                otherItem !== item

                            ) {



                                closeAccordionItem(

                                    otherItem

                                );



                            }



                        }

                    );





                    /*

                    |--------------------------------------------------------------------------

                    | Toggle selected menu.

                    |--------------------------------------------------------------------------

                    */



                    if (alreadyOpen) {



                        closeAccordionItem(

                            item

                        );



                    } else {



                        openAccordionItem(

                            item

                        );



                    }





                }

            );





        }

    );





})();



</script>


{{-- ============================================================
    PREMIUM LIVE SEARCH JAVASCRIPT
============================================================ --}}

<script>
(function () {
    'use strict';

    const layer = document.getElementById('search-layer');

    if (!layer) {
        return;
    }

    const form = layer.querySelector('[data-live-search-form]');
    const input = layer.querySelector('[data-live-search-input]');
    const area = layer.querySelector('[data-live-search-area]');
    const results = layer.querySelector('[data-live-search-results]');
    const message = layer.querySelector('[data-live-search-message]');
    const title = layer.querySelector('[data-live-search-title]');
    const count = layer.querySelector('[data-live-search-count]');
    const loading = layer.querySelector('[data-live-search-loading]');
    const footer = layer.querySelector('[data-live-search-footer]');
    const viewAll = layer.querySelector('[data-live-search-view-all]');
    const suggestionButtons = layer.querySelectorAll('[data-search-suggestion]');

    if (!form || !input || !area || !results) {
        return;
    }

    const endpoint = form.dataset.liveSearchUrl;
    const normalSearchUrl = form.action;

    let debounceTimer = null;
    let controller = null;
    let currentRequestId = 0;

    const money = (value) => {
        const number = Number(value || 0);
        return '৳' + new Intl.NumberFormat('en-US', {
            maximumFractionDigits: 0,
        }).format(number);
    };

    const clearResults = () => {
        results.replaceChildren();
        count.textContent = '';
        footer.classList.remove('is-visible');
    };

    const setLoading = (state) => {
        area.setAttribute('aria-busy', state ? 'true' : 'false');
        loading.classList.toggle('is-visible', state);
    };

    const showMessage = (heading, body, iconClass = 'fa-magnifying-glass') => {
        clearResults();
        message.hidden = false;
        title.textContent = heading;

        const icon = message.querySelector('i');
        const headingElement = message.querySelector('h3');
        const bodyElement = message.querySelector('p');

        if (icon) {
            icon.className = 'fa-solid ' + iconClass;
        }

        if (headingElement) {
            headingElement.textContent = heading;
        }

        if (bodyElement) {
            bodyElement.textContent = body;
        }
    };

    const createBadge = (product) => {
        let label = '';

        if (Number(product.discount_percent) > 0) {
            label = '-' + product.discount_percent + '%';
        } else if (product.is_new) {
            label = 'New';
        }

        if (!label) {
            return null;
        }

        const badge = document.createElement('span');
        badge.className = 'live-search-badge';
        badge.textContent = label;
        return badge;
    };

    const createProductCard = (product, index) => {
        const article = document.createElement('article');
        article.className = 'live-search-card';
        article.style.animationDelay = Math.min(index * 45, 225) + 'ms';

        const link = document.createElement('a');
        link.href = product.url;
        link.setAttribute('aria-label', 'View ' + product.name);

        const media = document.createElement('div');
        media.className = 'live-search-card-media';

        const image = document.createElement('img');
        image.src = product.image;
        image.alt = product.name;
        image.loading = 'lazy';
        media.appendChild(image);

        const badge = createBadge(product);
        if (badge) {
            media.appendChild(badge);
        }

        const body = document.createElement('div');
        body.className = 'live-search-card-body';

        const name = document.createElement('span');
        name.className = 'live-search-card-name';
        name.textContent = product.name;
        body.appendChild(name);

        const metaParts = [product.category, product.material].filter(Boolean);
        if (metaParts.length) {
            const meta = document.createElement('p');
            meta.className = 'live-search-card-meta';
            meta.textContent = metaParts.join(' · ');
            body.appendChild(meta);
        }

        const price = document.createElement('div');
        price.className = 'live-search-card-price';

        const current = document.createElement('strong');
        current.textContent = money(product.price);

        if (product.compare_price) {
            current.classList.add('is-sale');
        }

        price.appendChild(current);

        if (product.compare_price) {
            const old = document.createElement('del');
            old.textContent = money(product.compare_price);
            price.appendChild(old);
        }

        body.appendChild(price);

        link.appendChild(media);
        link.appendChild(body);
        article.appendChild(link);

        return article;
    };

    const renderProducts = (payload, query) => {
        clearResults();
        message.hidden = true;

        const products = Array.isArray(payload.products)
            ? payload.products
            : [];

        title.textContent = 'Suggested products';
        count.textContent = products.length
            ? products.length + (products.length === 1 ? ' match' : ' matches')
            : '';

        if (!products.length) {
            showMessage(
                'No matching products',
                'Try another product name, material or category.',
                'fa-feather'
            );
            return;
        }

        const fragment = document.createDocumentFragment();

        products.forEach((product, index) => {
            fragment.appendChild(createProductCard(product, index));
        });

        results.appendChild(fragment);

        const allUrl = new URL(normalSearchUrl, window.location.origin);
        allUrl.searchParams.set('q', query);
        viewAll.href = allUrl.toString();
        viewAll.textContent = '';

        const label = document.createTextNode('View all results for “' + query + '”');
        const arrow = document.createElement('i');
        arrow.className = 'fa-solid fa-arrow-right-long';
        viewAll.append(label, arrow);

        footer.classList.add('is-visible');
    };

    const runSearch = async (query) => {
        const trimmed = query.trim();

        if (trimmed.length < 2) {
            if (controller) {
                controller.abort();
                controller = null;
            }

            setLoading(false);
            showMessage(
                'Discover products',
                'Type at least 2 characters to see matching AATCHALA products.',
                'fa-magnifying-glass'
            );
            return;
        }

        if (controller) {
            controller.abort();
        }

        controller = new AbortController();
        const requestId = ++currentRequestId;

        setLoading(true);
        title.textContent = 'Searching';
        count.textContent = '';
        message.hidden = true;
        footer.classList.remove('is-visible');

        try {
            const url = new URL(endpoint, window.location.origin);
            url.searchParams.set('q', trimmed);

            const response = await fetch(url.toString(), {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                signal: controller.signal,
            });

            if (!response.ok) {
                throw new Error('Search request failed');
            }

            const payload = await response.json();

            if (requestId !== currentRequestId) {
                return;
            }

            renderProducts(payload, trimmed);
        } catch (error) {
            if (error.name === 'AbortError') {
                return;
            }

            showMessage(
                'Search is temporarily unavailable',
                'You can still press Enter to use the full search page.',
                'fa-triangle-exclamation'
            );
        } finally {
            if (requestId === currentRequestId) {
                setLoading(false);
            }
        }
    };

    input.addEventListener('input', function () {
        window.clearTimeout(debounceTimer);

        debounceTimer = window.setTimeout(function () {
            runSearch(input.value);
        }, 240);
    });

    suggestionButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            input.value = button.dataset.searchSuggestion || '';
            input.focus();
            runSearch(input.value);
        });
    });

    document.addEventListener('click', function (event) {
        const searchTrigger = event.target.closest('[data-open="search-layer"]');

        if (searchTrigger) {
            window.setTimeout(function () {
                input.focus();
            }, 180);
        }
    });

    layer.addEventListener('transitionend', function () {
        if (!layer.classList.contains('is-open')) {
            if (controller) {
                controller.abort();
                controller = null;
            }
            setLoading(false);
        }
    });
})();
</script>

