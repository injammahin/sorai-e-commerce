@extends('admin.layouts.app')

@section('title', 'Commerce overview')
@section('page_title', 'Commerce overview')
@section('eyebrow', 'AATCHALA intelligence')

@php
    $money = fn ($amount) => '৳'.number_format((float) $amount, 0);

    $compactMoney = function ($amount) {
        $amount = (float) $amount;

        if ($amount >= 10000000) {
            return '৳'.number_format($amount / 10000000, 1).'Cr';
        }

        if ($amount >= 100000) {
            return '৳'.number_format($amount / 100000, 1).'L';
        }

        if ($amount >= 1000) {
            return '৳'.number_format($amount / 1000, 1).'K';
        }

        return '৳'.number_format($amount, 0);
    };

    $statusMeta = [
        'pending' => ['Pending', '#d97706', 'dashboard-badge-amber'],
        'confirmed' => ['Confirmed', '#2563eb', 'dashboard-badge-blue'],
        'processing' => ['Processing', '#7c3aed', 'dashboard-badge-violet'],
        'shipped' => ['Shipped', '#0891b2', 'dashboard-badge-sky'],
        'delivered' => ['Delivered', '#059669', 'dashboard-badge-green'],
        'cancelled' => ['Cancelled', '#dc2626', 'dashboard-badge-red'],
        'refunded' => ['Refunded', '#64748b', 'dashboard-badge-slate'],
    ];

    $paymentMeta = [
        'paid' => ['Paid', 'dashboard-badge-green'],
        'pending' => ['Pending', 'dashboard-badge-amber'],
        'unpaid' => ['Unpaid', 'dashboard-badge-red'],
        'failed' => ['Failed', 'dashboard-badge-red'],
        'refunded' => ['Refunded', 'dashboard-badge-slate'],
    ];

    $kpis = [
        [
            'label' => 'Total paid sales',
            'value' => $money($stats['total_revenue']),
            'note' => number_format($stats['paid_orders']).' paid orders',
            'icon' => 'fa-sack-dollar',
            'tone' => 'forest',
        ],
        [
            'label' => 'Sales this month',
            'value' => $money($stats['month_revenue']),
            'note' => $stats['month_growth'] === null
                ? 'First paid sales this month'
                : (($stats['month_growth'] >= 0 ? '+' : '').$stats['month_growth'].'% vs last month'),
            'icon' => 'fa-calendar-days',
            'tone' => 'copper',
        ],
        [
            'label' => 'Last 3 months',
            'value' => $money($stats['three_month_revenue']),
            'note' => 'Paid revenue window',
            'icon' => 'fa-chart-column',
            'tone' => 'blue',
        ],
        [
            'label' => 'Last 6 months',
            'value' => $money($stats['six_month_revenue']),
            'note' => 'Paid revenue window',
            'icon' => 'fa-chart-line',
            'tone' => 'violet',
        ],
        [
            'label' => 'Average order value',
            'value' => $money($stats['average_order_value']),
            'note' => 'Across paid orders',
            'icon' => 'fa-receipt',
            'tone' => 'gold',
        ],
        [
            'label' => 'All orders',
            'value' => number_format($stats['orders']),
            'note' => $compactMoney($stats['gross_order_value']).' gross order value',
            'icon' => 'fa-bag-shopping',
            'tone' => 'wine',
        ],
    ];

    $chartWidth = 1000;
    $chartHeight = 280;
    $chartLeft = 52;
    $chartRight = 970;
    $chartTop = 18;
    $chartBottom = 232;
    $chartPlotHeight = $chartBottom - $chartTop;
    $chartValues = $salesChart->pluck('revenue');
    $chartMax = max(1, (float) $chartValues->max());
    $chartCount = max(1, $salesChart->count());
    $chartStep = $chartCount > 1
        ? ($chartRight - $chartLeft) / ($chartCount - 1)
        : 0;

    $chartPoints = $salesChart
        ->values()
        ->map(function ($row, $index) use (
            $chartLeft,
            $chartStep,
            $chartBottom,
            $chartPlotHeight,
            $chartMax
        ) {
            return [
                'x' => round($chartLeft + ($index * $chartStep), 2),
                'y' => round(
                    $chartBottom
                    - (($row['revenue'] / $chartMax) * $chartPlotHeight),
                    2
                ),
                'row' => $row,
            ];
        });

    $linePoints = $chartPoints
        ->map(fn ($point) => $point['x'].','.$point['y'])
        ->implode(' ');

    $areaPoints = $chartLeft.','.$chartBottom
        .' '.$linePoints
        .' '.$chartRight.','.$chartBottom;

    $averageY = round(
        $chartBottom
        - (($chartSummary['monthly_average'] / $chartMax) * $chartPlotHeight),
        2
    );

    $chartGrid = collect(range(0, 4))->map(function ($step) use (
        $chartTop,
        $chartPlotHeight,
        $chartMax
    ) {
        $ratio = $step / 4;

        return [
            'y' => round($chartTop + ($chartPlotHeight * $ratio), 2),
            'value' => $chartMax * (1 - $ratio),
        ];
    });

    $orderStatusTotal = max(1, (int) $orderStatuses->sum());
    $donutCursor = 0;
    $donutStops = [];

    foreach ($orderStatuses as $status => $count) {
        if ($count < 1) {
            continue;
        }

        $percentage = ($count / $orderStatusTotal) * 100;
        $nextCursor = $donutCursor + $percentage;
        $colour = $statusMeta[$status][1];

        $donutStops[] = $colour.' '.$donutCursor.'% '.$nextCursor.'%';
        $donutCursor = $nextCursor;
    }

    $donutBackground = $donutStops
        ? 'conic-gradient('.implode(', ', $donutStops).')'
        : '#e2e8f0';
