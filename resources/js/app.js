import axios from 'axios';


/* =============================================================
   AXIOS
============================================================= */

window.axios = axios;

window.axios.defaults.headers.common[
    'X-Requested-With'
] = 'XMLHttpRequest';


const body = document.body;


/* =============================================================
   STOREFRONT LAYERS / DRAWERS
============================================================= */

const openLayer = (id) => {

    const layer =
        document.getElementById(id);

    if (!layer) {
        return;
    }

    layer.classList.add('is-open');

    body.classList.add('no-scroll');
};


const closeLayers = () => {

    document
        .querySelectorAll(
            '.is-open[data-layer]'
        )
        .forEach(
            (element) => {

                element.classList.remove(
                    'is-open'
                );

            }
        );


    document
        .querySelectorAll(
            '.nav-wrap.is-open'
        )
        .forEach(
            (element) => {

                element.classList.remove(
                    'is-open'
                );

            }
        );


    /*
    |--------------------------------------------------------------------------
    | Do not remove no-scroll if mobile admin sidebar is open
    |--------------------------------------------------------------------------
    */

    const adminSidebarOpen =
        document
            .querySelector(
                '[data-admin-sidebar]'
            )
            ?.classList
            .contains('is-open');


    if (!adminSidebarOpen) {

        body.classList.remove(
            'no-scroll'
        );

    }

};



document.addEventListener(
    'click',
    (event) => {


        const trigger =
            event.target.closest(
                '[data-open]'
            );


        if (trigger) {

            event.preventDefault();

            openLayer(
                trigger.dataset.open
            );

        }


        if (
            event.target.matches(
                '[data-layer], [data-close]'
            )
            ||
            event.target.closest(
                '[data-close]'
            )
        ) {

            closeLayers();

        }

    }
);



/* =============================================================
   STOREFRONT NAV DROPDOWNS + DYNAMIC HOVER PREVIEWS
============================================================= */

const megaMenuItems = [
    ...document.querySelectorAll(
        '.nav-wrap[data-mega-menu]'
    ),
];


const reducedMenuMotion =
    window.matchMedia(
        '(prefers-reduced-motion: reduce)'
    );


const megaMenuControllers =
    new WeakMap();


let closeTimer;


const createMegaMenuPreview = (item) => {

    const card =
        item.querySelector(
            '[data-menu-preview-card]'
        );


    const previewLinks = [
        ...item.querySelectorAll(
            '[data-menu-preview]'
        ),
    ];


    if (!card || !previewLinks.length) {

        return {
            reset: () => {},
        };

    }


    const image =
        card.querySelector(
            '[data-menu-preview-image]'
        );


    const title =
        card.querySelector(
            '[data-menu-preview-title]'
        );


    const eyebrow =
        card.querySelector(
            '[data-menu-preview-eyebrow]'
        );


    const defaultPreview = {
        image: card.dataset.defaultImage,
        title: card.dataset.defaultTitle,
        eyebrow: card.dataset.defaultEyebrow,
        alt: card.dataset.defaultAlt,
        url: card.dataset.defaultUrl,
    };


    let swapTimer = null;

    let firstFrame = null;

    let secondFrame = null;


    const clearPendingSwap = () => {

        window.clearTimeout(
            swapTimer
        );

        window.cancelAnimationFrame(
            firstFrame
        );

        window.cancelAnimationFrame(
            secondFrame
        );

    };


    const updateCard = (preview) => {

        image.src = preview.image;

        image.alt = preview.alt;

        title.textContent = preview.title;

        eyebrow.textContent = preview.eyebrow;

        card.href = preview.url;

        card.dataset.currentPreviewImage =
            preview.image;

    };


    const showPreview = (
        preview,
        activeLink = null,
        immediate = false
    ) => {

        previewLinks.forEach(
            (link) => {

                link.classList.toggle(
                    'is-preview-active',
                    link === activeLink
                );

            }
        );


        clearPendingSwap();


        if (
            card.dataset.currentPreviewImage
            ===
            preview.image
        ) {

            updateCard(
                preview
            );

            card.classList.remove(
                'is-switching'
            );

            return;

        }


        if (
            immediate
            ||
            reducedMenuMotion.matches
        ) {

            updateCard(
                preview
            );

            card.classList.remove(
                'is-switching'
            );

            return;

        }


        card.classList.add(
            'is-switching'
        );


        swapTimer =
            window.setTimeout(
                () => {

                    updateCard(
                        preview
                    );


                    firstFrame =
                        window.requestAnimationFrame(
                            () => {

                                secondFrame =
                                    window.requestAnimationFrame(
                                        () => {

                                            card.classList.remove(
                                                'is-switching'
                                            );

                                        }
                                    );

                            }
                        );

                },
                145
            );

    };


    previewLinks.forEach(
        (link) => {

            const preview = {
                image: link.dataset.previewImage,
                title: link.dataset.previewTitle,
                eyebrow: link.dataset.previewEyebrow,
                alt: link.dataset.previewAlt,
                url: link.dataset.previewUrl,
            };


            const preload =
                new Image();

            preload.src =
                preview.image;


            const activate = () => {

                showPreview(
                    preview,
                    link
                );

            };


            link.addEventListener(
                'mouseenter',
                activate
            );


            link.addEventListener(
                'focus',
                activate
            );

        }
    );


    return {
        reset: () => {

            showPreview(
                defaultPreview,
                null,
                true
            );

        },
    };

};


