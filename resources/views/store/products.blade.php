@extends('layouts.store')





@php



    $heading =

        $pageTitle

        ?? ($subcategory?->name

        ?? $category?->name

        ?? 'Products');





    $activeFilterCount = 0;





    if (request()->filled('min')) {

        $activeFilterCount++;

    }





    if (request()->filled('max')) {

        $activeFilterCount++;

    }





    if (request()->filled('material')) {

        $activeFilterCount++;

    }



@endphp





@section(

    'title',

    $heading . ' | AATCHALA'

)





@section(

    'description',

    'Shop ' . $heading . ' — authentic Bangladeshi craft and lifestyle products, thoughtfully selected by AATCHALA.'

)





@section('content')





<style>



    /* =========================================================

       PAGE

    ========================================================= */



    .collection-page {

        padding-top: 2.5rem;

    }





    /* =========================================================

       BREADCRUMB

    ========================================================= */



    .collection-breadcrumb {

        display: flex;

        align-items: center;

        flex-wrap: wrap;



        gap: .4rem;



        color: #77716a;



        font-size: 11px;

    }





    .collection-breadcrumb a {

        transition: color .25s ease;

    }





    .collection-breadcrumb a:hover {

        color: #c4622f;

    }





    /* =========================================================

       HEADING

    ========================================================= */



    .collection-heading {

        padding:

            clamp(3rem, 5vw, 5rem)

            0

            clamp(2.5rem, 4vw, 4rem);



        text-align: center;

    }





    .collection-count {

        margin-top: 1rem;



        color: #827c75;



        font-size: 13px;

    }





    /* =========================================================

       TOOLBAR

    ========================================================= */



    .collection-toolbar {

        display: flex;



        align-items: center;

        justify-content: space-between;



        gap: 1rem;



        padding:

            .9rem

            0;



        margin-bottom: 1.8rem;



        border-top: 1px solid #ded8cf;

        border-bottom: 1px solid #ded8cf;

    }





    .collection-toolbar-left {

        display: flex;



        align-items: center;



        gap: 1rem;

    }





    .collection-toolbar-title {

        font-size: 10px;



        font-weight: 600;



        letter-spacing: .16em;



        text-transform: uppercase;

    }





    .collection-toolbar-count {

        color: #8b847c;



        font-size: 11px;

    }





    /* =========================================================

       SORT

    ========================================================= */



    .collection-sort {

        position: relative;



        min-width: 185px;

    }





    .collection-sort select {

        width: 100%;

        height: 44px;



        padding:

            0

            2.5rem

            0

            1rem;



        appearance: none;

        -webkit-appearance: none;



        border: 1px solid #d8d1c8;



        background: #faf8f5;



        color: #171717;



        font-size: 11px;



        outline: none;



        cursor: pointer;

    }





    .collection-sort::after {

        content: "";



        position: absolute;



        right: 1rem;

        top: 50%;



        width: 6px;

        height: 6px;



        border-right: 1px solid #171717;

        border-bottom: 1px solid #171717;



        pointer-events: none;



        transform:

            translateY(-70%)

            rotate(45deg);

    }





    /* =========================================================

       SHOP LAYOUT

    ========================================================= */



    .shop-layout {

        display: grid;



        grid-template-columns:

            240px

            minmax(0, 1fr);



        gap:

            clamp(2rem, 3vw, 4rem);



        align-items: start;



        padding-bottom: 4rem;

    }





    /* =========================================================

       FILTER

    ========================================================= */



    .filter-sidebar {

        position: sticky;



        top: 125px;

    }





    .filter-panel {

        border: 1px solid #ded8cf;



        background: #faf8f5;

    }





    .filter-header {

        display: flex;



        align-items: center;

        justify-content: space-between;



        padding: 1.2rem;



        border-bottom: 1px solid #ded8cf;

    }





    .filter-header-left {

        display: flex;



        align-items: center;



        gap: .7rem;

    }





    .filter-header-icon {

        width: 28px;

        height: 28px;



        display: grid;



        place-items: center;



        border: 1px solid #d9d2c9;



        font-size: 11px;

    }





    .filter-heading {

        font-size: 10px;



        font-weight: 600;



        letter-spacing: .15em;



        text-transform: uppercase;

    }





    .filter-active-number {

        min-width: 20px;

        height: 20px;



        display: grid;



        place-items: center;



        padding: 0 5px;



        border-radius: 999px;



        background: #173d32;

        color: white;



        font-size: 9px;

    }





    .filter-section {

        padding: 1.25rem;



        border-bottom: 1px solid #ded8cf;

    }





    .filter-section-title {

        display: flex;



        align-items: center;

        justify-content: space-between;



        margin-bottom: 1rem;



        font-size: 9px;



        font-weight: 600;



        letter-spacing: .17em;



        text-transform: uppercase;

    }





    .filter-section-title::after {

        content: "";



        width: 14px;

        height: 1px;



        background: #aaa39a;

    }





    /* =========================================================

       PRICE

    ========================================================= */



    .price-fields {

        display: grid;



        grid-template-columns:

            minmax(0, 1fr)

            12px

            minmax(0, 1fr);



        gap: .45rem;



        align-items: center;

    }





    .price-separator {

        height: 1px;



        background: #aaa39a;

    }





    .premium-field {

        width: 100%;

        height: 40px;



        min-width: 0;



        padding:

            0

            .7rem;



        border: 1px solid #d8d1c8;



        background: #fff;



        color: #171717;



        font-size: 11px;



        outline: none;



        transition:

            border-color .25s ease,

            box-shadow .25s ease;

    }





    .premium-field:focus {

        border-color: #173d32;



        box-shadow:

            0

            0

            0

            2px

            rgba(23, 61, 50, .06);

    }





    .premium-field[type="number"]::-webkit-outer-spin-button,

    .premium-field[type="number"]::-webkit-inner-spin-button {

        -webkit-appearance: none;



        margin: 0;

    }





    /* =========================================================

       QUICK PRICES

    ========================================================= */



    .quick-price-list {

        display: grid;



        gap: .25rem;



        margin-top: .9rem;

    }





    .quick-price-button {

        width: 100%;



        display: flex;



        align-items: center;

        justify-content: space-between;



        padding: .55rem .2rem;



        border: 0;



        background: transparent;



        color: #635d57;



        font-size: 11px;



        text-align: left;



        cursor: pointer;



        transition:

            color .25s ease,

            padding-left .25s ease;

    }





    .quick-price-button::after {

        content: "";



        width: 5px;

        height: 5px;



        border-top: 1px solid currentColor;

        border-right: 1px solid currentColor;



        opacity: .45;



        transform: rotate(45deg);

    }





    .quick-price-button:hover {

        color: #c4622f;



        padding-left: .35rem;

    }





    /* =========================================================

       MATERIAL

    ========================================================= */



    .material-field-wrapper {

        position: relative;

    }





    .material-field-wrapper i {

        position: absolute;



        right: .85rem;

        top: 50%;



        color: #928b83;



        font-size: 10px;



        pointer-events: none;



        transform: translateY(-50%);

    }





    .material-field-wrapper input {

        padding-right: 2rem;

    }





    .filter-help {

        margin-top: .6rem;



        color: #928b83;



        font-size: 9px;



        line-height: 1.5;

    }





    /* =========================================================

       ACTIVE FILTER

    ========================================================= */



    .active-filters {

        display: flex;



        flex-wrap: wrap;



        gap: .4rem;

    }





    .active-filter-chip {

        padding:

            .4rem

            .5rem;



        border: 1px solid #ddd6cd;



        background: white;



        color: #625c55;



        font-size: 9px;

    }





    /* =========================================================

       FILTER BUTTONS

    ========================================================= */



    .filter-actions {

        display: grid;



        gap: .65rem;



        padding: 1.2rem;

    }





    .apply-filter-button {

        width: 100%;

        height: 43px;



        border: 1px solid #173d32;



        background: #173d32;



        color: white;



        font-size: 9px;



        font-weight: 600;



        letter-spacing: .16em;



        text-transform: uppercase;



        transition:

            background .25s ease,

            border-color .25s ease;

    }





    .apply-filter-button:hover {

        border-color: #c4622f;



        background: #c4622f;

    }





    .clear-filter-button {

        min-height: 40px;



        display: flex;



        align-items: center;

        justify-content: center;



        gap: .5rem;



        border: 1px solid #d8d1c8;



        font-size: 9px;



        letter-spacing: .12em;



        text-transform: uppercase;

    }





    /* =========================================================

       RESULTS

    ========================================================= */



    .product-results {

        min-width: 0;

    }





    .result-information {

        margin-bottom: 1rem;



        color: #827b73;



        font-size: 10px;

    }





    /* =========================================================

       IMPORTANT:

       EXACTLY 3 PRODUCTS ON DESKTOP

    ========================================================= */



    .collection-products {

        display: grid;



        grid-template-columns:

            repeat(

                3,

                minmax(0, 1fr)

            );



        gap:

            2.5rem

            1rem;

    }





    /* =========================================================

       PRODUCT CARD

    ========================================================= */



    .premium-product-card {

        position: relative;



        min-width: 0;

    }





    .premium-product-media {

        position: relative;



        overflow: hidden;



        background: #eee8df;

    }





    .premium-product-image-link {

        display: block;



        width: 100%;

        height: 100%;

    }





    /*

    |--------------------------------------------------------------------------

    | Prevent actions from inheriting image behavior

    |--------------------------------------------------------------------------

    */



    .premium-product-media

    .product-favourite-action {



        position: absolute;



        top: .8rem;

        right: .8rem;



        z-index: 20;

    }





    /* =========================================================

       FAVOURITE

    ========================================================= */



    .product-favourite-button {

        width: 39px;

        height: 39px;



        display: grid;



        place-items: center;



        border: 1px solid rgba(23, 21, 18, .08);



        border-radius: 50%;



        background: rgba(250, 248, 245, .96);



        color: #171512;



        font-size: 15px;



        box-shadow:

            0

            4px

            15px

            rgba(0, 0, 0, .07);



        transition:

            color .25s ease,

            background .25s ease,

            transform .3s cubic-bezier(.22, 1, .36, 1);

    }





    .product-favourite-button:hover {

        background: #173d32;



        color: #fff;



        transform: scale(1.06);

    }



    .product-favourite-button.is-favourite {
        border-color: #c4622f;

        background: #c4622f;

        color: #ffffff;

        box-shadow:
            0
            5px
            18px
            rgba(196, 98, 47, .23);
    }


    .product-favourite-button.is-favourite:hover {
        border-color: #173d32;

        background: #173d32;

        color: #ffffff;
    }





    .product-favourite-button:hover i::before {

        font-weight: 900;

    }





    /* =========================================================

       PRODUCT BADGE

    ========================================================= */