@endphp

@push('styles')
    <style>
        .dashboard-page {
            --dash-forest: #173d32;
            --dash-copper: #c4622f;
            --dash-ink: #0f172a;
            --dash-muted: #64748b;
            display: grid;
            gap: 1.25rem;
        }

        .dashboard-welcome {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 2rem;
            min-height: 164px;
            padding: clamp(1.5rem, 3vw, 2.4rem);
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, .08);
            border-radius: 1.15rem;
            background:
                radial-gradient(circle at 88% 15%, rgba(196, 98, 47, .38), transparent 26%),
                radial-gradient(circle at 58% 110%, rgba(255, 255, 255, .08), transparent 30%),
                linear-gradient(125deg, #0d1a17 0%, #173d32 64%, #214f42 100%);
            color: #fff;
            box-shadow: 0 24px 55px rgba(15, 23, 42, .12);
        }

        .dashboard-welcome::after {
            position: absolute;
            right: -55px;
            bottom: -90px;
            width: 280px;
            height: 280px;
            border: 1px solid rgba(255, 255, 255, .09);
            border-radius: 50%;
            box-shadow:
                0 0 0 36px rgba(255, 255, 255, .025),
                0 0 0 72px rgba(255, 255, 255, .018);
            content: '';
        }

        .dashboard-welcome-copy,
        .dashboard-welcome-actions {
            position: relative;
            z-index: 1;
        }

        .dashboard-welcome-kicker {
            margin: 0 0 .55rem;
            color: #dca07c;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: .2em;
            text-transform: uppercase;
        }

        .dashboard-welcome h2 {
            margin: 0;
            color: #fff;
            font-size: clamp(2rem, 3vw, 3.35rem);
            letter-spacing: -.025em;
        }

        .dashboard-welcome-copy > p:last-child {
            max-width: 630px;
            margin: .8rem 0 0;
            color: rgba(255, 255, 255, .67);
            font-size: 12px;
        }

        .dashboard-welcome-actions {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-end;
            gap: .65rem;
        }

        .dashboard-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .55rem;
            min-height: 42px;
            padding: .7rem 1rem;
            border: 1px solid rgba(255, 255, 255, .17);
            border-radius: .7rem;
            background: rgba(255, 255, 255, .08);
            color: #fff;
            font-size: 11px;
            font-weight: 600;
            backdrop-filter: blur(8px);
            transition: .25s ease;
        }

        .dashboard-action:hover {
            border-color: rgba(255, 255, 255, .35);
            background: rgba(255, 255, 255, .15);
            transform: translateY(-2px);
        }

        .dashboard-action-primary {
            border-color: #fff;
            background: #fff;
            color: #173d32;
        }

        .dashboard-action-primary:hover {
            background: #f6ece5;
            color: #a84f27;
        }

        .dashboard-kpi-grid {
            display: grid;
            grid-template-columns: repeat(6, minmax(0, 1fr));
            gap: .85rem;
        }

        .dashboard-kpi {
            position: relative;
            min-width: 0;
            padding: 1.2rem;
            overflow: hidden;
            border: 1px solid #e5e9ee;
            border-radius: .95rem;
            background: #fff;
            box-shadow: 0 8px 26px rgba(15, 23, 42, .035);
            transition: transform .3s ease, border-color .3s ease, box-shadow .3s ease;
        }

        .dashboard-kpi:hover {
            border-color: #d9c3b5;
            box-shadow: 0 18px 38px rgba(15, 23, 42, .07);
            transform: translateY(-3px);
        }

        .dashboard-kpi-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .75rem;
        }

        .dashboard-kpi-icon {
            display: grid;
            width: 38px;
            height: 38px;
            flex: 0 0 38px;
            place-items: center;
            border-radius: .75rem;
            font-size: 13px;
        }

        .dashboard-kpi-icon.forest { background: #e7f0ed; color: #173d32; }
        .dashboard-kpi-icon.copper { background: #f8eae1; color: #b55528; }
        .dashboard-kpi-icon.blue { background: #e8f0ff; color: #2563eb; }
        .dashboard-kpi-icon.violet { background: #f0eaff; color: #7c3aed; }
        .dashboard-kpi-icon.gold { background: #fff6da; color: #a16207; }
        .dashboard-kpi-icon.wine { background: #f6e9ed; color: #7b2b43; }

        .dashboard-kpi-label {
            min-width: 0;
            color: #64748b;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: .04em;
        }

        .dashboard-kpi-value {
            display: block;
            margin-top: 1rem;
            overflow: hidden;
            color: #0f172a;
            font-size: clamp(1.25rem, 1.6vw, 1.75rem);
            font-weight: 600;
            letter-spacing: -.04em;
            line-height: 1.1;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .dashboard-kpi-note {
            display: block;
            margin-top: .5rem;
            overflow: hidden;
            color: #94a3b8;
            font-size: 9px;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .dashboard-live-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: .85rem;
        }

        .dashboard-live-item {
            display: flex;
            align-items: center;
            gap: .85rem;
            min-width: 0;
            padding: 1rem 1.1rem;
            border: 1px solid #e7eaee;
            border-radius: .85rem;
            background: rgba(255, 255, 255, .72);
        }

        .dashboard-live-icon {
            display: grid;
            width: 34px;
            height: 34px;
            flex: 0 0 34px;
            place-items: center;
            border-radius: 50%;
            background: #f1f5f4;
            color: #173d32;
            font-size: 11px;
        }

        .dashboard-live-item small {
            display: block;
            color: #94a3b8;
            font-size: 9px;
            letter-spacing: .07em;
            text-transform: uppercase;
        }

        .dashboard-live-item strong {
            display: block;
            margin-top: .15rem;
            overflow: hidden;
            color: #172033;
            font-size: 14px;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .dashboard-grid-main,
        .dashboard-grid-secondary {
            display: grid;
            grid-template-columns: minmax(0, 1.72fr) minmax(300px, .78fr);
            gap: 1rem;
        }

        .dashboard-grid-secondary {
            grid-template-columns: minmax(0, 1.15fr) minmax(320px, .85fr);
        }

        .dashboard-panel {
            min-width: 0;
            overflow: hidden;
            border: 1px solid #e5e9ee;
            border-radius: 1rem;
            background: #fff;
            box-shadow: 0 8px 28px rgba(15, 23, 42, .035);
        }

        .dashboard-panel-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            padding: 1.25rem 1.35rem;
            border-bottom: 1px solid #edf0f3;
        }

        .dashboard-panel-title {
            margin: 0;
            color: #111827;
            font: 600 15px/1.3 Inter, sans-serif;
        }

        .dashboard-panel-copy {
            margin: .3rem 0 0;
            color: #94a3b8;
            font-size: 10px;
        }

        .dashboard-panel-link {
            flex: 0 0 auto;
            color: #a84f27;
            font-size: 10px;
            font-weight: 600;
        }

        .dashboard-panel-link:hover { color: #173d32; }

        .dashboard-periods {
            display: inline-flex;
            gap: .2rem;
            padding: .22rem;
            border-radius: .65rem;
            background: #f1f4f6;
        }

        .dashboard-period {
            min-width: 42px;
            padding: .4rem .55rem;
            border-radius: .48rem;
            color: #64748b;
            font-size: 9px;
            font-weight: 600;
            text-align: center;
        }

        .dashboard-period:hover,
        .dashboard-period.active {
            background: #fff;
            color: #173d32;
            box-shadow: 0 2px 8px rgba(15, 23, 42, .08);
        }

        .dashboard-chart-summary {
            display: flex;
            flex-wrap: wrap;
            gap: 1.25rem;
            padding: 1rem 1.35rem 0;
        }

        .dashboard-chart-summary small {
            display: block;
            color: #94a3b8;
            font-size: 9px;
            text-transform: uppercase;
        }

        .dashboard-chart-summary strong {
            display: block;
            margin-top: .15rem;
            color: #172033;
            font-size: 13px;
        }

        .dashboard-chart-wrap {
            padding: .5rem 1.1rem 1.2rem;
        }

        .dashboard-chart {
            display: block;
            width: 100%;
            height: auto;
            overflow: visible;
        }

        .dashboard-chart-grid { stroke: #e8edf0; stroke-width: 1; }
        .dashboard-chart-label { fill: #94a3b8; font: 9px Inter, sans-serif; }
        .dashboard-chart-area { fill: url(#salesArea); }
        .dashboard-chart-line {
            fill: none;
            stroke: #c4622f;
            stroke-linecap: round;
            stroke-linejoin: round;
            stroke-width: 4;
        }
        .dashboard-chart-average {
            stroke: #173d32;
            stroke-dasharray: 8 7;
            stroke-width: 1.5;
        }
        .dashboard-chart-point {
            fill: #fff;
            stroke: #c4622f;
            stroke-width: 3;
        }

        .dashboard-chart-months {
            display: grid;
            gap: .25rem;
            padding: 0 1.1rem 1rem 3.2rem;
            color: #94a3b8;
            font-size: 9px;
            text-align: center;
        }

        .dashboard-donut-area {
            display: grid;
            place-items: center;
            padding: 1.4rem 1.25rem 1rem;
        }

        .dashboard-donut {
            position: relative;
            display: grid;
            width: min(210px, 70vw);
            aspect-ratio: 1;
            place-items: center;
            border-radius: 50%;
        }

        .dashboard-donut::before {
            position: absolute;
            width: 68%;
            aspect-ratio: 1;
            border-radius: 50%;
            background: #fff;
            box-shadow: inset 0 0 0 1px #edf0f3;
            content: '';
        }

        .dashboard-donut-copy {
            position: relative;
            z-index: 1;
            text-align: center;
        }

        .dashboard-donut-copy strong {
            display: block;
            color: #111827;
            font-size: 28px;
            line-height: 1;
        }

        .dashboard-donut-copy small {
            display: block;
            margin-top: .4rem;
            color: #94a3b8;
            font-size: 9px;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .dashboard-status-list {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .65rem .9rem;
            padding: .5rem 1.35rem 1.35rem;
        }

        .dashboard-status-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .5rem;
            color: #64748b;
            font-size: 10px;
        }

        .dashboard-status-name {
            display: flex;
            align-items: center;
            min-width: 0;
            gap: .5rem;
        }

        .dashboard-status-dot {
            width: 7px;
            height: 7px;
            flex: 0 0 7px;
            border-radius: 50%;
        }

        .dashboard-status-row strong { color: #172033; }

        .dashboard-inventory-overview {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: .7rem;
            padding: 1.2rem 1.35rem;
        }

        .dashboard-inventory-stat {
            padding: .9rem;
            border-radius: .75rem;
            background: #f8fafb;
        }

        .dashboard-inventory-stat small {
            display: block;
            color: #94a3b8;
            font-size: 9px;
            text-transform: uppercase;
        }

        .dashboard-inventory-stat strong {
            display: block;
            margin-top: .25rem;
            color: #172033;
            font-size: 18px;
        }

        .dashboard-stock-meter {
            display: flex;
            height: 9px;
            margin: 0 1.35rem 1.2rem;
            overflow: hidden;
            border-radius: 999px;
            background: #eef2f4;
        }

        .dashboard-stock-meter span { min-width: 0; }
        .dashboard-stock-meter .out { background: #dc2626; }
        .dashboard-stock-meter .low { background: #d97706; }
        .dashboard-stock-meter .healthy { background: #059669; }

        .dashboard-product-list {
            display: grid;
            padding: 0 1.35rem 1.35rem;
        }

        .dashboard-product-row {
            display: grid;
            grid-template-columns: 44px minmax(0, 1fr) auto;
            align-items: center;
            gap: .75rem;
            padding: .72rem 0;
            border-top: 1px solid #eef1f3;
        }

        .dashboard-product-image {
            width: 44px;
            height: 52px;
            overflow: hidden;
            border-radius: .5rem;
            background: #f1ede8;
        }

        .dashboard-product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .dashboard-product-name {
            display: block;
            overflow: hidden;
            color: #172033;
            font-size: 11px;
            font-weight: 600;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .dashboard-product-meta {
            display: block;
            margin-top: .2rem;
            color: #94a3b8;
            font-size: 9px;
        }

        .dashboard-product-value {
            text-align: right;
        }

        .dashboard-product-value strong {
            display: block;
            color: #172033;
            font-size: 11px;
        }

        .dashboard-product-value small {
            color: #94a3b8;
            font-size: 9px;
        }

        .dashboard-stock-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            min-height: 26px;
            padding: .25rem .45rem;
            border-radius: 999px;
            background: #fff1f1;
            color: #b91c1c;
            font-size: 10px;
            font-weight: 700;
        }

        .dashboard-table-wrap { overflow-x: auto; }

        .dashboard-table {
            width: 100%;
            min-width: 820px;
            border-collapse: collapse;
        }

        .dashboard-table th {
            padding: .72rem 1rem;
            background: #f8fafb;
            color: #94a3b8;
            font-size: 9px;
            font-weight: 600;
            letter-spacing: .1em;
            text-align: left;
            text-transform: uppercase;
        }

        .dashboard-table td {
            padding: .85rem 1rem;
            border-top: 1px solid #edf0f3;
            color: #475569;
            font-size: 10px;
            vertical-align: middle;
        }

        .dashboard-order-link {
            color: #172033;
            font-weight: 700;
        }

        .dashboard-order-link:hover { color: #c4622f; }

        .dashboard-badge {
            display: inline-flex;
            align-items: center;
            padding: .28rem .5rem;
            border: 1px solid transparent;
            border-radius: 999px;
            font-size: 8px;
            font-weight: 700;
            letter-spacing: .04em;
            text-transform: uppercase;
        }

        .dashboard-badge-amber { border-color: #fde2a8; background: #fff8e7; color: #a16207; }
        .dashboard-badge-blue { border-color: #cfe0ff; background: #eff5ff; color: #1d4ed8; }
        .dashboard-badge-violet { border-color: #ddd0ff; background: #f5f1ff; color: #6d28d9; }
        .dashboard-badge-sky { border-color: #bae8f5; background: #ecfbff; color: #0e7490; }
        .dashboard-badge-green { border-color: #bdebd7; background: #ecfdf5; color: #047857; }
        .dashboard-badge-red { border-color: #fecaca; background: #fff1f2; color: #b91c1c; }
        .dashboard-badge-slate { border-color: #d9e0e7; background: #f4f6f8; color: #475569; }

        .dashboard-empty {
            display: grid;
            min-height: 180px;
            place-items: center;
            padding: 2rem;
            color: #94a3b8;
            font-size: 11px;
            text-align: center;
        }

        .dashboard-empty i {
            display: block;
            margin-bottom: .7rem;
            color: #cbd5e1;
            font-size: 25px;
        }

        @media (max-width: 1500px) {
            .dashboard-kpi-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        }

        @media (max-width: 1120px) {
            .dashboard-grid-main,
            .dashboard-grid-secondary { grid-template-columns: 1fr; }
            .dashboard-live-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }

        @media (max-width: 760px) {
            .dashboard-welcome {
                align-items: flex-start;
                flex-direction: column;
            }
            .dashboard-welcome-actions { justify-content: flex-start; }
            .dashboard-kpi-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .dashboard-inventory-overview { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .dashboard-panel-header { flex-direction: column; }
        }

        @media (max-width: 520px) {
            .dashboard-kpi-grid,
            .dashboard-live-grid { grid-template-columns: 1fr; }
            .dashboard-welcome-actions,
            .dashboard-action { width: 100%; }
            .dashboard-chart-summary { gap: .8rem 1rem; }
            .dashboard-status-list { grid-template-columns: 1fr; }
            .dashboard-chart-label { display: none; }
            .dashboard-chart-months { padding-left: 1rem; }
        }
    </style>
@endpush

@section('content')
    <div class="dashboard-page">
        <section class="dashboard-welcome">
            <div class="dashboard-welcome-copy">
                <p class="dashboard-welcome-kicker">
                    {{ now()->format('l, d F Y') }}
                </p>

                <h2>Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}, {{ auth()->user()->name }}.</h2>

                <p>
                    A live commercial overview of paid sales, orders,
                    customers and inventory health across AATCHALA.
                </p>
            </div>

            <div class="dashboard-welcome-actions">
                <a
                    class="dashboard-action dashboard-action-primary"
                    href="{{ route('admin.products.create') }}"
                >
                    <i class="fa-solid fa-plus"></i>
                    Add product
                </a>

                <a
                    class="dashboard-action"
                    href="{{ route('admin.orders.index') }}"
                >
                    <i class="fa-solid fa-receipt"></i>
                    Manage orders
                </a>

                <a
                    class="dashboard-action"
                    href="{{ route('home') }}"
                    target="_blank"
                    rel="noopener"
                >
                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    View store
                </a>
            </div>
        </section>

        <section class="dashboard-kpi-grid" aria-label="Sales overview">
            @foreach ($kpis as $kpi)
                <article class="dashboard-kpi">
                    <div class="dashboard-kpi-top">
                        <span class="dashboard-kpi-label">
                            {{ $kpi['label'] }}
                        </span>

                        <span class="dashboard-kpi-icon {{ $kpi['tone'] }}">
                            <i class="fa-solid {{ $kpi['icon'] }}"></i>
                        </span>
                    </div>

                    <strong class="dashboard-kpi-value">
                        {{ $kpi['value'] }}
                    </strong>

                    <small class="dashboard-kpi-note">
                        {{ $kpi['note'] }}
                    </small>
                </article>
            @endforeach
        </section>

        <section class="dashboard-live-grid" aria-label="Operational overview">
            <a
                class="dashboard-live-item"
                href="{{ route('admin.orders.index') }}"
            >
                <span class="dashboard-live-icon">
                    <i class="fa-solid fa-bolt"></i>
                </span>

                <span>
                    <small>Today</small>
                    <strong>
                        {{ $money($operations['today_revenue']) }} ·
                        {{ number_format($operations['today_orders']) }} orders
                    </strong>
                </span>
            </a>

            <a
                class="dashboard-live-item"
                href="{{ route('admin.orders.index') }}"
            >
                <span class="dashboard-live-icon">
                    <i class="fa-solid fa-box-open"></i>
                </span>

                <span>
                    <small>Need fulfilment</small>
                    <strong>{{ number_format($operations['orders_to_fulfil']) }} orders</strong>
                </span>
            </a>

            <a
                class="dashboard-live-item"
                href="{{ route('admin.customers.index') }}"
            >
                <span class="dashboard-live-icon">
                    <i class="fa-solid fa-user-plus"></i>
                </span>

                <span>
                    <small>Customers</small>
                    <strong>
                        {{ number_format($operations['customers']) }} total ·
                        {{ number_format($operations['new_customers']) }} new
                    </strong>
                </span>
            </a>

            <a
                class="dashboard-live-item"
                href="{{ route('admin.messages.index') }}"
            >
                <span class="dashboard-live-icon">
                    <i class="fa-solid fa-inbox"></i>
                </span>

                <span>
                    <small>Attention</small>
                    <strong>
                        {{ number_format($operations['new_messages']) }} messages ·
                        {{ number_format($operations['pending_reviews']) }} reviews
                    </strong>
                </span>
            </a>
        </section>

        <div class="dashboard-grid-main">
            <section class="dashboard-panel">
                <header class="dashboard-panel-header">
                    <div>
                        <h3 class="dashboard-panel-title">Sales performance</h3>
                        <p class="dashboard-panel-copy">
                            Paid revenue using the payment date. Empty months remain visible.
                        </p>
                    </div>

                    <nav class="dashboard-periods" aria-label="Sales chart period">
                        @foreach ([3, 6, 12] as $months)
                            <a
                                class="dashboard-period {{ $period === $months ? 'active' : '' }}"
                                href="{{ route('admin.dashboard', ['period' => $months]) }}"
                                @if ($period === $months) aria-current="page" @endif
                            >
                                {{ $months }}M
                            </a>
                        @endforeach
                    </nav>
                </header>

                <div class="dashboard-chart-summary">
                    <div>
                        <small>Period revenue</small>
                        <strong>{{ $money($chartSummary['revenue']) }}</strong>
                    </div>

                    <div>
                        <small>Monthly average</small>
                        <strong>{{ $money($chartSummary['monthly_average']) }}</strong>
                    </div>

                    <div>
                        <small>Paid orders</small>
                        <strong>{{ number_format($chartSummary['orders']) }}</strong>
                    </div>

                    <div>
                        <small>Best month</small>
                        <strong>
                            {{ $chartSummary['best_month']['full_label'] ?? 'No sales yet' }}
                        </strong>
                    </div>
                </div>

                <div class="dashboard-chart-wrap">
                    <svg
                        class="dashboard-chart"
                        viewBox="0 0 {{ $chartWidth }} {{ $chartHeight }}"
                        role="img"
                        aria-labelledby="sales-chart-title sales-chart-description"
                    >
                        <title id="sales-chart-title">Paid sales for the last {{ $period }} months</title>
                        <desc id="sales-chart-description">
                            Revenue line chart with a monthly average reference line.
                        </desc>

                        <defs>
                            <linearGradient id="salesArea" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="#c4622f" stop-opacity=".28" />
                                <stop offset="100%" stop-color="#c4622f" stop-opacity=".015" />
                            </linearGradient>
                        </defs>

                        @foreach ($chartGrid as $grid)
                            <line
                                class="dashboard-chart-grid"
                                x1="{{ $chartLeft }}"
                                y1="{{ $grid['y'] }}"
                                x2="{{ $chartRight }}"
                                y2="{{ $grid['y'] }}"
                            />

                            <text
                                class="dashboard-chart-label"
                                x="{{ $chartLeft - 10 }}"
                                y="{{ $grid['y'] + 3 }}"
                                text-anchor="end"
                            >{{ $compactMoney($grid['value']) }}</text>
                        @endforeach

                        <polygon
                            class="dashboard-chart-area"
                            points="{{ $areaPoints }}"
                        />

                        <line
                            class="dashboard-chart-average"
                            x1="{{ $chartLeft }}"
                            y1="{{ $averageY }}"
                            x2="{{ $chartRight }}"
                            y2="{{ $averageY }}"
                        >
                            <title>Average {{ $money($chartSummary['monthly_average']) }}</title>
                        </line>

                        <polyline
                            class="dashboard-chart-line"
                            points="{{ $linePoints }}"
                        />

                        @foreach ($chartPoints as $point)
                            <circle
                                class="dashboard-chart-point"
                                cx="{{ $point['x'] }}"
                                cy="{{ $point['y'] }}"
                                r="5"
                            >
                                <title>
                                    {{ $point['row']['full_label'] }}:
                                    {{ $money($point['row']['revenue']) }},
                                    {{ number_format($point['row']['orders']) }} paid orders
                                </title>
                            </circle>
                        @endforeach
                    </svg>

                    <div
                        class="dashboard-chart-months"
                        style="grid-template-columns: repeat({{ $salesChart->count() }}, minmax(0, 1fr));"
                        aria-hidden="true"
                    >
                        @foreach ($salesChart as $row)
                            <span>{{ $row['label'] }}</span>
                        @endforeach
                    </div>
                </div>
            </section>

            <section class="dashboard-panel">
                <header class="dashboard-panel-header">
                    <div>
                        <h3 class="dashboard-panel-title">Order status</h3>
                        <p class="dashboard-panel-copy">Lifetime order distribution.</p>
                    </div>

                    <a
                        class="dashboard-panel-link"
                        href="{{ route('admin.orders.index') }}"
                    >
                        View orders
                    </a>
                </header>

                <div class="dashboard-donut-area">
                    <div
                        class="dashboard-donut"
                        style="background: {{ $donutBackground }};"
                        role="img"
                        aria-label="Order status distribution"
                    >
                        <div class="dashboard-donut-copy">
                            <strong>{{ number_format($orderStatuses->sum()) }}</strong>
                            <small>Total orders</small>
                        </div>
                    </div>
                </div>

                <div class="dashboard-status-list">
                    @foreach ($orderStatuses as $status => $count)
                        <a
                            class="dashboard-status-row"
                            href="{{ route('admin.orders.index', ['status' => $status]) }}"
                        >
                            <span class="dashboard-status-name">
                                <i
                                    class="dashboard-status-dot"
                                    style="background: {{ $statusMeta[$status][1] }};"
                                ></i>

                                {{ $statusMeta[$status][0] }}
                            </span>

                            <strong>{{ number_format($count) }}</strong>
                        </a>
                    @endforeach
                </div>
            </section>
        </div>

        <div class="dashboard-grid-secondary">
            <section class="dashboard-panel">
                <header class="dashboard-panel-header">
                    <div>
                        <h3 class="dashboard-panel-title">Inventory health</h3>
                        <p class="dashboard-panel-copy">
                            Active product stock and retail value overview.
                        </p>
                    </div>

                    <a
                        class="dashboard-panel-link"
                        href="{{ route('admin.products.index') }}"
                    >
                        Manage inventory
                    </a>
                </header>

                <div class="dashboard-inventory-overview">
                    <div class="dashboard-inventory-stat">
                        <small>Active products</small>
                        <strong>{{ number_format($inventory['total_products']) }}</strong>
                    </div>

                    <div class="dashboard-inventory-stat">
                        <small>Stock units</small>
                        <strong>{{ number_format($inventory['stock_units']) }}</strong>
                    </div>

                    <div class="dashboard-inventory-stat">
                        <small>Healthy stock</small>
                        <strong>{{ number_format($inventory['healthy_stock']) }}</strong>
                    </div>

                    <div class="dashboard-inventory-stat">
                        <small>Retail value</small>
                        <strong>{{ $compactMoney($inventory['retail_stock_value']) }}</strong>
                    </div>
                </div>

                @php
                    $inventoryTotal = max(1, $inventory['total_products']);
                @endphp

                <div
                    class="dashboard-stock-meter"
                    aria-label="Inventory status: {{ $inventory['out_of_stock'] }} out of stock, {{ $inventory['low_stock'] }} low stock and {{ $inventory['healthy_stock'] }} healthy"
                >
                    <span
                        class="out"
                        style="width: {{ ($inventory['out_of_stock'] / $inventoryTotal) * 100 }}%;"
                    ></span>

                    <span
                        class="low"
                        style="width: {{ ($inventory['low_stock'] / $inventoryTotal) * 100 }}%;"
                    ></span>

                    <span
                        class="healthy"
                        style="width: {{ ($inventory['healthy_stock'] / $inventoryTotal) * 100 }}%;"
                    ></span>
                </div>

                <div class="dashboard-status-list">
                    <a
                        class="dashboard-status-row"
                        href="{{ route('admin.products.index', ['stock' => 'out_of_stock']) }}"
                    >
                        <span class="dashboard-status-name">
                            <i class="dashboard-status-dot" style="background:#dc2626"></i>
                            Out of stock
                        </span>
                        <strong>{{ number_format($inventory['out_of_stock']) }}</strong>
                    </a>

                    <a
                        class="dashboard-status-row"
                        href="{{ route('admin.products.index', ['stock' => 'low_stock']) }}"
                    >
                        <span class="dashboard-status-name">
                            <i class="dashboard-status-dot" style="background:#d97706"></i>
                            Low stock
                        </span>
                        <strong>{{ number_format($inventory['low_stock']) }}</strong>
                    </a>

                    <div class="dashboard-status-row">
                        <span class="dashboard-status-name">
                            <i class="dashboard-status-dot" style="background:#059669"></i>
                            Healthy stock
                        </span>
                        <strong>{{ number_format($inventory['healthy_stock']) }}</strong>
                    </div>
                </div>
            </section>

            <section class="dashboard-panel">
                <header class="dashboard-panel-header">
                    <div>
                        <h3 class="dashboard-panel-title">Top-selling products</h3>
                        <p class="dashboard-panel-copy">Ranked by paid revenue.</p>
                    </div>
                </header>

                <div class="dashboard-product-list">
                    @forelse ($topProducts as $index => $item)
                        <div class="dashboard-product-row">
                            <div class="dashboard-product-image">
                                <img
                                    src="{{ $item->image ? asset($item->image) : asset('images/placeholder.webp') }}"
                                    alt=""
                                    loading="lazy"
                                >
                            </div>

                            <div class="min-w-0">
                                <span class="dashboard-product-name">
                                    {{ $index + 1 }}. {{ $item->product_name }}
                                </span>

                                <small class="dashboard-product-meta">
                                    {{ $item->sku }} · {{ number_format($item->units_sold) }} sold
                                </small>
                            </div>

                            <div class="dashboard-product-value">
                                <strong>{{ $money($item->revenue) }}</strong>
                                <small>revenue</small>
                            </div>
                        </div>
                    @empty
                        <div class="dashboard-empty">
                            <div>
                                <i class="fa-solid fa-chart-simple"></i>
                                Top products will appear after the first paid order.
                            </div>
                        </div>
                    @endforelse
                </div>
            </section>
        </div>

        <div class="dashboard-grid-main">
            <section class="dashboard-panel">
                <header class="dashboard-panel-header">
                    <div>
                        <h3 class="dashboard-panel-title">Recent orders</h3>
                        <p class="dashboard-panel-copy">The latest activity across every payment method.</p>
                    </div>

                    <a
                        class="dashboard-panel-link"
                        href="{{ route('admin.orders.index') }}"
                    >
                        View all orders
                    </a>
                </header>

                <div class="dashboard-table-wrap">
                    <table class="dashboard-table">
                        <thead>
                            <tr>
                                <th>Order</th>
                                <th>Customer</th>
                                <th>Order status</th>
                                <th>Payment</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Date</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($recentOrders as $order)
                                @php
                                    $orderStatus = $statusMeta[$order->status]
                                        ?? [ucfirst($order->status), '#64748b', 'dashboard-badge-slate'];

                                    $paymentStatus = $paymentMeta[$order->payment_status]
                                        ?? [ucfirst($order->payment_status), 'dashboard-badge-slate'];
                                @endphp

                                <tr>
                                    <td>
                                        <a
                                            class="dashboard-order-link"
                                            href="{{ route('admin.orders.show', $order) }}"
                                        >
                                            {{ $order->order_number }}
                                        </a>
                                    </td>

                                    <td>
                                        {{ $order->user?->name ?: data_get($order->shipping_address, 'name', 'Guest') }}
                                    </td>

                                    <td>
                                        <span class="dashboard-badge {{ $orderStatus[2] }}">
                                            {{ $orderStatus[0] }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="dashboard-badge {{ $paymentStatus[1] }}">
                                            {{ $paymentStatus[0] }}
                                        </span>
                                    </td>

                                    <td>{{ number_format($order->items_count) }}</td>
                                    <td><strong>{{ $money($order->total) }}</strong></td>
                                    <td>{{ $order->created_at->format('d M Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">
                                        <div class="dashboard-empty">
                                            <div>
                                                <i class="fa-solid fa-receipt"></i>
                                                No orders have been placed yet.
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="dashboard-panel">
                <header class="dashboard-panel-header">
                    <div>
                        <h3 class="dashboard-panel-title">Stock attention</h3>
                        <p class="dashboard-panel-copy">Out-of-stock items appear first.</p>
                    </div>

                    <a
                        class="dashboard-panel-link"
                        href="{{ route('admin.products.index', ['stock' => 'low_stock']) }}"
                    >
                        View stock
                    </a>
                </header>

                <div class="dashboard-product-list">
                    @forelse ($lowStockProducts as $product)
                        <a
                            class="dashboard-product-row"
                            href="{{ route('admin.products.edit', $product) }}"
                        >
                            <div class="dashboard-product-image">
                                <img
                                    src="{{ asset($product->primaryImage->path) }}"
                                    alt="{{ $product->name }}"
                                    loading="lazy"
                                >
                            </div>

                            <div class="min-w-0">
                                <span class="dashboard-product-name">
                                    {{ $product->name }}
                                </span>

                                <small class="dashboard-product-meta">
                                    {{ $product->sku }} · Alert at {{ number_format($product->low_stock_threshold) }}
                                </small>
                            </div>

                            <span class="dashboard-stock-number">
                                {{ number_format($product->stock) }}
                            </span>
                        </a>
                    @empty
                        <div class="dashboard-empty">
                            <div>
                                <i class="fa-solid fa-circle-check"></i>
                                Every active product is above its stock threshold.
                            </div>
                        </div>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
@endsection
