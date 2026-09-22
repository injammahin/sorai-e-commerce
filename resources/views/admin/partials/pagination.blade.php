@php
    $itemLabel = $itemLabel ?? 'items';
@endphp

@if($paginator->hasPages())
    @php
        $currentPage = $paginator->currentPage();
        $lastPage = $paginator->lastPage();
        $startPage = max(1, $currentPage - 2);
        $endPage = min($lastPage, $currentPage + 2);
    @endphp

    <div
        class="flex flex-col gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3 shadow-sm sm:flex-row sm:items-center sm:justify-between"
    >
        <p class="text-xs text-slate-500">
            Showing
            <span class="font-semibold text-slate-800">
                {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }}
            </span>
            of
            <span class="font-semibold text-slate-800">
                {{ $paginator->total() }}
            </span>
            {{ $itemLabel }}
        </p>

        <nav
            class="flex flex-wrap items-center gap-1"
            aria-label="{{ ucfirst($itemLabel) }} pagination"
        >
            @if($paginator->onFirstPage())
                <span
                    class="inline-flex h-9 cursor-not-allowed items-center gap-2 rounded-md border border-slate-200 bg-slate-50 px-3 text-xs font-semibold text-slate-300"
                    aria-disabled="true"
                >
                    <i class="fa-solid fa-chevron-left text-[9px]"></i>
                    <span class="hidden sm:inline">Previous</span>
                </span>
            @else
                <a
                    href="{{ $paginator->previousPageUrl() }}"
                    class="inline-flex h-9 items-center gap-2 rounded-md border border-slate-200 bg-white px-3 text-xs font-semibold text-slate-600 transition hover:border-[#c4622f] hover:bg-orange-50 hover:text-[#c4622f]"
                    rel="prev"
                >
                    <i class="fa-solid fa-chevron-left text-[9px]"></i>
                    <span class="hidden sm:inline">Previous</span>
                </a>
            @endif

            @if($startPage > 1)
                <a
                    href="{{ $paginator->url(1) }}"
                    class="grid h-9 min-w-9 place-items-center rounded-md border border-slate-200 bg-white px-2 text-xs font-semibold text-slate-600 transition hover:border-[#c4622f] hover:text-[#c4622f]"
                >
                    1
                </a>

                @if($startPage > 2)
                    <span class="px-1 text-slate-400">…</span>
                @endif
            @endif

            @foreach($paginator->getUrlRange($startPage, $endPage) as $page => $url)
                @if($page === $currentPage)
                    <span
                        class="grid h-9 min-w-9 place-items-center rounded-md border border-[#173d32] bg-[#173d32] px-2 text-xs font-semibold text-white"
                        aria-current="page"
                    >
                        {{ $page }}
                    </span>
                @else
                    <a
                        href="{{ $url }}"
                        class="grid h-9 min-w-9 place-items-center rounded-md border border-slate-200 bg-white px-2 text-xs font-semibold text-slate-600 transition hover:border-[#c4622f] hover:bg-orange-50 hover:text-[#c4622f]"
                    >
                        {{ $page }}
                    </a>
                @endif
            @endforeach

            @if($endPage < $lastPage)
                @if($endPage < $lastPage - 1)
                    <span class="px-1 text-slate-400">…</span>
                @endif

                <a
                    href="{{ $paginator->url($lastPage) }}"
                    class="grid h-9 min-w-9 place-items-center rounded-md border border-slate-200 bg-white px-2 text-xs font-semibold text-slate-600 transition hover:border-[#c4622f] hover:text-[#c4622f]"
                >
                    {{ $lastPage }}
                </a>
            @endif

            @if($paginator->hasMorePages())
                <a
                    href="{{ $paginator->nextPageUrl() }}"
                    class="inline-flex h-9 items-center gap-2 rounded-md border border-slate-200 bg-white px-3 text-xs font-semibold text-slate-600 transition hover:border-[#c4622f] hover:bg-orange-50 hover:text-[#c4622f]"
                    rel="next"
                >
                    <span class="hidden sm:inline">Next</span>
                    <i class="fa-solid fa-chevron-right text-[9px]"></i>
                </a>
            @else
                <span
                    class="inline-flex h-9 cursor-not-allowed items-center gap-2 rounded-md border border-slate-200 bg-slate-50 px-3 text-xs font-semibold text-slate-300"
                    aria-disabled="true"
                >
                    <span class="hidden sm:inline">Next</span>
                    <i class="fa-solid fa-chevron-right text-[9px]"></i>
                </span>
            @endif
        </nav>
    </div>
@endif