.premium-product-badge {
    position: absolute !important;

    top: .65rem !important;
    left: .65rem !important;

    z-index: 25 !important;

    display: inline-flex;

    align-items: center;
    justify-content: center;

    width: auto;
    min-width: 0;

    margin: 0 !important;

    padding:
        .38rem
        .55rem;

    border: 0;

    background: #111;

    color: #fff;

    font-size: 8px;

    font-weight: 600;

    line-height: 1;

    letter-spacing: .08em;

    text-transform: uppercase;

    pointer-events: none;
}




    /* =========================================================

       HOVER ACTIONS

    ========================================================= */



    .product-hover-actions {

        position: absolute;



        left: .7rem;

        right: .7rem;

        bottom: .7rem;



        z-index: 20;



        display: grid;



        grid-template-columns:

            1fr

            1fr;



        gap: .45rem;



        opacity: 0;



        visibility: hidden;



        transform: translateY(18px);



        transition:

            opacity .3s ease,

            visibility .3s ease,

            transform .4s cubic-bezier(.22, 1, .36, 1);

    }





    .premium-product-card:hover

    .product-hover-actions,



    .premium-product-card:focus-within

    .product-hover-actions {



        opacity: 1;



        visibility: visible;



        transform: translateY(0);

    }





    .product-hover-form {

        min-width: 0;

    }





    .product-hover-button {

        width: 100%;

        min-height: 43px;



        display: flex;



        align-items: center;

        justify-content: center;



        gap: .45rem;



        padding:

            .6rem

            .5rem;



        border: 1px solid #171512;



        font-size: 9px;



        font-weight: 600;



        letter-spacing: .09em;



        text-transform: uppercase;



        cursor: pointer;



        transition:

            color .25s ease,

            background .25s ease,

            border-color .25s ease;

    }





    .product-hover-add {

        background: #171512;



        color: #fff;

    }





    .product-hover-add:hover {

        border-color: #173d32;



        background: #173d32;

    }





    .product-hover-add:disabled {

        cursor: not-allowed;



        opacity: .55;

    }





    .product-hover-quick {

        background: rgba(250, 248, 245, .96);



        color: #171512;

    }





    .product-hover-quick:hover {

        background: #faf8f5;



        color: #c4622f;

    }





    /* =========================================================

       PRODUCT INFO

    ========================================================= */



    .premium-product-info {

        padding-top: .8rem;

    }





    .premium-product-info .product-name {

        transition: color .25s ease;

    }





    .premium-product-info .product-name:hover {

        color: #c4622f;

    }





    /* =========================================================

       MOBILE FILTER BUTTON

    ========================================================= */



    .mobile-filter-button {

        display: none;



        height: 44px;



        align-items: center;

        justify-content: center;



        gap: .5rem;



        padding: 0 1rem;



        border: 1px solid #171512;



        font-size: 9px;



        font-weight: 600;



        letter-spacing: .13em;



        text-transform: uppercase;

    }





    .mobile-filter-count {

        min-width: 18px;

        height: 18px;



        display: grid;



        place-items: center;



        padding: 0 4px;



        border-radius: 50%;



        background: #171512;



        color: white;



        font-size: 8px;

    }





    /* =========================================================

       MOBILE FILTER DRAWER

    ========================================================= */



    .filter-mobile-overlay {

        position: fixed;



        inset: 0;



        z-index: 300;



        display: none;



        background: rgba(0, 0, 0, .38);



        opacity: 0;



        visibility: hidden;



        transition:

            opacity .3s ease,

            visibility .3s ease;

    }





    .filter-mobile-overlay.is-open {

        opacity: 1;



        visibility: visible;

    }





    .filter-mobile-drawer {

        position: absolute;



        top: 0;

        left: 0;

        bottom: 0;



        width: min(90vw, 360px);



        overflow-y: auto;



        background: #faf8f5;



        transform: translateX(-100%);



        transition:

            transform .45s cubic-bezier(.22, 1, .36, 1);

    }





    .filter-mobile-overlay.is-open

    .filter-mobile-drawer {



        transform: translateX(0);

    }





    .filter-mobile-header {

        position: sticky;



        top: 0;



        z-index: 5;



        display: flex;



        align-items: center;

        justify-content: space-between;



        padding: 1.2rem;



        border-bottom: 1px solid #ded8cf;



        background: #faf8f5;

    }





    .filter-mobile-close {

        width: 36px;

        height: 36px;



        display: grid;



        place-items: center;



        border: 1px solid #ded8cf;

    }





    .filter-mobile-drawer

    .filter-panel {



        border: 0;

    }





    .filter-mobile-drawer

    .filter-header {



        display: none;

    }





    /* =========================================================

       QUICK VIEW BACKDROP

    ========================================================= */



    .quick-view-backdrop {

        position: fixed;



        inset: 0;



        z-index: 500;



        display: flex;



        align-items: center;

        justify-content: center;



        padding:

            clamp(1rem, 3vw, 2.5rem);



        background:

            rgba(17, 17, 17, .54);



        backdrop-filter: blur(5px);

        -webkit-backdrop-filter: blur(5px);



        opacity: 0;



        visibility: hidden;



        pointer-events: none;



        transition:

            opacity .3s ease,

            visibility .3s ease;

    }





    .quick-view-backdrop.is-open {

        opacity: 1;



        visibility: visible;



        pointer-events: auto;

    }





    /* =========================================================

       QUICK VIEW DIALOG

    ========================================================= */



    .quick-view-dialog {

        position: relative;



        width: min(920px, 100%);



        max-height: min(720px, 92vh);



        overflow-y: auto;



        display: grid;



        grid-template-columns:

            minmax(0, 1fr)

            minmax(320px, .85fr);



        background: #faf8f5;



        box-shadow:

            0

            30px

            90px

            rgba(0, 0, 0, .24);



        transform:

            translateY(28px)

            scale(.975);



        transition:

            transform .42s cubic-bezier(.22, 1, .36, 1);

    }





    .quick-view-backdrop.is-open

    .quick-view-dialog {



        transform:

            translateY(0)

            scale(1);

    }





    /* =========================================================

       QUICK VIEW IMAGE

    ========================================================= */



    .quick-view-media {

        position: relative;



        min-height: 520px;



        display: flex;



        align-items: center;

        justify-content: center;



        overflow: hidden;



        background: #eee8df;

    }





    .quick-view-media img {

        width: 100%;

        height: 100%;



        position: absolute;



        inset: 0;



        object-fit: contain;

        object-position: center;

    }





    .quick-view-badge {

        position: absolute;



        left: 1rem;

        top: 1rem;



        z-index: 3;



        display: none;



        padding:

            .4rem

            .6rem;



        background: #171512;



        color: #fff;



        font-size: 9px;



        font-weight: 600;



        letter-spacing: .12em;



        text-transform: uppercase;

    }





    .quick-view-badge.is-visible {

        display: inline-flex;

    }





    /* =========================================================

       QUICK VIEW CLOSE

    ========================================================= */



    .quick-view-close {

        position: absolute;



        right: 1rem;

        top: 1rem;



        z-index: 10;



        width: 40px;

        height: 40px;



        display: grid;



        place-items: center;



        border: 1px solid #ded8cf;



        border-radius: 50%;



        background: rgba(250, 248, 245, .95);



        color: #171512;



        transition:

            color .25s ease,

            background .25s ease,

            transform .25s ease;

    }





    .quick-view-close:hover {

        background: #171512;



        color: white;



        transform: rotate(90deg);

    }





    /* =========================================================

       QUICK VIEW INFORMATION

    ========================================================= */



    .quick-view-content {

        display: flex;



        flex-direction: column;



        justify-content: center;



        padding:

            clamp(2rem, 4vw, 3.5rem);

    }





    .quick-view-eyebrow {

        margin-bottom: .65rem;



        color: #8c857d;



        font-size: 9px;



        font-weight: 600;



        letter-spacing: .18em;



        text-transform: uppercase;

    }





    .quick-view-title {

        font-family: 'Cormorant Garamond', Georgia, serif;



        font-size: clamp(1.5rem, 1vw, 2rem);



        font-weight: 400;



        line-height: .98;

    }





    .quick-view-price {

        display: flex;



        align-items: center;



        gap: .65rem;



        margin-top: 1.2rem;



        font-size: 15px;

    }





    .quick-view-price-current {

        font-weight: 600;

    }





    .quick-view-price-old {

        color: #8d867e;



        text-decoration: line-through;

    }





    .quick-view-description {

        margin-top: 1.3rem;



        color: #676159;



        font-size: 13px;



        line-height: 1.75;

    }





    .quick-view-stock {

        display: flex;



        align-items: center;



        gap: .45rem;



        margin-top: 1.2rem;



        color: #6f6961;



        font-size: 10px;

    }





    .quick-view-stock-dot {

        width: 6px;

        height: 6px;



        border-radius: 50%;



        background: #27714e;

    }





    .quick-view-stock.is-out

    .quick-view-stock-dot {



        background: #a44b3e;

    }





    /* =========================================================

       QUICK VIEW CART

    ========================================================= */



    .quick-view-cart {

        display: grid;



        grid-template-columns:

            90px

            minmax(0, 1fr);



        gap: .7rem;



        margin-top: 1.7rem;

    }





    .quick-view-quantity {

        width: 100%;

        height: 48px;



        padding:

            0

            .8rem;



        border: 1px solid #d8d1c8;



        background: #fff;



        text-align: center;



        outline: none;

    }





    .quick-view-add {

        height: 48px;



        border: 1px solid #171512;



        background: #171512;



        color: #fff;



        font-size: 10px;



        font-weight: 600;



        letter-spacing: .15em;



        text-transform: uppercase;



        transition:

            background .25s ease,

            border-color .25s ease;

    }





    .quick-view-add:hover {

        border-color: #173d32;



        background: #173d32;

    }





    .quick-view-add:disabled {

        opacity: .5;



        cursor: not-allowed;

    }





    .quick-view-details {

        display: inline-flex;



        align-items: center;



        gap: .65rem;



        width: fit-content;



        margin-top: 1.3rem;



        padding-bottom: .3rem;



        border-bottom: 1px solid #171512;



        font-size: 9px;



        font-weight: 600;



        letter-spacing: .14em;



        text-transform: uppercase;



        transition:

            color .25s ease,

            border-color .25s ease;

    }





    .quick-view-details:hover {

        color: #c4622f;



        border-color: #c4622f;

    }





    /* =========================================================

       EMPTY

    ========================================================= */



    .premium-empty-state {

        grid-column: 1 / -1;



        padding: 5rem 2rem;



        border: 1px solid #ded8cf;



        text-align: center;

    }





    /* =========================================================

       TABLET

       ALWAYS 2 PRODUCTS

    ========================================================= */



    @media (max-width: 1100px) {



        .shop-layout {

            grid-template-columns:

                210px

                minmax(0, 1fr);



            gap: 1.5rem;

        }





        .collection-products {

            grid-template-columns:

                repeat(

                    2,

                    minmax(0, 1fr)

                );

        }



    }





    /* =========================================================

       FILTER DRAWER BREAKPOINT

    ========================================================= */



    @media (max-width: 900px) {



        .desktop-filter-sidebar {

            display: none;

        }





        .shop-layout {

            display: block;

        }





        .mobile-filter-button {

            display: inline-flex;

        }





        .filter-mobile-overlay {

            display: block;

        }





        .collection-toolbar-title,

        .collection-toolbar-count {

            display: none;

        }





        .collection-toolbar-left {

            flex: 1;

        }



    }





    /* =========================================================

       TOUCH DEVICES

       Keep actions accessible without hover

    ========================================================= */



    @media (hover: none) {



        .product-hover-actions {

            opacity: 1;



            visibility: visible;



            transform: none;

        }



    }





    /* =========================================================

       MOBILE

       KEEP TWO PRODUCTS PER ROW

    ========================================================= */



    @media (max-width: 680px) {



        .collection-page {

            padding-top: 1.4rem;

        }





        .collection-heading {

            padding:

                2.5rem

                0;

        }





        .collection-toolbar {

            align-items: stretch;



            gap: .6rem;



            margin-bottom: 1.2rem;

        }





        .collection-toolbar-left {

            flex: 0 0 auto;

        }





        .collection-sort {

            flex: 1;



            min-width: 0;

        }





        .collection-products {

            grid-template-columns:

                repeat(

                    2,

                    minmax(0, 1fr)

                );



            gap:

                2rem

                .65rem;

        }





        .product-favourite-action {

            top: .35rem !important;

            right: .35rem !important;

        }





        .product-favourite-button {

            width: 31px;

            height: 31px;



            font-size: 12px;

        }





        .product-hover-actions {

            left: .28rem;

            right: .28rem;

            bottom: .28rem;



            gap: .25rem;

        }





        .product-hover-button {

            min-height: 29px;

            height: 29px;



            padding:

                0

                .18rem;



            gap: .18rem;



            font-size: 6.5px;



            letter-spacing: .015em;

        }





        .product-hover-button i {

            font-size: 8px;

        }





        .premium-product-info .product-name {

            font-size: 16px;

        }





        .premium-product-info .text-xs {

            font-size: 10px;

        }





        .quick-view-dialog {

            grid-template-columns: 1fr;



            width: min(94vw, 520px);

        }





        .quick-view-media {

            min-height: 360px;

        }





        .quick-view-content {

            padding: 1.6rem;

        }





        .quick-view-title {

            font-size: 2.7rem;

        }



    }






    /* =========================================================
       VERY SMALL PHONES
       Keep image coverage minimal
    ========================================================= */

    @media (max-width: 390px) {

        .product-hover-button span {
            display: none;
        }


        .product-hover-button {
            min-height: 28px;
            height: 28px;

            padding: 0;
        }


        .product-hover-button i {
            font-size: 10px;
        }


        .product-favourite-button {
            width: 29px;
            height: 29px;

            font-size: 11px;
        }

    }

