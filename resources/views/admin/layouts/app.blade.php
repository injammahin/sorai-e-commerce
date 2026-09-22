<!doctype html>
<html lang="en">
<head>

    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >


    <title>
        @yield('title', 'Admin') — SARAI
    </title>


    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

</head>


<body class="admin-body">


<div
    class="admin-shell"
    data-admin-shell
>


    {{-- =========================================================
        SIDEBAR
    ========================================================== --}}
    <aside
        id="admin-sidebar"
        class="admin-sidebar"
        data-admin-sidebar
        aria-label="Administration sidebar"
    >


        {{-- =====================================================
            BRAND
        ====================================================== --}}
        <a
            class="admin-brand"
            href="{{ route('admin.dashboard') }}"
            data-admin-tooltip="Dashboard"
        >


            <span class="admin-brand-mark">

                <img
                    src="{{ asset('images/logo/sarai-mark.webp') }}"
                    alt="SARAI"
                >

            </span>


            <span class="admin-brand-copy">

                <strong>
                    SARAI
                </strong>

                <small>
                    Commerce Admin
                </small>

            </span>


        </a>



        {{-- =====================================================
            NAVIGATION
        ====================================================== --}}
        <nav
            class="admin-nav"
            aria-label="Admin navigation"
        >


            @php

                $items = [

                    [
                        'admin.dashboard',
                        'fa-chart-pie',
                        'Dashboard'
                    ],

                    [
                        'admin.orders.index',
                        'fa-receipt',
                        'Orders'
                    ],

                    [
                        'admin.products.index',
                        'fa-box',
                        'Products'
                    ],

                    [
                        'admin.categories.index',
                        'fa-layer-group',
                        'Categories'
                    ],

                    [
                        'admin.collections.index',
                        'fa-shapes',
                        'Collections'
                    ],

                    [
                        'admin.customers.index',
                        'fa-users',
                        'Customers'
                    ],

                    [
                        'admin.banners.index',
                        'fa-images',
                        'Banners'
                    ],

                    [
                        'admin.posts.index',
                        'fa-book-open',
                        'Journal'
                    ],

                    [
                        'admin.pages.index',
                        'fa-file-lines',
                        'Pages'
                    ],

                    [
                        'admin.coupons.index',
                        'fa-ticket',
                        'Coupons'
                    ],

                    [
                        'admin.reviews.index',
                        'fa-star',
                        'Reviews'
                    ],

                    [
                        'admin.messages.index',
                        'fa-envelope',
                        'Messages'
                    ],

                    [
                        'admin.subscribers.index',
                        'fa-paper-plane',
                        'Subscribers'
                    ],

                    [
                        'admin.settings.index',
                        'fa-gear',
                        'Settings'
                    ],

                ];

            @endphp



            @foreach($items as [$route, $icon, $label])


                @php

                    $routePattern =
                        str_replace(
                            '.index',
                            '.*',
                            $route
                        );


                    $isActive =

                        request()->routeIs(
                            $routePattern
                        )

                        ||

                        request()->routeIs(
                            $route
                        );

                @endphp



                <a
                    class="
                        admin-nav-link

                        {{
                            $isActive
                                ? 'active'
                                : ''
                        }}
                    "
                    href="{{ route($route) }}"
                    data-admin-tooltip="{{ $label }}"
                    @if($isActive)
                        aria-current="page"
                    @endif
                >


                    <span class="admin-nav-icon">

                        <i
                            class="
                                fa-solid
                                {{ $icon }}
                            "
                        ></i>

                    </span>


                    <span class="admin-nav-label">

                        {{ $label }}

                    </span>


                </a>


            @endforeach


        </nav>



        {{-- =====================================================
            SIDEBAR FOOTER
        ====================================================== --}}
        <div class="admin-sidebar-footer">


            <a
                class="admin-utility-link"
                href="{{ route('home') }}"
                target="_blank"
                rel="noopener"
                data-admin-tooltip="View storefront"
            >


                <span class="admin-nav-icon">

                    <i
                        class="
                            fa-solid
                            fa-arrow-up-right-from-square
                        "
                    ></i>

                </span>


                <span class="admin-nav-label">

                    View storefront

                </span>


            </a>



            <form
                method="POST"
                action="{{ route('logout') }}"
                class="admin-logout-form"
            >

                @csrf


                <button
                    type="submit"
                    class="admin-utility-link"
                    data-admin-tooltip="Sign out"
                >


                    <span class="admin-nav-icon">

                        <i
                            class="
                                fa-solid
                                fa-right-from-bracket
                            "
                        ></i>

                    </span>


                    <span class="admin-nav-label">

                        Sign out

                    </span>


                </button>


            </form>


        </div>


    </aside>



    {{-- =========================================================
        MOBILE BACKDROP
    ========================================================== --}}
    <button
        type="button"
        class="admin-sidebar-overlay"
        data-admin-overlay
        aria-label="Close sidebar"
        tabindex="-1"
    ></button>



    {{-- =========================================================
        MAIN CONTENT
    ========================================================== --}}
    <div class="admin-main">


        {{-- =====================================================
            TOPBAR
        ====================================================== --}}
        <header class="admin-topbar">


            <div class="admin-topbar-left">


                {{-- Hamburger --}}
                <button
                    type="button"
                    class="admin-menu-button"
                    data-admin-menu
                    aria-controls="admin-sidebar"
                    aria-expanded="true"
                    aria-label="Collapse sidebar"
                    title="Toggle sidebar"
                >

                    <i
                        class="
                            fa-solid
                            fa-bars
                        "
                    ></i>

                </button>



                {{-- PAGE TITLE --}}
                <div class="admin-page-heading">


                    <small>

                        @yield(
                            'eyebrow',
                            'SARAI administration'
                        )

                    </small>


                    <h1>

                        @yield(
                            'page_title',
                            'Dashboard'
                        )

                    </h1>


                </div>


            </div>



            {{-- USER --}}
            <div class="admin-user-box">


                <span class="admin-user-name">

                    {{ auth()->user()->name }}

                </span>


                <span class="admin-user-avatar">

                    {{
                        strtoupper(
                            substr(
                                auth()->user()->name,
                                0,
                                1
                            )
                        )
                    }}

                </span>


            </div>


        </header>



        {{-- =====================================================
            CONTENT
        ====================================================== --}}
        <main class="admin-content">


            <x-flash />


            @yield('content')


        </main>


    </div>


</div>



{{-- =============================================================
    COLLAPSED MENU HOVER LABEL

    This appears beside the icon.

    Important:
    Clicking this label triggers the original menu item, meaning
    Dashboard → Dashboard
    Products  → Products
    Orders    → Orders
    etc.
============================================================== --}}
<button
    type="button"
    id="admin-nav-tooltip"
    class="admin-nav-tooltip"
    aria-hidden="true"
    tabindex="-1"
></button>



@stack('scripts')


</body>
</html>