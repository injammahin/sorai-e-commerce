@props(['banner' => null])

@if ($banner)
    <div
        class="home-popup"
        data-home-popup
        data-popup-delay="500"
        aria-hidden="true"
    >
        <div
            class="home-popup-dialog"
            role="dialog"
            aria-modal="true"
            aria-labelledby="home-popup-title-{{ $banner->id }}"
        >
            <h2
                id="home-popup-title-{{ $banner->id }}"
                class="sr-only"
            >
                {{ $banner->title ?: 'Promotion' }}
            </h2>

            <button
                class="home-popup-close"
                type="button"
                data-home-popup-close
                aria-label="Close promotional banner"
            >
                <i
                    class="fa-solid fa-xmark"
                    aria-hidden="true"
                ></i>
            </button>

            @if ($banner->link_url)
                <a
                    class="home-popup-media"
                    href="{{ $banner->link_url }}"
                    aria-label="{{ $banner->button_text ?: $banner->title ?: 'View promotion' }}"
                    @if ($banner->link_is_external)
                        target="_blank"
                        rel="noopener noreferrer"
                    @endif
                >
            @else
                <div class="home-popup-media">
            @endif

                <picture>
                    @if ($banner->mobile_image)
                        <source
                            media="(max-width: 680px)"
                            srcset="{{ asset($banner->mobile_image) }}"
                        >
                    @endif

                    <img
                        src="{{ asset($banner->image) }}"
                        alt="{{ $banner->title ?: 'Promotion' }}"
                        decoding="async"
                    >
                </picture>

                @if ($banner->link_url && $banner->button_text)
                    <span class="home-popup-cta">
                        {{ $banner->button_text }}

                        <i
                            class="fa-solid fa-arrow-right"
                            aria-hidden="true"
                        ></i>
                    </span>
                @endif

            @if ($banner->link_url)
                </a>
            @else
                </div>
            @endif
        </div>
    </div>
@endif