</style>







{{-- ============================================================

    COLLECTION PAGE

============================================================ --}}



<div class="wrap collection-page">





    {{-- BREADCRUMB --}}



    <nav

        class="collection-breadcrumb"

        aria-label="Breadcrumb"

    >



        <a href="{{ route('home') }}">



            Home



        </a>





        @if($category)



            <span>/</span>





            <a href="{{ route('categories.show', $category) }}">



                {{ $category->name }}



            </a>



        @endif





        <span>/</span>





        <span>



            {{ $heading }}



        </span>





    </nav>







    {{-- PAGE TITLE --}}



    <section class="collection-heading">





        <p class="eyebrow">



            Handmade in Bangladesh



        </p>





        <h1 class="heading mt-2">



            {{ $heading }}



        </h1>





        <p class="collection-count">



            {{ number_format($products->total()) }}



            {{ $products->total() === 1 ? 'piece' : 'pieces' }}



        </p>





    </section>







    {{-- ========================================================

        TOOLBAR

    ======================================================== --}}



    <div class="collection-toolbar">





        <div class="collection-toolbar-left">





            <button

                type="button"

                class="mobile-filter-button"

                data-filter-open

            >



                <i class="fa-solid fa-sliders"></i>



                Filter





                @if($activeFilterCount > 0)



                    <span class="mobile-filter-count">



                        {{ $activeFilterCount }}



                    </span>



                @endif





            </button>







            <span class="collection-toolbar-title">



                Collection



            </span>





            <span class="collection-toolbar-count">



                {{ number_format($products->total()) }} products



            </span>





        </div>







        {{-- SORT --}}



        <form

            method="GET"

            action="{{ url()->current() }}"

            class="collection-sort"

        >





            @if(request()->filled('min'))



                <input

                    type="hidden"

                    name="min"

                    value="{{ request('min') }}"

                >



            @endif





            @if(request()->filled('max'))



                <input

                    type="hidden"

                    name="max"

                    value="{{ request('max') }}"

                >



            @endif





            @if(request()->filled('material'))



                <input

                    type="hidden"

                    name="material"

                    value="{{ request('material') }}"

                >



            @endif





            <select

                name="sort"

                onchange="this.form.submit()"

                aria-label="Sort products"

            >



                <option

                    value=""

                    @selected(!request()->filled('sort'))

                >

                    Newest

                </option>





                <option

                    value="price_asc"

                    @selected(request('sort') === 'price_asc')

                >

                    Price: Low to High

                </option>





                <option

                    value="price_desc"

                    @selected(request('sort') === 'price_desc')

                >

                    Price: High to Low

                </option>





                <option

                    value="rating"

                    @selected(request('sort') === 'rating')

                >

                    Top Rated

                </option>



            </select>





        </form>





    </div>







    {{-- ========================================================

        MAIN

    ======================================================== --}}



    <div class="shop-layout">





        {{-- ====================================================

            DESKTOP FILTER

        ==================================================== --}}



        <aside class="filter-sidebar desktop-filter-sidebar">





            <form

                method="GET"

                action="{{ url()->current() }}"

                class="filter-panel"

            >





                @if(request()->filled('sort'))



                    <input

                        type="hidden"

                        name="sort"

                        value="{{ request('sort') }}"

                    >



                @endif







                <div class="filter-header">





                    <div class="filter-header-left">





                        <span class="filter-header-icon">



                            <i class="fa-solid fa-sliders"></i>



                        </span>





                        <span class="filter-heading">



                            Filters



                        </span>





                    </div>





                    @if($activeFilterCount > 0)



                        <span class="filter-active-number">



                            {{ $activeFilterCount }}



                        </span>



                    @endif





                </div>







                @if($activeFilterCount > 0)



                    <div class="filter-section">





                        <div class="filter-section-title">



                            Active



                        </div>





                        <div class="active-filters">





                            @if(request()->filled('min'))



                                <span class="active-filter-chip">



                                    From ৳{{ number_format((float) request('min')) }}



                                </span>



                            @endif





                            @if(request()->filled('max'))



                                <span class="active-filter-chip">



                                    To ৳{{ number_format((float) request('max')) }}



                                </span>



                            @endif





                            @if(request()->filled('material'))



                                <span class="active-filter-chip">



                                    {{ request('material') }}



                                </span>



                            @endif





                        </div>





                    </div>



                @endif







                {{-- PRICE --}}



                <div class="filter-section">





                    <div class="filter-section-title">



                        Price



                    </div>







                    <div class="price-fields">





                        <input

                            id="desktop-price-min"

                            class="premium-field"

                            type="number"

                            min="0"

                            name="min"

                            value="{{ request('min') }}"

                            placeholder="Min ৳"

                        >





                        <span class="price-separator"></span>





                        <input

                            id="desktop-price-max"

                            class="premium-field"

                            type="number"

                            min="0"

                            name="max"

                            value="{{ request('max') }}"

                            placeholder="Max ৳"

                        >





                    </div>







                    <div class="quick-price-list">





                        <button

                            type="button"

                            class="quick-price-button"

                            data-price-target="desktop"

                            data-price-min=""

                            data-price-max="3000"

                        >

                            Under ৳3,000

                        </button>





                        <button

                            type="button"

                            class="quick-price-button"

                            data-price-target="desktop"

                            data-price-min="3000"

                            data-price-max="7000"

                        >

                            ৳3,000 – ৳7,000

                        </button>





                        <button

                            type="button"

                            class="quick-price-button"

                            data-price-target="desktop"

                            data-price-min="7000"

                            data-price-max="15000"

                        >

                            ৳7,000 – ৳15,000

                        </button>





                        <button

                            type="button"

                            class="quick-price-button"

                            data-price-target="desktop"

                            data-price-min="15000"

                            data-price-max=""

                        >

                            Above ৳15,000

                        </button>





                    </div>





                </div>







                {{-- MATERIAL --}}



                <div class="filter-section">





                    <div class="filter-section-title">



                        Material



                    </div>





                    <div class="material-field-wrapper">





                        <input

                            class="premium-field"

                            type="text"

                            name="material"

                            value="{{ request('material') }}"

                            placeholder="e.g. Cotton, Jute"

                        >





                        <i class="fa-solid fa-magnifying-glass"></i>





                    </div>





                    <p class="filter-help">



                        Search products by their material.



                    </p>





                </div>







                <div class="filter-actions">





                    <button

                        type="submit"

                        class="apply-filter-button"

                    >



                        Apply Filters



                    </button>







                    @if($activeFilterCount > 0)



                        <a

                            href="{{ url()->current() }}{{ request()->filled('sort') ? '?sort=' . urlencode(request('sort')) : '' }}"

                            class="clear-filter-button"

                        >



                            Clear Filters



                        </a>



                    @endif





                </div>





            </form>





        </aside>







        {{-- ====================================================

            PRODUCTS

        ==================================================== --}}



        <section class="product-results">





            <div class="result-information">



                Showing



                <strong>

                    {{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }}

                </strong>



                of



                <strong>

                    {{ number_format($products->total()) }}

                </strong>



                products



            </div>







            <div class="collection-products">





                @forelse($products as $product)





                    <x-product-card

                        :product="$product"

                        :quick-view="true"

                    />





                @empty





                    <div class="premium-empty-state">





                        <i class="fa-solid fa-feather text-4xl muted"></i>





                        <h2 class="text-3xl mt-4">



                            Nothing found here yet.



                        </h2>





                        <p class="muted mt-2">



                            Try changing or removing your filters.



                        </p>





                    </div>





                @endforelse





            </div>







            @if($products->hasPages())



                <div class="pagination pt-12 pb-16">



                    {{ $products->links() }}



                </div>



            @else



                <div class="pb-16"></div>



            @endif





        </section>





    </div>





