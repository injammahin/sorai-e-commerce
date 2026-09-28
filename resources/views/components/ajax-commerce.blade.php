<style>

    /* =========================================================
       AJAX COMMERCE TOAST
    ========================================================= */

    .ajax-commerce-toast {
        position: fixed;

        right: 24px;
        bottom: 24px;

        z-index: 9999;

        min-width: 260px;
        max-width: 360px;

        display: flex;

        align-items: center;

        gap: .75rem;

        padding:
            1rem
            1.1rem;

        background: #173d32;

        color: #ffffff;

        box-shadow:
            0
            18px
            45px
            rgba(0, 0, 0, .18);

        opacity: 0;

        visibility: hidden;

        transform:
            translateY(18px);

        transition:
            opacity .3s ease,
            transform .35s cubic-bezier(.22, 1, .36, 1),
            visibility .3s ease;

        pointer-events: none;
    }


    .ajax-commerce-toast.is-visible {
        opacity: 1;

        visibility: visible;

        transform:
            translateY(0);
    }


    .ajax-commerce-toast.is-error {
        background: #8e342b;
    }


    .ajax-commerce-toast-icon {
        width: 28px;
        height: 28px;

        flex: 0 0 28px;

        display: grid;

        place-items: center;

        border:
            1px solid
            rgba(255, 255, 255, .3);

        border-radius: 50%;

        font-size: 11px;
    }


    .ajax-commerce-toast-message {
        font-size: 12px;

        line-height: 1.5;
    }


    /* =========================================================
       AJAX BUTTON LOADING
    ========================================================= */

    [data-ajax-cart].is-loading button[type="submit"],
    [data-ajax-wishlist].is-loading button[type="submit"] {
        pointer-events: none;

        opacity: .65;
    }


    .product-favourite-button.is-favourite {
        background: #c4622f !important;

        border-color: #c4622f !important;

        color: #ffffff !important;
    }


    @media (max-width: 680px) {

        .ajax-commerce-toast {
            left: 14px;
            right: 14px;
            bottom: 14px;

            min-width: 0;
            max-width: none;
        }

    }

</style>



<div
    class="ajax-commerce-toast"
    data-ajax-commerce-toast
    role="status"
    aria-live="polite"
>

    <span class="ajax-commerce-toast-icon">

        <i class="fa-solid fa-check"></i>

    </span>


    <span
        class="ajax-commerce-toast-message"
        data-ajax-commerce-toast-message
    >

        Updated successfully.

    </span>

</div>