const closeMegaMenu = (item) => {

    item.classList.remove(
        'is-open'
    );


    item.querySelector(
        '.nav-item'
    )?.setAttribute(
        'aria-expanded',
        'false'
    );


    megaMenuControllers
        .get(item)
        ?.reset();

};


const openMegaMenu = (item) => {

    window.clearTimeout(
        closeTimer
    );


    megaMenuItems.forEach(
        (otherItem) => {

            if (otherItem !== item) {

                closeMegaMenu(
                    otherItem
                );

            }

        }
    );


    item.classList.add(
        'is-open'
    );


    item.querySelector(
        '.nav-item'
    )?.setAttribute(
        'aria-expanded',
        'true'
    );

};


megaMenuItems.forEach(
    (item) => {

        megaMenuControllers.set(
            item,
            createMegaMenuPreview(item)
        );


        item.addEventListener(
            'mouseenter',
            () => {

                openMegaMenu(
                    item
                );

            }
        );


        item.addEventListener(
            'mouseleave',
            () => {

                closeTimer =
                    window.setTimeout(
                        () => {

                            closeMegaMenu(
                                item
                            );

                        },
                        130
                    );

            }
        );


        item.addEventListener(
            'focusin',
            () => {

                openMegaMenu(
                    item
                );

            }
        );


        item.addEventListener(
            'focusout',
            () => {

                closeTimer =
                    window.setTimeout(
                        () => {

                            if (
                                !item.contains(
                                    document.activeElement
                                )
                            ) {

                                closeMegaMenu(
                                    item
                                );

                            }

                        },
                        0
                    );

            }
        );


        item.addEventListener(
            'keydown',
            (event) => {

                if (event.key === 'Escape') {

                    closeMegaMenu(
                        item
                    );


                    item.querySelector(
                        '.nav-item'
                    )?.focus();

                }

            }
        );

    }
);



/* =============================================================
   HERO SLIDER
============================================================= */

const slides = [
    ...document.querySelectorAll(
        '.hero-slide'
    )
];


const heroDots =
    document.querySelectorAll(
        '.hero-dot'
    );


let slide = 0;

let timer = null;


const go = (nextSlide) => {


    if (!slides.length) {

        return;

    }


    slides[slide]
        ?.classList
        .remove(
            'is-active'
        );


    heroDots[slide]
        ?.classList
        .remove(
            'is-active'
        );


    slide =
        (
            nextSlide
            +
            slides.length
        )
        %
        slides.length;


    slides[slide]
        ?.classList
        .add(
            'is-active'
        );


    heroDots[slide]
        ?.classList
        .add(
            'is-active'
        );

};


