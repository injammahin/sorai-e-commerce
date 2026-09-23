{{-- AATCHALA HEADER V2.1 FIXED: Laravel 9-safe PHP blocks + smooth hover previews --}}
@php
    $cartService = app(\App\Services\CartService::class);
@endphp

<div class="announcement">
    <div class="wrap text-center">
        {{ $siteSettings['announcement'] ?? 'Complimentary delivery across Bangladesh on orders over ৳5,000' }}
    </div>
</div>

<header class="site-header">
    <div class="wrap main-row">
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

        <a href="{{ route('home') }}" aria-label="AATCHALA home">
            <img
                class="logo"
                src="{{ asset('images/logo/aatchala-lockup.webp') }}"
                alt="aatchala"
            >
        </a>

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
                class="icon-link"
                href="{{ auth()->check() ? route('wishlist.index') : route('login') }}"
                aria-label="Wishlist"
            >
                <i class="fa-regular fa-heart"></i>
            </a>

            <a
                class="icon-link"
                href="{{ route('cart.index') }}"
                aria-label="Bag"
            >
                <i class="fa-solid fa-bag-shopping"></i>

                @if($cartService->count())
                    <span class="count">
                        {{ $cartService->count() }}
                    </span>
                @endif
            </a>
        </div>
    </div>

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

                <div class="nav-wrap" data-mega-menu>
                    <a
                        class="nav-item"
                        href="{{ route('categories.show', $nav) }}"
                        aria-haspopup="true"
                        aria-expanded="false"
                    >
                        {{ $nav->name }}
                    </a>

                    <div class="mega-menu">
                        <div class="wrap mega-grid">
                            {{-- Menu links --}}
                            <div>
                                <p class="eyebrow mb-3">
                                    {{ $nav->tagline }}
                                </p>

                                <div class="mega-links">
                                    <a
                                        class="mega-link"
                                        href="{{ route('new-arrivals') }}"
                                        data-menu-preview
                                        data-preview-image="{{ asset('images/category/new-arrivals.webp') }}"
                                        data-preview-title="New Arrivals"
                                        data-preview-eyebrow="Latest from AATCHALA"
                                        data-preview-alt="AATCHALA new arrivals"
                                        data-preview-url="{{ route('new-arrivals') }}"
                                    >
                                        New Arrivals
                                    </a>

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

                            {{-- Dynamic category preview --}}
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

                            {{-- Featured product --}}
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

            <a
                class="nav-item"
                href="{{ route('new-arrivals') }}"
            >
                New Arrivals
            </a>
        </div>
    </nav>
</header>

{{-- Search overlay --}}
<div
    id="search-layer"
    class="search-layer"
    data-layer
>
    <button
        class="absolute right-8 top-8 text-2xl"
        type="button"
        data-close
        aria-label="Close search"
    >
        <i class="fa-solid fa-xmark"></i>
    </button>

    <form
        class="search-box"
        action="{{ route('search') }}"
        method="GET"
    >
        <p class="eyebrow mb-5">
            Search AATCHALA
        </p>

        <div class="flex border-b border-black">
            <input
                class="w-full bg-transparent py-4 text-2xl outline-none"
                type="search"
                name="q"
                placeholder="Jamdani, kantha, home décor…"
                autocomplete="off"
            >

            <button
                class="px-4"
                type="submit"
                aria-label="Submit search"
            >
                <i class="fa-solid fa-arrow-right"></i>
            </button>
        </div>

        <p class="mt-4 text-xs muted">
            Try “Jamdani saree”, “Nakshi Kantha”,
            “Shital Pati” or “handmade gifts”.
        </p>
    </form>
</div>

{{-- Mobile navigation --}}
<div
    id="mobile-menu"
    class="drawer-backdrop"
    data-layer
>
    <nav
        class="drawer mobile-nav"
        aria-label="Mobile navigation"
    >
        <div class="flex items-center justify-between mb-8">
            <img
                class="h-12"
                src="{{ asset('images/logo/aatchala-lockup.webp') }}"
                alt="aatchala"
            >

            <button
                type="button"
                data-close
                class="text-xl"
                aria-label="Close menu"
            >
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <a
            class="block py-3 border-b hairline"
            href="{{ route('new-arrivals') }}"
        >
            New Arrivals
        </a>

        @foreach($navCategories as $nav)
            <details class="border-b hairline">
                <summary class="py-3 flex justify-between">
                    {{ $nav->name }}

                    <i class="fa-solid fa-plus text-xs"></i>
                </summary>

                <div class="pb-3 pl-4">
                    <a
                        class="block py-2"
                        href="{{ route('categories.show', $nav) }}"
                    >
                        View all
                    </a>

                    @foreach($nav->children as $child)
                        <a
                            class="block py-2 muted"
                            href="{{ route('products.index', [$nav, $child]) }}"
                        >
                            {{ $child->name }}
                        </a>
                    @endforeach
                </div>
            </details>
        @endforeach

        <div class="grid gap-3 mt-8 text-sm">
            <a
                href="{{ auth()->check()
                    ? route('account.index')
                    : route('login') }}"
            >
                My Account
            </a>

            <a href="{{ route('pages.show', 'customer-service') }}">
                Customer Service
            </a>

            <a href="{{ route('pages.show', 'stores') }}">
                Find a Store
            </a>
        </div>
    </nav>
</div>