<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {


        /* =====================================================
           ELEMENTS
        ===================================================== */

        const toast =
            document.querySelector(
                '[data-ajax-commerce-toast]'
            );


        const toastMessage =
            toast?.querySelector(
                '[data-ajax-commerce-toast-message]'
            );


        const toastIcon =
            toast?.querySelector(
                '.ajax-commerce-toast-icon i'
            );


        let toastTimer = null;



        /* =====================================================
           TOAST
        ===================================================== */

        function showToast(
            message,
            type = 'success'
        ) {

            if (
                !toast ||
                !toastMessage
            ) {
                return;
            }


            window.clearTimeout(
                toastTimer
            );


            toastMessage.textContent =
                message;


            toast.classList.toggle(
                'is-error',
                type === 'error'
            );


            if (toastIcon) {

                toastIcon.className =
                    type === 'error'
                        ? 'fa-solid fa-xmark'
                        : 'fa-solid fa-check';

            }


            toast.classList.add(
                'is-visible'
            );


            toastTimer =
                window.setTimeout(
                    function () {

                        toast.classList.remove(
                            'is-visible'
                        );

                    },
                    2600
                );

        }



        /* =====================================================
           FIND HEADER
        ===================================================== */

        function getCurrentHeader() {

            return document.querySelector(
                '[data-aatchala-header]'
            );

        }



        /* =====================================================
           FIND LINK BY URL
        ===================================================== */

        function findLinkByUrl(
            root,
            url
        ) {

            if (!root) {
                return null;
            }


            return Array.from(
                root.querySelectorAll(
                    'a[href]'
                )
            ).find(
                function (link) {

                    return (
                        link.href ===
                        url
                    );

                }
            ) || null;

        }



        /* =====================================================
           SYNC ONE COUNT BADGE
        ===================================================== */

        function syncCountBadge(
            currentLink,
            serverLink
        ) {

            if (
                !currentLink ||
                !serverLink
            ) {
                return;
            }


            const currentBadge =
                currentLink.querySelector(
                    '.header-icon-count, .count'
                );


            const serverBadge =
                serverLink.querySelector(
                    '.header-icon-count, .count'
                );


            if (serverBadge) {

                let badge =
                    currentBadge;


                if (!badge) {

                    badge =
                        document.createElement(
                            'span'
                        );


                    badge.className =
                        'header-icon-count';


                    currentLink.appendChild(
                        badge
                    );

                }


                badge.textContent =
                    serverBadge.textContent
                        .trim();

            } else if (currentBadge) {

                currentBadge.remove();

            }


            /*
            |--------------------------------------------------------------------------
            | Update aria label too
            |--------------------------------------------------------------------------
            */

            if (
                serverLink.hasAttribute(
                    'aria-label'
                )
            ) {

                currentLink.setAttribute(
                    'aria-label',
                    serverLink.getAttribute(
                        'aria-label'
                    )
                );

            }

        }



        /* =====================================================
           SYNC HEADER FROM SERVER HTML
        ===================================================== */

        function syncHeaderFromDocument(
            serverDocument
        ) {

            const currentHeader =
                getCurrentHeader();


            const serverHeader =
                serverDocument.querySelector(
                    '[data-aatchala-header]'
                );


            if (
                !currentHeader ||
                !serverHeader
            ) {
                return;
            }



            /*
            |--------------------------------------------------------------------------
            | WISHLIST
            |--------------------------------------------------------------------------
            */

            const currentWishlist =
                currentHeader.querySelector(
                    '.wishlist-header-link'
                );


            const serverWishlist =
                serverHeader.querySelector(
                    '.wishlist-header-link'
                );


            if (
                currentWishlist &&
                serverWishlist
            ) {

                syncCountBadge(
                    currentWishlist,
                    serverWishlist
                );


                const currentHeart =
                    currentWishlist.querySelector(
                        '.fa-heart'
                    );


                const serverHeart =
                    serverWishlist.querySelector(
                        '.fa-heart'
                    );


                if (
                    currentHeart &&
                    serverHeart
                ) {

                    currentHeart.className =
                        serverHeart.className;

                }


                currentWishlist.classList.toggle(
                    'has-items',
                    serverWishlist
                        .classList
                        .contains(
                            'has-items'
                        )
                );

            }



            /*
            |--------------------------------------------------------------------------
            | CART
            |--------------------------------------------------------------------------
            */

            const cartUrl =
                @json(route('cart.index'));


            const currentCart =
                findLinkByUrl(
                    currentHeader,
                    cartUrl
                );


            const serverCart =
                findLinkByUrl(
                    serverHeader,
                    cartUrl
                );


            syncCountBadge(
                currentCart,
                serverCart
            );

        }



        /* =====================================================
           UPDATE PRODUCT HEART
        ===================================================== */

        function updateWishlistButton(
            form
        ) {

            const button =
                form.querySelector(
                    '.product-favourite-button, .wishlist-remove-button'
                );


            if (!button) {
                return;
            }


            const productId =
                form.dataset.productId;


            const isCurrentlyFavourite =
                button.classList.contains(
                    'is-favourite'
                )
                ||
                button.getAttribute(
                    'aria-pressed'
                ) === 'true'
                ||
                button.classList.contains(
                    'wishlist-remove-button'
                );


            const newState =
                !isCurrentlyFavourite;


            /*
            |--------------------------------------------------------------------------
            | Update all matching product hearts on current page
            |--------------------------------------------------------------------------
            */

            const forms =
                productId
                    ? document.querySelectorAll(
                        '[data-ajax-wishlist][data-product-id="' +
                        CSS.escape(productId) +
                        '"]'
                    )
                    : [form];


            forms.forEach(
                function (wishlistForm) {

                    const wishlistButton =
                        wishlistForm.querySelector(
                            '.product-favourite-button'
                        );


                    if (!wishlistButton) {
                        return;
                    }


                    wishlistButton
                        .classList
                        .toggle(
                            'is-favourite',
                            newState
                        );


                    wishlistButton.setAttribute(
                        'aria-pressed',
                        newState
                            ? 'true'
                            : 'false'
                    );


                    const icon =
                        wishlistButton.querySelector(
                            '.fa-heart'
                        );


                    if (icon) {

                        icon.classList.toggle(
                            'fa-solid',
                            newState
                        );


                        icon.classList.toggle(
                            'fa-regular',
                            !newState
                        );

                    }


                    wishlistButton.title =
                        newState
                            ? 'Remove from favourites'
                            : 'Add to favourites';

                }
            );


            /*
            |--------------------------------------------------------------------------
            | If user removes product directly from wishlist page,
            | remove card smoothly.
            |--------------------------------------------------------------------------
            */

            if (
                !newState &&
                form.closest(
                    '.wishlist-card'
                )
            ) {

                const card =
                    form.closest(
                        '.wishlist-card'
                    );


                card.style.transition =
                    'opacity .3s ease, transform .3s ease';


                card.style.opacity =
                    '0';


                card.style.transform =
                    'scale(.97)';


                window.setTimeout(
                    function () {

                        card.remove();

                    },
                    300
                );

            }


            return newState;

        }



        /* =====================================================
           SUBMIT USING FETCH
        ===================================================== */

        async function submitAjaxForm(
            form,
            type
        ) {

            if (
                form.classList.contains(
                    'is-loading'
                )
            ) {
                return;
            }


            form.classList.add(
                'is-loading'
            );


            const submitButton =
                form.querySelector(
                    'button[type="submit"]'
                );


            const originalButtonHtml =
                submitButton
                    ? submitButton.innerHTML
                    : '';


            const originalDisabled =
                submitButton
                    ? submitButton.disabled
                    : false;


            if (submitButton) {

                submitButton.disabled =
                    true;


                if (
                    type === 'cart'
                ) {

                    submitButton.innerHTML =
                        '<i class="fa-solid fa-spinner fa-spin"></i> <span>Adding...</span>';

                }

            }


            try {

                const formData =
                    new FormData(
                        form
                    );


                const csrfToken =
                    document.querySelector(
                        'meta[name="csrf-token"]'
                    )?.content;


                const response =
                    await fetch(
                        form.action,
                        {

                            method:
                                form.method
                                    ? form.method.toUpperCase()
                                    : 'POST',

                            body:
                                formData,

                            credentials:
                                'same-origin',

                            redirect:
                                'follow',

                            headers: {

                                'X-Requested-With':
                                    'XMLHttpRequest',

                                'X-CSRF-TOKEN':
                                    csrfToken || '',

                            },

                        }
                    );



                /*
                |--------------------------------------------------------------------------
                | If Laravel redirected guest to login
                |--------------------------------------------------------------------------
                */

                if (
                    response.redirected
                ) {

                    const redirectedUrl =
                        new URL(
                            response.url
                        );


                    if (
                        redirectedUrl.pathname
                            .includes(
                                '/login'
                            )
                    ) {

                        window.location.href =
                            response.url;

                        return;

                    }

                }



                if (
                    !response.ok
                ) {

                    throw new Error(
                        'Request failed'
                    );

                }



                /*
                |--------------------------------------------------------------------------
                | Existing controllers redirect back with normal HTML.
                |
                | We parse that returned HTML instead of navigating to it.
                |--------------------------------------------------------------------------
                */

                const html =
                    await response.text();


                const serverDocument =
                    new DOMParser()
                        .parseFromString(
                            html,
                            'text/html'
                        );



                /*
                |--------------------------------------------------------------------------
                | Synchronise exact server-rendered cart/wishlist counts.
                |--------------------------------------------------------------------------
                */

                syncHeaderFromDocument(
                    serverDocument
                );



                /*
                |--------------------------------------------------------------------------
                | WISHLIST
                |--------------------------------------------------------------------------
                */

                if (
                    type ===
                    'wishlist'
                ) {

                    const isFavourite =
                        updateWishlistButton(
                            form
                        );


                    showToast(
                        isFavourite
                            ? 'Added to favourites.'
                            : 'Removed from favourites.'
                    );

                }



                /*
                |--------------------------------------------------------------------------
                | CART
                |--------------------------------------------------------------------------
                */

                if (
                    type ===
                    'cart'
                ) {

                    showToast(
                        'Added to your bag.'
                    );


                    if (submitButton) {

                        submitButton.innerHTML =
                            '<i class="fa-solid fa-check"></i> <span>Added</span>';

                    }

                }


            } catch (error) {

                console.error(
                    error
                );


                showToast(
                    'Something went wrong. Please try again.',
                    'error'
                );


            } finally {

                form.classList.remove(
                    'is-loading'
                );


                if (submitButton) {

                    window.setTimeout(
                        function () {

                            submitButton.disabled =
                                originalDisabled;


                            submitButton.innerHTML =
                                originalButtonHtml;

                        },
                        900
                    );

                }

            }

        }



        /* =====================================================
           GLOBAL FORM LISTENER
        ===================================================== */

        document.addEventListener(
            'submit',
            function (event) {

                const form =
                    event.target;


                /*
                |--------------------------------------------------------------------------
                | WISHLIST
                |--------------------------------------------------------------------------
                */

                if (
                    form.matches(
                        '[data-ajax-wishlist]'
                    )
                ) {

                    event.preventDefault();


                    submitAjaxForm(
                        form,
                        'wishlist'
                    );


                    return;

                }



                /*
                |--------------------------------------------------------------------------
                | CART
                |--------------------------------------------------------------------------
                */


                if (
                    form.matches(
                        '[data-ajax-cart], ' +
                        '.product-hover-form, ' +
                        '.quick-view-cart'
                    )
                ) {

                    event.preventDefault();

                    event.stopPropagation();

                    submitAjaxForm(
                        form,
                        'cart'
                    );

                    return;

                }

            }
        );


    }
);

</script>