if (
    slides.length
    >
    1
) {


    timer =
        setInterval(
            () => {

                go(
                    slide + 1
                );

            },
            6500
        );


    heroDots.forEach(
        (dot, index) => {


            dot.addEventListener(
                'click',
                () => {


                    clearInterval(
                        timer
                    );


                    go(index);


                    timer =
                        setInterval(
                            () => {

                                go(
                                    slide + 1
                                );

                            },
                            6500
                        );

                }
            );

        }
    );

}



/* =============================================================
   SCROLL REVEAL
============================================================= */

if (
    'IntersectionObserver'
    in
    window
) {


    const observer =
        new IntersectionObserver(
            (entries) => {


                entries.forEach(
                    (entry) => {


                        if (
                            entry.isIntersecting
                        ) {


                            entry.target
                                .classList
                                .add('in');


                            observer.unobserve(
                                entry.target
                            );

                        }


                    }
                );

            },
            {
                threshold: 0.12
            }
        );


    document
        .querySelectorAll(
            '.reveal'
        )
        .forEach(
            (element) => {

                observer.observe(
                    element
                );

            }
        );


}
else {


    document
        .querySelectorAll(
            '.reveal'
        )
        .forEach(
            (element) => {

                element.classList.add(
                    'in'
                );

            }
        );

}



/* =============================================================
   QUANTITY CONTROL
============================================================= */

document
    .querySelectorAll(
        '[data-qty]'
    )
    .forEach(
        (wrapper) => {


            const input =
                wrapper.querySelector(
                    'input'
                );


            if (!input) {

                return;

            }


            wrapper
                .querySelector(
                    '[data-minus]'
                )
                ?.addEventListener(
                    'click',
                    () => {


                        const current =
                            Number(
                                input.value
                            )
                            ||
                            1;


                        input.value =
                            Math.max(
                                1,
                                current - 1
                            );

                    }
                );


            wrapper
                .querySelector(
                    '[data-plus]'
                )
                ?.addEventListener(
                    'click',
                    () => {


                        const current =
                            Number(
                                input.value
                            )
                            ||
                            1;


                        input.value =
                            Math.min(
                                10,
                                current + 1
                            );

                    }
                );

        }
    );



/* =============================================================
   PRODUCT THUMBNAILS
============================================================= */

document
    .querySelectorAll(
        '[data-thumb]'
    )
    .forEach(
        (thumbnail) => {


            thumbnail.addEventListener(
                'click',
                () => {


                    const mainImage =
                        document.querySelector(
                            '[data-main-image]'
                        );


                    if (
                        mainImage
                        &&
                        thumbnail.dataset.thumb
                    ) {

                        mainImage.src =
                            thumbnail.dataset.thumb;

                    }

                }
            );

        }
    );



/* =============================================================
   AUTO REMOVE FLASH TOAST
============================================================= */

window.setTimeout(
    () => {


        document
            .querySelectorAll(
                '.toast'
            )
            .forEach(
                (toast) => {

                    toast.remove();

                }
            );


    },
    5000
);



/* =============================================================
   ADMIN COLLAPSIBLE SIDEBAR
============================================================= */

const adminShell =
    document.querySelector(
        '[data-admin-shell]'
    );


const adminSidebar =
    document.querySelector(
        '[data-admin-sidebar]'
    );


const adminMenuButton =
    document.querySelector(
        '[data-admin-menu]'
    );


const adminOverlay =
    document.querySelector(
        '[data-admin-overlay]'
    );


const adminTooltip =
    document.getElementById(
        'admin-nav-tooltip'
    );


const adminDesktopMedia =
    window.matchMedia(
        '(min-width: 901px)'
    );


const adminSidebarStorageKey =
    'sarai_admin_sidebar_collapsed';


let adminTooltipTrigger = null;

let adminTooltipHideTimer = null;



/* =============================================================
   READ SAVED SIDEBAR STATE
============================================================= */

function getSavedAdminSidebarState()
{

    try {


        return (
            localStorage.getItem(
                adminSidebarStorageKey
            )
            ===
            '1'
        );


    }
    catch (error) {


        return false;


    }

}



