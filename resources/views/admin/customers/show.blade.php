@extends('admin.layouts.app')

@section('title', $customer->name)
@section('page_title', 'Customer details')

@section('content')

@php
    $initials = collect(preg_split('/\s+/', trim($customer->name)))
        ->filter()
        ->take(2)
        ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
        ->implode('');

    $statusClasses = [
        'pending' => 'border-amber-200 bg-amber-50 text-amber-700',
        'confirmed' => 'border-blue-200 bg-blue-50 text-blue-700',
        'processing' => 'border-violet-200 bg-violet-50 text-violet-700',
        'shipped' => 'border-sky-200 bg-sky-50 text-sky-700',
        'delivered' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
        'cancelled' => 'border-red-200 bg-red-50 text-red-700',
        'refunded' => 'border-slate-300 bg-slate-100 text-slate-700',
    ];

    $paymentClasses = [
        'unpaid' => 'border-red-200 bg-red-50 text-red-700',
        'pending' => 'border-amber-200 bg-amber-50 text-amber-700',
        'paid' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
        'failed' => 'border-red-200 bg-red-50 text-red-700',
        'refunded' => 'border-slate-300 bg-slate-100 text-slate-700',
    ];
@endphp

<div class="space-y-5">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <a
            href="{{ route('admin.customers.index') }}"
            class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500 transition hover:text-copper-500"
        >
            <i class="fa-solid fa-arrow-left"></i>
            Back to customers
        </a>

        <span class="text-xs text-slate-400">
            Customer #{{ $customer->id }}
        </span>
    </div>

    <div class="grid gap-5 xl:grid-cols-[320px_minmax(0,1fr)]">
        <aside class="space-y-5">
            <section class="admin-card overflow-hidden">
                <div class="h-20 bg-gradient-to-r from-[#173d32] to-[#245c4d]"></div>

                <div class="px-5 pb-5">
                    <span class="-mt-9 grid h-[72px] w-[72px] place-items-center rounded-full border-4 border-white bg-[#c4622f] text-lg font-bold tracking-wider text-white shadow-md">
                        {{ $initials ?: 'C' }}
                    </span>

                    <div class="mt-4">
                        <div class="flex flex-wrap items-center gap-2">
                            <h1 class="text-lg font-semibold text-slate-900">
                                {{ $customer->name }}
                            </h1>

                            <span class="inline-flex items-center gap-1.5 rounded-full border px-2 py-0.5 text-[9px] font-bold uppercase tracking-wide {{ $customer->is_active ? 'border-emerald-200 bg-emerald-50 text-emerald-700' : 'border-red-200 bg-red-50 text-red-700' }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ $customer->is_active ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                                {{ $customer->is_active ? 'Active' : 'Disabled' }}
                            </span>
                        </div>

                        <p class="mt-1 break-all text-xs text-slate-500">
                            {{ $customer->email }}
                        </p>
                    </div>

                    <div class="mt-5 space-y-3 border-t border-slate-100 pt-5">
                        <div class="flex items-center gap-3 text-sm text-slate-600">
                            <span class="grid h-8 w-8 shrink-0 place-items-center rounded-lg bg-slate-100 text-slate-500">
                                <i class="fa-solid fa-phone text-[11px]"></i>
                            </span>
                            <span>{{ $customer->phone ?: 'Phone not provided' }}</span>
                        </div>

                        <div class="flex items-center gap-3 text-sm text-slate-600">
                            <span class="grid h-8 w-8 shrink-0 place-items-center rounded-lg bg-slate-100 text-slate-500">
                                <i class="fa-solid fa-envelope-circle-check text-[11px]"></i>
                            </span>
                            <span>
                                {{ $customer->email_verified_at ? 'Email verified' : 'Email not verified' }}
                            </span>
                        </div>

                        <div class="flex items-center gap-3 text-sm text-slate-600">
                            <span class="grid h-8 w-8 shrink-0 place-items-center rounded-lg bg-slate-100 text-slate-500">
                                <i class="fa-solid fa-calendar-days text-[11px]"></i>
                            </span>
                            <span>Joined {{ $customer->created_at->format('d M Y') }}</span>
                        </div>
                    </div>

                    <form
                        method="POST"
                        action="{{ route('admin.customers.toggle', $customer) }}"
                        class="mt-5"
                        onsubmit="return confirm('{{ $customer->is_active ? 'Disable this customer account?' : 'Enable this customer account?' }}')"
                    >
                        @csrf
                        @method('PATCH')

                        <button
                            type="submit"
                            class="admin-btn w-full {{ $customer->is_active ? 'admin-btn-danger' : '' }}"
                        >
                            <i class="fa-solid {{ $customer->is_active ? 'fa-user-slash' : 'fa-user-check' }}"></i>
                            {{ $customer->is_active ? 'Disable account' : 'Enable account' }}
                        </button>
                    </form>
                </div>
            </section>

            <section class="admin-card overflow-hidden">
                <div class="border-b border-slate-100 px-5 py-4">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <h2 class="text-sm font-semibold text-slate-900">Saved addresses</h2>
                            <p class="mt-0.5 text-xs text-slate-500">
                                {{ $customer->addresses->count() }} saved
                            </p>
                        </div>
                        <span class="grid h-9 w-9 place-items-center rounded-lg bg-orange-50 text-copper-500">
                            <i class="fa-solid fa-location-dot text-sm"></i>
                        </span>
                    </div>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse($customer->addresses as $address)
                        <article class="p-5">
                            <div class="flex items-center gap-2">
                                <h3 class="text-xs font-semibold text-slate-900">
                                    {{ $address->label ?: 'Address' }}
                                </h3>

                                @if($address->is_default)
                                    <span class="rounded-full bg-emerald-50 px-2 py-0.5 text-[9px] font-bold uppercase text-emerald-700">
                                        Default
                                    </span>
                                @endif
                            </div>

                            <p class="mt-2 text-xs leading-5 text-slate-600">
                                {{ $address->address_line_1 }}
                                @if($address->address_line_2)
                                    <br>{{ $address->address_line_2 }}
                                @endif
                                <br>
                                {{ collect([$address->area, $address->city, $address->district])->filter()->implode(', ') }}
                                @if($address->postal_code)
                                    – {{ $address->postal_code }}
                                @endif
                            </p>
                        </article>
                    @empty
                        <div class="p-6 text-center">
                            <i class="fa-regular fa-map text-xl text-slate-300"></i>
                            <p class="mt-2 text-xs text-slate-500">No saved address</p>
                        </div>
                    @endforelse
                </div>
            </section>
        </aside>

        <main class="min-w-0 space-y-5">
            <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <article class="admin-card p-5">
                    <p class="text-xs font-medium text-slate-500">Total orders</p>
                    <div class="mt-3 flex items-center justify-between gap-3">
                        <strong class="text-2xl text-slate-900">
                            {{ number_format((int) ($orderStats->total_orders ?? 0)) }}
                        </strong>
                        <i class="fa-solid fa-bag-shopping text-copper-500"></i>
                    </div>
                </article>

                <article class="admin-card p-5">
                    <p class="text-xs font-medium text-slate-500">Paid value</p>
                    <div class="mt-3 flex items-center justify-between gap-3">
                        <strong class="text-xl text-slate-900">
                            ৳{{ number_format((float) ($orderStats->total_spent ?? 0)) }}
                        </strong>
                        <i class="fa-solid fa-sack-dollar text-emerald-600"></i>
                    </div>
                </article>

                <article class="admin-card p-5">
                    <p class="text-xs font-medium text-slate-500">Delivered</p>
                    <div class="mt-3 flex items-center justify-between gap-3">
                        <strong class="text-2xl text-slate-900">
                            {{ number_format((int) ($orderStats->delivered_orders ?? 0)) }}
                        </strong>
                        <i class="fa-solid fa-circle-check text-emerald-600"></i>
                    </div>
                </article>

                <article class="admin-card p-5">
                    <p class="text-xs font-medium text-slate-500">Open orders</p>
                    <div class="mt-3 flex items-center justify-between gap-3">
                        <strong class="text-2xl text-slate-900">
                            {{ number_format((int) ($orderStats->open_orders ?? 0)) }}
                        </strong>
                        <i class="fa-solid fa-clock text-amber-500"></i>
                    </div>
                </article>
            </section>

            <section class="admin-card overflow-hidden">
                <div class="flex flex-col gap-2 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                    <div>
                        <h2 class="text-sm font-semibold text-slate-900">Order history</h2>
                        <p class="mt-0.5 text-xs text-slate-500">
                            All orders placed by this customer.
                        </p>
                    </div>

                    @if($orders->total())
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-[10px] font-bold uppercase tracking-wide text-slate-600">
                            {{ $orders->total() }} {{ Str::plural('order', $orders->total()) }}
                        </span>
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table class="admin-table min-w-[900px]">
                        <thead>
                            <tr>
                                <th>Order</th>
                                <th>Order status</th>
                                <th>Payment</th>
                                <th>Total</th>
                                <th>Date</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td>
                                        <a
                                            href="{{ route('admin.orders.show', $order) }}"
                                            class="font-semibold text-slate-900 transition hover:text-copper-500"
                                        >
                                            {{ $order->order_number }}
                                        </a>
                                        <p class="mt-0.5 text-[11px] uppercase text-slate-400">
                                            {{ str_replace('_', ' ', $order->payment_method) }}
                                        </p>
                                    </td>

                                    <td>
                                        <span class="inline-flex rounded-full border px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide {{ $statusClasses[$order->status] ?? 'border-slate-200 bg-slate-50 text-slate-600' }}">
                                            {{ $order->status }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="inline-flex rounded-full border px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide {{ $paymentClasses[$order->payment_status] ?? 'border-slate-200 bg-slate-50 text-slate-600' }}">
                                            {{ $order->payment_status }}
                                        </span>
                                    </td>

                                    <td class="font-semibold text-slate-900">
                                        ৳{{ number_format((float) $order->total) }}
                                    </td>

                                    <td>
                                        <p class="text-sm text-slate-700">
                                            {{ $order->created_at->format('d M Y') }}
                                        </p>
                                        <p class="mt-0.5 text-[11px] text-slate-400">
                                            {{ $order->created_at->format('h:i A') }}
                                        </p>
                                    </td>

                                    <td>
                                        <div class="flex justify-end">
                                            <a
                                                href="{{ route('admin.orders.show', $order) }}"
                                                class="grid h-9 w-9 place-items-center rounded-md border border-slate-200 bg-white text-slate-600 transition hover:border-[#c4622f] hover:bg-orange-50 hover:text-[#c4622f]"
                                                aria-label="View order {{ $order->order_number }}"
                                                title="View order"
                                            >
                                                <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="!py-16 text-center">
                                        <span class="mx-auto grid h-14 w-14 place-items-center rounded-full bg-slate-100 text-slate-400">
                                            <i class="fa-solid fa-bag-shopping text-lg"></i>
                                        </span>
                                        <h3 class="mt-4 text-sm font-semibold text-slate-900">
                                            No orders yet
                                        </h3>
                                        <p class="mt-1 text-xs text-slate-500">
                                            This customer has not placed any orders.
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            @include('admin.partials.pagination', [
                'paginator' => $orders,
                'itemLabel' => 'orders',
            ])
        </main>
    </div>
</div>

@endsection