</div>







{{-- ============================================================

    MOBILE FILTER

============================================================ --}}



<div

    class="filter-mobile-overlay"

    data-filter-overlay

>





    <div class="filter-mobile-drawer">





        <div class="filter-mobile-header">





            <div>





                <p class="eyebrow">



                    Refine Collection



                </p>





                <h2 class="text-2xl mt-1">



                    Filters



                </h2>





            </div>







            <button

                type="button"

                class="filter-mobile-close"

                data-filter-close

            >



                <i class="fa-solid fa-xmark"></i>



            </button>





        </div>







        <form

            method="GET"

            action="{{ url()->current() }}"

            class="filter-panel"

        >





            @if(request()->filled('sort'))



                <input

                    type="hidden"

                    name="sort"

                    value="{{ request('sort') }}"

                >



            @endif







            <div class="filter-section">





                <div class="filter-section-title">



                    Price



                </div>





                <div class="price-fields">





                    <input

                        id="mobile-price-min"

                        class="premium-field"

                        type="number"

                        min="0"

                        name="min"

                        value="{{ request('min') }}"

                        placeholder="Min ৳"

                    >





                    <span class="price-separator"></span>





                    <input

                        id="mobile-price-max"

                        class="premium-field"

                        type="number"

                        min="0"

                        name="max"

                        value="{{ request('max') }}"

                        placeholder="Max ৳"

                    >





                </div>







                <div class="quick-price-list">





                    <button

                        type="button"

                        class="quick-price-button"

                        data-price-target="mobile"

                        data-price-min=""

                        data-price-max="3000"

                    >

                        Under ৳3,000

                    </button>





                    <button

                        type="button"

                        class="quick-price-button"

                        data-price-target="mobile"

                        data-price-min="3000"

                        data-price-max="7000"

                    >

                        ৳3,000 – ৳7,000

                    </button>





                    <button

                        type="button"

                        class="quick-price-button"

                        data-price-target="mobile"

                        data-price-min="7000"

                        data-price-max="15000"

                    >

                        ৳7,000 – ৳15,000

                    </button>





                    <button

                        type="button"

                        class="quick-price-button"

                        data-price-target="mobile"

                        data-price-min="15000"

                        data-price-max=""

                    >

                        Above ৳15,000

                    </button>





                </div>





            </div>







            <div class="filter-section">





                <div class="filter-section-title">



                    Material



                </div>





                <div class="material-field-wrapper">





                    <input

                        class="premium-field"

                        type="text"

                        name="material"

                        value="{{ request('material') }}"

                        placeholder="e.g. Cotton, Jute"

                    >





                    <i class="fa-solid fa-magnifying-glass"></i>





                </div>





            </div>







            <div class="filter-actions">





                <button

                    type="submit"

                    class="apply-filter-button"

                >



                    Show Products



                </button>





                @if($activeFilterCount > 0)



                    <a

                        href="{{ url()->current() }}"

                        class="clear-filter-button"

                    >



                        Clear Filters



                    </a>



                @endif





            </div>





        </form>





    </div>





