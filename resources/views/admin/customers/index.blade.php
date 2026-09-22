@extends('admin.layouts.app')

@section('title', 'Customers')
@section('page_title', 'Customers')

@section('content')

@php
    $hasFilters =
        request()->filled('q')
        ||
        request()->filled('status')
        ||
        request()->filled('sort');

    $searchClearParams = request()->except(['q', 'page']);
@endphp

<div class="space-y-5">

    {{-- Summary cards --}}
    <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <article class="admin-card p-5">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs font-medium text-slate-500">Total customers</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900">
                        {{ number_format((int) ($stats->total_customers ?? 0)) }}
                    </p>
                </div>
                <span class="grid h-11 w-11 place-items-center rounded-xl bg-orange-50 text-copper-500">
                    <i class="fa-solid fa-users"></i>
                </span>
            </div>
        </article>

        <article class="admin-card p-5">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs font-medium text-slate-500">Active customers</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900">
                        {{ number_format((int) ($stats->active_customers ?? 0)) }}
                    </p>
                </div>
                <span class="grid h-11 w-11 place-items-center rounded-xl bg-emerald-50 text-emerald-600">
                    <i class="fa-solid fa-user-check"></i>
                </span>
            </div>
        </article>

        <article class="admin-card p-5">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs font-medium text-slate-500">Disabled customers</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900">
                        {{ number_format((int) ($stats->disabled_customers ?? 0)) }}
                    </p>
                </div>
                <span class="grid h-11 w-11 place-items-center rounded-xl bg-red-50 text-red-600">
                    <i class="fa-solid fa-user-slash"></i>
                </span>
            </div>
        </article>

        <article class="admin-card p-5">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs font-medium text-slate-500">New this month</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900">
                        {{ number_format((int) ($stats->new_this_month ?? 0)) }}
                    </p>
                </div>
                <span class="grid h-11 w-11 place-items-center rounded-xl bg-blue-50 text-blue-600">
                    <i class="fa-solid fa-user-plus"></i>
                </span>
            </div>
        </article>
    </section>

    {{-- Filter card --}}
    <section class="admin-card overflow-visible">
        <div class="flex flex-col gap-4 border-b border-slate-100 px-5 py-4 sm:px-6 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex items-center gap-3">
                <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-orange-50 text-copper-500">
                    <i class="fa-solid fa-address-book"></i>
                </span>

                <div>
                    <h2 class="text-sm font-semibold text-slate-900">
                        Customer management
                    </h2>
                    <p class="mt-0.5 text-xs text-slate-500">
                        Search accounts, review activity and control customer access.
                    </p>
                </div>
            </div>

            @if($hasFilters)
                <a
                    href="{{ route('admin.customers.index') }}"
                    class="inline-flex items-center gap-2 text-xs font-semibold text-red-600 transition hover:text-red-700"
                >
                    <i class="fa-solid fa-filter-circle-xmark"></i>
                    Clear all filters
                </a>
            @endif
        </div>

        <form
            method="GET"
            action="{{ route('admin.customers.index') }}"
            class="p-5 sm:p-6"
        >
            <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-[minmax(320px,1.5fr)_minmax(190px,.75fr)_minmax(210px,.8fr)_auto]">
                <label class="md:col-span-2 xl:col-span-1">
                    <span class="admin-label">Search</span>

                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-0 grid w-10 place-items-center text-slate-400">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        </span>

                        <input
                            class="admin-input !pl-10 {{ request()->filled('q') ? '!pr-10' : '' }}"
                            type="search"
                            name="q"
                            value="{{ request('q') }}"
                            placeholder="Name, email or phone"
                            autocomplete="off"
                        >

                        @if(request()->filled('q'))
                            <a
                                href="{{ route('admin.customers.index', $searchClearParams) }}"
                                class="absolute inset-y-0 right-0 grid w-10 place-items-center text-slate-400 transition hover:text-red-600"
                                aria-label="Clear search"
                                title="Clear search"
                            >
                                <i class="fa-solid fa-circle-xmark text-sm"></i>
                            </a>
                        @endif
                    </div>
                </label>

                <label>
                    <span class="admin-label">Status</span>
                    <select class="admin-input" name="status">
                        <option value="">All statuses</option>
                        <option value="active" @selected(request('status') === 'active')>
                            Active
                        </option>
                        <option value="disabled" @selected(request('status') === 'disabled')>
                            Disabled
                        </option>
                    </select>
                </label>

                <label>
                    <span class="admin-label">Sort by</span>
                    <select class="admin-input" name="sort">
                        <option value="newest" @selected(request('sort', 'newest') === 'newest')>
                            Newest first
                        </option>
                        <option value="oldest" @selected(request('sort') === 'oldest')>
                            Oldest first
                        </option>
                        <option value="name_asc" @selected(request('sort') === 'name_asc')>
                            Name A–Z
                        </option>
                        <option value="name_desc" @selected(request('sort') === 'name_desc')>
                            Name Z–A
                        </option>
                        <option value="orders_desc" @selected(request('sort') === 'orders_desc')>
                            Most orders
                        </option>
                    </select>
                </label>

                <div class="flex items-end gap-2">
                    <button class="admin-btn h-[42px] flex-1 xl:flex-none" type="submit">
                        <i class="fa-solid fa-filter"></i>
                        Apply
                    </button>

                    <a
                        href="{{ route('admin.customers.index') }}"
                        class="grid h-[42px] w-[42px] shrink-0 place-items-center rounded-md border transition {{ $hasFilters ? 'border-red-200 bg-red-50 text-red-600 hover:bg-red-100' : 'pointer-events-none border-slate-200 bg-slate-50 text-slate-300' }}"
                        aria-label="Reset filters"
                        title="Reset filters"
                    >
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                </div>
            </div>
        </form>
    </section>

    {{-- Catalogue heading --}}
    <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h2 class="text-sm font-semibold text-slate-900">
                Customer directory
            </h2>

            <p class="mt-0.5 text-xs text-slate-500">
                @if($customers->total())
                    Showing {{ $customers->firstItem() }}–{{ $customers->lastItem() }}
                    of {{ $customers->total() }} customers
                @else
                    No customers found
                @endif
            </p>
        </div>

        @if($customers->hasPages())
            <div class="flex items-center gap-2 text-xs text-slate-500">
                <span>Page {{ $customers->currentPage() }} of {{ $customers->lastPage() }}</span>

                @if($customers->onFirstPage())
                    <span class="grid h-8 w-8 cursor-not-allowed place-items-center rounded-md border border-slate-200 bg-slate-50 text-slate-300">
                        <i class="fa-solid fa-chevron-left text-[9px]"></i>
                    </span>
                @else
                    <a
                        href="{{ $customers->previousPageUrl() }}"
                        class="grid h-8 w-8 place-items-center rounded-md border border-slate-200 bg-white text-slate-600 transition hover:border-[#c4622f] hover:text-[#c4622f]"
                        aria-label="Previous page"
                    >
                        <i class="fa-solid fa-chevron-left text-[9px]"></i>
                    </a>
                @endif

                @if($customers->hasMorePages())
                    <a
                        href="{{ $customers->nextPageUrl() }}"
                        class="grid h-8 w-8 place-items-center rounded-md border border-slate-200 bg-white text-slate-600 transition hover:border-[#c4622f] hover:text-[#c4622f]"
                        aria-label="Next page"
                    >
                        <i class="fa-solid fa-chevron-right text-[9px]"></i>
                    </a>
                @else
                    <span class="grid h-8 w-8 cursor-not-allowed place-items-center rounded-md border border-slate-200 bg-slate-50 text-slate-300">
                        <i class="fa-solid fa-chevron-right text-[9px]"></i>
                    </span>
                @endif
            </div>
        @endif
    </div>

    {{-- Customer table --}}
    <section class="admin-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="admin-table min-w-[1050px]">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Contact</th>
                        <th>Orders</th>
                        <th>Joined</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($customers as $customer)
                        @php
                            $initials = collect(preg_split('/\s+/', trim($customer->name)))
                                ->filter()
                                ->take(2)
                                ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
                                ->implode('');
                        @endphp

                        <tr>
                            <td>
                                <div class="flex min-w-[260px] items-center gap-3">
                                    <span class="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-[#173d32] text-xs font-bold tracking-wider text-white shadow-sm">
                                        {{ $initials ?: 'C' }}
                                    </span>

                                    <div class="min-w-0">
                                        <a
                                            href="{{ route('admin.customers.show', $customer) }}"
                                            class="block truncate text-sm font-semibold text-slate-900 transition hover:text-copper-500"
                                        >
                                            {{ $customer->name }}
                                        </a>
                                        <p class="mt-0.5 truncate text-xs text-slate-500">
                                            {{ $customer->email }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <td>
                                @if($customer->phone)
                                    <a
                                        href="tel:{{ $customer->phone }}"
                                        class="inline-flex items-center gap-2 text-sm text-slate-700 transition hover:text-copper-500"
                                    >
                                        <i class="fa-solid fa-phone text-[10px] text-slate-400"></i>
                                        {{ $customer->phone }}
                                    </a>
                                @else
                                    <span class="text-sm text-slate-400">Not provided</span>
                                @endif
                            </td>

                            <td>
                                <span class="inline-flex min-w-[44px] items-center justify-center rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-semibold text-slate-700">
                                    {{ number_format($customer->orders_count) }}
                                </span>
                            </td>

                            <td>
                                <p class="text-sm font-medium text-slate-700">
                                    {{ $customer->created_at->format('d M Y') }}
                                </p>
                                <p class="mt-0.5 text-[11px] text-slate-400">
                                    {{ $customer->created_at->diffForHumans() }}
                                </p>
                            </td>

                            <td>
                                <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide {{ $customer->is_active ? 'border-emerald-200 bg-emerald-50 text-emerald-700' : 'border-red-200 bg-red-50 text-red-700' }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $customer->is_active ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                                    {{ $customer->is_active ? 'Active' : 'Disabled' }}
                                </span>
                            </td>

                            <td>
                                <div class="flex items-center justify-end gap-2">
                                    <a
                                        href="{{ route('admin.customers.show', $customer) }}"
                                        class="grid h-9 w-9 place-items-center rounded-md border border-slate-200 bg-white text-slate-600 transition hover:border-[#c4622f] hover:bg-orange-50 hover:text-[#c4622f]"
                                        title="View customer"
                                        aria-label="View {{ $customer->name }}"
                                    >
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </a>

                                    <form
                                        method="POST"
                                        action="{{ route('admin.customers.toggle', $customer) }}"
                                        onsubmit="return confirm('{{ $customer->is_active ? 'Disable this customer account?' : 'Enable this customer account?' }}')"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="grid h-9 w-9 place-items-center rounded-md border transition {{ $customer->is_active ? 'border-red-200 bg-red-50 text-red-600 hover:bg-red-100' : 'border-emerald-200 bg-emerald-50 text-emerald-600 hover:bg-emerald-100' }}"
                                            title="{{ $customer->is_active ? 'Disable customer' : 'Enable customer' }}"
                                            aria-label="{{ $customer->is_active ? 'Disable' : 'Enable' }} {{ $customer->name }}"
                                        >
                                            <i class="fa-solid {{ $customer->is_active ? 'fa-user-slash' : 'fa-user-check' }} text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="!py-16 text-center">
                                <span class="mx-auto grid h-14 w-14 place-items-center rounded-full bg-slate-100 text-slate-400">
                                    <i class="fa-solid fa-users-slash text-lg"></i>
                                </span>

                                <h3 class="mt-4 text-sm font-semibold text-slate-900">
                                    No customers found
                                </h3>

                                <p class="mx-auto mt-1 max-w-md text-xs text-slate-500">
                                    {{ $hasFilters ? 'No customer matches the selected filters. Try changing or clearing them.' : 'Customer accounts will appear here after registration.' }}
                                </p>

                                @if($hasFilters)
                                    <a
                                        href="{{ route('admin.customers.index') }}"
                                        class="admin-btn mt-5"
                                    >
                                        <i class="fa-solid fa-rotate-left"></i>
                                        Reset filters
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    @include('admin.partials.pagination', [
        'paginator' => $customers,
        'itemLabel' => 'customers',
    ])

</div>

@endsection