/* =============================================================
   SAVE SIDEBAR STATE
============================================================= */

function saveAdminSidebarState(
    collapsed
)
{

    try {


        localStorage.setItem(
            adminSidebarStorageKey,
            collapsed
                ? '1'
                : '0'
        );


    }
    catch (error) {


        /*
        |--------------------------------------------------------------------------
        | Storage can be unavailable in some private/restricted browser modes.
        |--------------------------------------------------------------------------
        */


    }

}



/* =============================================================
   HIDE COLLAPSED MENU TOOLTIP
============================================================= */

function hideAdminTooltip()
{

    if (!adminTooltip) {

        return;

    }


    clearTimeout(
        adminTooltipHideTimer
    );


    adminTooltip.classList.remove(
        'is-visible'
    );


    adminTooltip.setAttribute(
        'aria-hidden',
        'true'
    );


    adminTooltip.setAttribute(
        'tabindex',
        '-1'
    );


    adminTooltipTrigger =
        null;

}



/* =============================================================
   SHOW COLLAPSED MENU TOOLTIP
============================================================= */

function showAdminTooltip(
    trigger
)
{

    if (
        !adminTooltip
        ||
        !adminShell
        ||
        !adminDesktopMedia.matches
        ||
        !adminShell.classList.contains(
            'is-sidebar-collapsed'
        )
    ) {

        return;

    }


    const label =
        trigger.dataset.adminTooltip;


    if (!label) {

        return;

    }


    clearTimeout(
        adminTooltipHideTimer
    );


    const rect =
        trigger.getBoundingClientRect();


    adminTooltipTrigger =
        trigger;


    adminTooltip.textContent =
        label;


    /*
    |--------------------------------------------------------------------------
    | Position beside menu icon
    |--------------------------------------------------------------------------
    */

    adminTooltip.style.left =
        `${rect.right + 12}px`;


    adminTooltip.style.top =
        `${
            rect.top
            +
            rect.height / 2
        }px`;


    adminTooltip.classList.add(
        'is-visible'
    );


    adminTooltip.setAttribute(
        'aria-hidden',
        'false'
    );


    adminTooltip.setAttribute(
        'tabindex',
        '0'
    );

}



/* =============================================================
   DELAY TOOLTIP HIDE

   The small delay allows the cursor to move from
   the sidebar icon onto the floating menu name.
============================================================= */

function scheduleAdminTooltipHide()
{

    clearTimeout(
        adminTooltipHideTimer
    );


    adminTooltipHideTimer =
        window.setTimeout(
            () => {

                hideAdminTooltip();

            },
            180
        );

}



/* =============================================================
   DESKTOP COLLAPSE / EXPAND
============================================================= */

function setAdminSidebarCollapsed(
    collapsed,
    persist = true
)
{

    if (!adminShell) {

        return;

    }


    adminShell.classList.toggle(
        'is-sidebar-collapsed',
        collapsed
    );


    if (adminMenuButton) {


        adminMenuButton.setAttribute(
            'aria-expanded',
            collapsed
                ? 'false'
                : 'true'
        );


        adminMenuButton.setAttribute(
            'aria-label',
            collapsed
                ? 'Expand sidebar'
                : 'Collapse sidebar'
        );


        adminMenuButton.title =
            collapsed
                ? 'Expand sidebar'
                : 'Collapse sidebar';

    }


    hideAdminTooltip();


    if (persist) {


        saveAdminSidebarState(
            collapsed
        );


    }

}



/* =============================================================
   MOBILE SIDEBAR OPEN / CLOSE
============================================================= */

function setAdminMobileSidebar(
    open
)
{

    if (!adminSidebar) {

        return;

    }


    adminSidebar.classList.toggle(
        'is-open',
        open
    );


    adminOverlay
        ?.classList
        .toggle(
            'is-open',
            open
        );


    if (adminMenuButton) {


        adminMenuButton.setAttribute(
            'aria-expanded',
            open
                ? 'true'
                : 'false'
        );


        adminMenuButton.setAttribute(
            'aria-label',
            open
                ? 'Close sidebar'
                : 'Open sidebar'
        );


        adminMenuButton.title =
            open
                ? 'Close sidebar'
                : 'Open sidebar';

    }


    body.classList.toggle(
        'no-scroll',
        open
    );

}