</div>







{{-- ============================================================

    QUICK VIEW MODAL

============================================================ --}}



<div

    class="quick-view-backdrop"

    id="product-quick-view"

    data-quick-view-modal

    aria-hidden="true"

>





    <div

        class="quick-view-dialog"

        role="dialog"

        aria-modal="true"

        aria-labelledby="quick-view-title"

    >





        <button

            type="button"

            class="quick-view-close"

            data-qv-close

            aria-label="Close quick view"

        >



            <i class="fa-solid fa-xmark"></i>



        </button>







        {{-- IMAGE --}}



        <div class="quick-view-media">





            <span

                class="quick-view-badge"

                data-qv-modal-badge

            ></span>





            <img

                src="{{ asset('images/placeholder.webp') }}"

                alt=""

                data-qv-modal-image

            >





        </div>







        {{-- DETAILS --}}



        <div class="quick-view-content">





            <p

                class="quick-view-eyebrow"

                data-qv-modal-material

            >



                AATCHALA



            </p>







            <h2

                class="quick-view-title"

                id="quick-view-title"

                data-qv-modal-name

            ></h2>







            <div class="quick-view-price">





                <span

                    class="quick-view-price-current"

                    data-qv-modal-price

                ></span>





                <span

                    class="quick-view-price-old"

                    data-qv-modal-compare

                ></span>





            </div>







            <p

                class="quick-view-description"

                data-qv-modal-description

            ></p>







            <div

                class="quick-view-stock"

                data-qv-modal-stock-wrap

            >





                <span class="quick-view-stock-dot"></span>





                <span data-qv-modal-stock></span>





            </div>







                <form
                    method="POST"

                    class="quick-view-cart"

                    data-qv-cart-form
                    data-ajax-cart
                >



                @csrf





                <input

                    class="quick-view-quantity"

                    type="number"

                    name="quantity"

                    min="1"

                    max="10"

                    value="1"

                    aria-label="Quantity"

                >





                <button

                    type="submit"

                    class="quick-view-add"

                    data-qv-add-button

                >



                    Add to Bag



                </button>





            </form>







            <a

                href="#"

                class="quick-view-details"

                data-qv-details

            >



                View Full Details



                <i class="fa-solid fa-arrow-right-long"></i>



            </a>





        </div>





    </div>