/* =============================================================
   INITIALISE ADMIN SIDEBAR
============================================================= */

function initialiseAdminSidebar()
{

    if (
        !adminShell
        ||
        !adminSidebar
    ) {

        return;

    }


    /*
    |--------------------------------------------------------------------------
    | Desktop
    |--------------------------------------------------------------------------
    */

    if (
        adminDesktopMedia.matches
    ) {


        /*
        |--------------------------------------------------------------------------
        | Remove mobile state
        |--------------------------------------------------------------------------
        */

        adminSidebar.classList.remove(
            'is-open'
        );


        adminOverlay
            ?.classList
            .remove(
                'is-open'
            );


        body.classList.remove(
            'no-scroll'
        );


        /*
        |--------------------------------------------------------------------------
        | Restore saved desktop collapse state
        |--------------------------------------------------------------------------
        */

        setAdminSidebarCollapsed(
            getSavedAdminSidebarState(),
            false
        );


        return;

    }


    /*
    |--------------------------------------------------------------------------
    | Mobile
    |--------------------------------------------------------------------------
    */

    adminShell.classList.remove(
        'is-sidebar-collapsed'
    );


    adminSidebar.classList.remove(
        'is-open'
    );


    adminOverlay
        ?.classList
        .remove(
            'is-open'
        );


    body.classList.remove(
        'no-scroll'
    );


    if (adminMenuButton) {


        adminMenuButton.setAttribute(
            'aria-expanded',
            'false'
        );


        adminMenuButton.setAttribute(
            'aria-label',
            'Open sidebar'
        );


        adminMenuButton.title =
            'Open sidebar';

    }


    hideAdminTooltip();

}



/* =============================================================
   ADMIN HAMBURGER BUTTON
============================================================= */

adminMenuButton
    ?.addEventListener(
        'click',
        (event) => {


            event.preventDefault();

            event.stopPropagation();


            /*
            |--------------------------------------------------------------------------
            | Desktop
            |--------------------------------------------------------------------------
            */

            if (
                adminDesktopMedia.matches
            ) {


                const isCollapsed =
                    adminShell
                        ?.classList
                        .contains(
                            'is-sidebar-collapsed'
                        );


                setAdminSidebarCollapsed(
                    !isCollapsed
                );


                return;

            }


            /*
            |--------------------------------------------------------------------------
            | Mobile
            |--------------------------------------------------------------------------
            */

            const isOpen =
                adminSidebar
                    ?.classList
                    .contains(
                        'is-open'
                    );


            setAdminMobileSidebar(
                !isOpen
            );


        }
    );



/* =============================================================
   MOBILE SIDEBAR BACKDROP
============================================================= */

adminOverlay
    ?.addEventListener(
        'click',
        () => {


            setAdminMobileSidebar(
                false
            );


        }
    );



/* =============================================================
   CLOSE MOBILE SIDEBAR AFTER NAVIGATION CLICK
============================================================= */

document
    .querySelectorAll(
        `
        .admin-nav-link,
        .admin-brand,
        .admin-utility-link
        `
    )
    .forEach(
        (trigger) => {


            trigger.addEventListener(
                'click',
                () => {


                    if (
                        !adminDesktopMedia.matches
                    ) {


                        setAdminMobileSidebar(
                            false
                        );


                    }


                }
            );


        }
    );



/* =============================================================
   COLLAPSED SIDEBAR ICON HOVER
============================================================= */

document
    .querySelectorAll(
        '[data-admin-tooltip]'
    )
    .forEach(
        (trigger) => {


            /*
            |--------------------------------------------------------------------------
            | Mouse enter
            |--------------------------------------------------------------------------
            */

            trigger.addEventListener(
                'mouseenter',
                () => {


                    showAdminTooltip(
                        trigger
                    );


                }
            );


            /*
            |--------------------------------------------------------------------------
            | Mouse leave
            |--------------------------------------------------------------------------
            */

            trigger.addEventListener(
                'mouseleave',
                () => {


                    scheduleAdminTooltipHide();


                }
            );


            /*
            |--------------------------------------------------------------------------
            | Keyboard focus
            |--------------------------------------------------------------------------
            */

            trigger.addEventListener(
                'focus',
                () => {


                    showAdminTooltip(
                        trigger
                    );


                }
            );


            /*
            |--------------------------------------------------------------------------
            | Keyboard blur
            |--------------------------------------------------------------------------
            */

            trigger.addEventListener(
                'blur',
                () => {


                    scheduleAdminTooltipHide();


                }
            );


        }
    );



/* =============================================================
   KEEP TOOLTIP OPEN WHILE HOVERING THE NAME
============================================================= */

adminTooltip
    ?.addEventListener(
        'mouseenter',
        () => {


            clearTimeout(
                adminTooltipHideTimer
            );


        }
    );


adminTooltip
    ?.addEventListener(
        'mouseleave',
        () => {


            scheduleAdminTooltipHide();


        }
    );



/* =============================================================
   CLICK FLOATING MENU NAME

   Example:

   sidebar closed
        ↓
   hover Products icon
        ↓
   "Products" appears
        ↓
   click Products
        ↓
   opens /admin/products
============================================================= */

adminTooltip
    ?.addEventListener(
        'click',
        () => {


            const trigger =
                adminTooltipTrigger;


            if (!trigger) {


                hideAdminTooltip();

                return;


            }


            /*
            |--------------------------------------------------------------------------
            | Save reference before hiding because hideAdminTooltip()
            | clears adminTooltipTrigger.
            |--------------------------------------------------------------------------
            */

            const target =
                trigger;


            hideAdminTooltip();


            /*
            |--------------------------------------------------------------------------
            | Anchor
            |--------------------------------------------------------------------------
            */

            if (
                target.tagName
                ===
                'A'
            ) {


                const href =
                    target.getAttribute(
                        'href'
                    );


                if (href) {


                    window.location.href =
                        href;


                }


                return;

            }


            /*
            |--------------------------------------------------------------------------
            | Button / other clickable item
            |--------------------------------------------------------------------------
            */

            target.click();


        }
    );



/* =============================================================
   ADMIN KEYBOARD ESCAPE
============================================================= */

document.addEventListener(
    'keydown',
    (event) => {


        if (
            event.key
            !==
            'Escape'
        ) {

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | Storefront layers
        |--------------------------------------------------------------------------
        */

        closeLayers();


        /*
        |--------------------------------------------------------------------------
        | Admin mobile sidebar
        |--------------------------------------------------------------------------
        */

        if (
            !adminDesktopMedia.matches
        ) {


            setAdminMobileSidebar(
                false
            );


        }


        /*
        |--------------------------------------------------------------------------
        | Collapsed menu tooltip
        |--------------------------------------------------------------------------
        */

        hideAdminTooltip();


    }
);



/* =============================================================
   HIDE TOOLTIP WHEN PAGE MOVES
============================================================= */

window.addEventListener(
    'scroll',
    hideAdminTooltip,
    true
);


window.addEventListener(
    'resize',
    hideAdminTooltip
);



/* =============================================================
   RESPONSIVE BREAKPOINT CHANGE
============================================================= */

if (
    typeof
    adminDesktopMedia
        .addEventListener
    ===
    'function'
) {


    adminDesktopMedia.addEventListener(
        'change',
        initialiseAdminSidebar
    );


}
else {


    /*
    |--------------------------------------------------------------------------
    | Older browser fallback
    |--------------------------------------------------------------------------
    */

    adminDesktopMedia.addListener(
        initialiseAdminSidebar
    );


}



/* =============================================================
   START ADMIN SIDEBAR
============================================================= */

initialiseAdminSidebar();