</div>







{{-- ============================================================

    JAVASCRIPT

============================================================ --}}



<script>



document.addEventListener(

    'DOMContentLoaded',

    function () {





        /* =====================================================

           FILTER DRAWER

        ===================================================== */



        const filterOverlay =

            document.querySelector(

                '[data-filter-overlay]'

            );





        const filterOpenButtons =

            document.querySelectorAll(

                '[data-filter-open]'

            );





        const filterCloseButtons =

            document.querySelectorAll(

                '[data-filter-close]'

            );





        function openFilter() {



            if (!filterOverlay) {

                return;

            }





            filterOverlay.classList.add(

                'is-open'

            );





            document.body.style.overflow =

                'hidden';



        }





        function closeFilter() {



            if (!filterOverlay) {

                return;

            }





            filterOverlay.classList.remove(

                'is-open'

            );





            document.body.style.overflow =

                '';



        }





        filterOpenButtons.forEach(

            function (button) {



                button.addEventListener(

                    'click',

                    openFilter

                );



            }

        );





        filterCloseButtons.forEach(

            function (button) {



                button.addEventListener(

                    'click',

                    closeFilter

                );



            }

        );





        if (filterOverlay) {



            filterOverlay.addEventListener(

                'click',

                function (event) {



                    if (

                        event.target ===

                        filterOverlay

                    ) {



                        closeFilter();



                    }



                }

            );



        }







        /* =====================================================

           QUICK PRICE BUTTONS

        ===================================================== */



        document

            .querySelectorAll(

                '[data-price-target]'

            )

            .forEach(

                function (button) {





                    button.addEventListener(

                        'click',

                        function () {





                            const target =

                                button.dataset.priceTarget;





                            const min =

                                button.dataset.priceMin || '';





                            const max =

                                button.dataset.priceMax || '';





                            const minInput =

                                document.getElementById(

                                    target +

                                    '-price-min'

                                );





                            const maxInput =

                                document.getElementById(

                                    target +

                                    '-price-max'

                                );





                            if (minInput) {

                                minInput.value = min;

                            }





                            if (maxInput) {

                                maxInput.value = max;

                            }





                        }

                    );





                }

            );







        /* =====================================================

           QUICK VIEW

        ===================================================== */



        const quickViewModal =

            document.querySelector(

                '[data-quick-view-modal]'

            );





        if (!quickViewModal) {

            return;

        }





        const modalImage =

            quickViewModal.querySelector(

                '[data-qv-modal-image]'

            );





        const modalName =

            quickViewModal.querySelector(

                '[data-qv-modal-name]'

            );





        const modalMaterial =

            quickViewModal.querySelector(

                '[data-qv-modal-material]'

            );





        const modalPrice =

            quickViewModal.querySelector(

                '[data-qv-modal-price]'

            );





        const modalCompare =

            quickViewModal.querySelector(

                '[data-qv-modal-compare]'

            );





        const modalDescription =

            quickViewModal.querySelector(

                '[data-qv-modal-description]'

            );





        const modalStock =

            quickViewModal.querySelector(

                '[data-qv-modal-stock]'

            );





        const modalStockWrap =

            quickViewModal.querySelector(

                '[data-qv-modal-stock-wrap]'

            );





        const modalBadge =

            quickViewModal.querySelector(

                '[data-qv-modal-badge]'

            );





        const cartForm =

            quickViewModal.querySelector(

                '[data-qv-cart-form]'

            );





        const addButton =

            quickViewModal.querySelector(

                '[data-qv-add-button]'

            );





        const detailsLink =

            quickViewModal.querySelector(

                '[data-qv-details]'

            );





        const closeButton =

            quickViewModal.querySelector(

                '[data-qv-close]'

            );





        let lastQuickViewTrigger = null;







        /* =====================================================

           OPEN QUICK VIEW

        ===================================================== */



        function openQuickView(button) {



            lastQuickViewTrigger =

                button;





            const data =

                button.dataset;





            modalImage.src =

                data.qvImage;





            modalImage.alt =

                data.qvName;





            modalName.textContent =

                data.qvName;





            modalMaterial.textContent =

                data.qvMaterial

                    ? data.qvMaterial

                    : 'AATCHALA';





            modalPrice.textContent =

                data.qvPrice;





            modalCompare.textContent =

                data.qvCompare || '';





            modalCompare.style.display =

                data.qvCompare

                    ? ''

                    : 'none';





            modalDescription.textContent =

                data.qvDescription

                    ? data.qvDescription

                    : 'Discover this piece from AATCHALA.';





            modalStock.textContent =

                data.qvStock;





            const inStock =

                data.qvInStock === '1';





            modalStockWrap.classList.toggle(

                'is-out',

                !inStock

            );





            if (data.qvBadge) {



                modalBadge.textContent =

                    data.qvBadge;





                modalBadge.classList.add(

                    'is-visible'

                );



            } else {



                modalBadge.textContent =

                    '';





                modalBadge.classList.remove(

                    'is-visible'

                );



            }





            cartForm.action =

                data.qvCartUrl;





            detailsLink.href =

                data.qvUrl;





            addButton.disabled =

                !inStock;





            addButton.textContent =

                inStock

                    ? 'Add to Bag'

                    : 'Sold Out';





            quickViewModal.classList.add(

                'is-open'

            );





            quickViewModal.setAttribute(

                'aria-hidden',

                'false'

            );





            document.body.style.overflow =

                'hidden';





            window.setTimeout(

                function () {



                    closeButton.focus();



                },

                100

            );



        }







        /* =====================================================

           CLOSE QUICK VIEW

        ===================================================== */



        function closeQuickView() {



            quickViewModal.classList.remove(

                'is-open'

            );





            quickViewModal.setAttribute(

                'aria-hidden',

                'true'

            );





            document.body.style.overflow =

                '';





            if (lastQuickViewTrigger) {



                lastQuickViewTrigger.focus();



            }



        }







        /* =====================================================

           QUICK VIEW BUTTONS

        ===================================================== */



        document

            .querySelectorAll(

                '.js-quick-view'

            )

            .forEach(

                function (button) {





                    button.addEventListener(

                        'click',

                        function () {



                            openQuickView(

                                button

                            );



                        }

                    );





                }

            );







        closeButton.addEventListener(

            'click',

            closeQuickView

        );







        quickViewModal.addEventListener(

            'click',

            function (event) {



                if (

                    event.target ===

                    quickViewModal

                ) {



                    closeQuickView();



                }



            }

        );







        /* =====================================================

           ESCAPE

        ===================================================== */



        document.addEventListener(

            'keydown',

            function (event) {





                if (

                    event.key ===

                    'Escape'

                ) {





                    if (

                        quickViewModal

                            .classList

                            .contains(

                                'is-open'

                            )

                    ) {



                        closeQuickView();



                        return;



                    }





                    closeFilter();



                }



            }

        );





    }

);



</script>





@endsection