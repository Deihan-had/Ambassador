function formatIDR(amount) {

    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        maximumFractionDigits: 0
    }).format(amount);

}


/* =========================================================
   BADGES
========================================================= */

function updateBadges() {

    const totalCartItems =
        cart.reduce(
            (sum, item) =>
                sum +
                Number(item.qty || 0),
            0
        );


    const cartBadge =
        document.getElementById(
            "cartCount"
        );


    const cartHeaderBadge =
        document.getElementById(
            "cartHeaderBadge"
        );


    const wishlistBadge =
        document.getElementById(
            "wishlistCount"
        );


    if (cartBadge)
        cartBadge.textContent =
            totalCartItems;


    if (cartHeaderBadge)
        cartHeaderBadge.textContent =
            totalCartItems;


    if (wishlistBadge) {

        wishlistBadge.textContent =
            wishlist.length;


        wishlistBadge.classList.toggle(
            "hidden",
            wishlist.length === 0
        );

    }

}


/* =========================================================
   TOAST
========================================================= */

let toastTimeout;


function showToast(
    message,
    iconType = "info"
) {

    const toast =
        document.getElementById(
            "toast"
        );


    const msgEl =
        document.getElementById(
            "toastMessage"
        );


    const iconEl =
        document.getElementById(
            "toastIcon"
        );


    if (
        !toast ||
        !msgEl ||
        !iconEl
    )
        return;


    msgEl.textContent =
        message;


    if (iconType === "cart") {

        iconEl.className =
            "fa-solid fa-cart-shopping text-emerald-400 text-lg";

    }

    else if (iconType === "heart") {

        iconEl.className =
            "fa-solid fa-heart text-red-500 text-lg";

    }

    else {

        iconEl.className =
            "fa-solid fa-circle-info text-blue-400 text-lg";

    }


    toast.classList.remove(
        "hidden"
    );


    clearTimeout(
        toastTimeout
    );


    toastTimeout =
        setTimeout(() => {

            toast.classList.add(
                "hidden"
            );

        }, 3000);

}


/* =========================================================
   COUNTDOWN
========================================================= */

function startCountdown() {

    let hours = 8;
    let minutes = 42;
    let seconds = 19;


    setInterval(() => {

        seconds--;


        if (seconds < 0) {

            seconds = 59;
            minutes--;

        }


        if (minutes < 0) {

            minutes = 59;
            hours--;

        }


        if (hours < 0)
            hours = 23;


        const hEl =
            document.getElementById(
                "hours"
            );


        const mEl =
            document.getElementById(
                "minutes"
            );


        const sEl =
            document.getElementById(
                "seconds"
            );


        if (hEl)
            hEl.textContent =
                String(hours)
                    .padStart(2, "0");


        if (mEl)
            mEl.textContent =
                String(minutes)
                    .padStart(2, "0");


        if (sEl)
            sEl.textContent =
                String(seconds)
                    .padStart(2, "0");


    }, 1000);

}


/* =========================================================
   KEYBOARD / ESCAPE
========================================================= */

document.addEventListener(
    "keydown",
    event => {

        if (event.key !== "Escape")
            return;


        const productModal =
            document.getElementById(
                "productModal"
            );


        const wishlistModal =
            document.getElementById(
                "wishlistModal"
            );


        const cartModal =
            document.getElementById(
                "cartModal"
            );


        const checkoutModal =
            document.getElementById(
                "checkoutModal"
            );


        if (
            productModal &&
            !productModal.classList.contains(
                "invisible"
            )
        ) {

            closeProductModal();

            return;

        }


        if (
            wishlistModal &&
            !wishlistModal.classList.contains(
                "invisible"
            )
        ) {

            toggleWishlistModal();

            return;

        }


        if (
            cartModal &&
            !cartModal.classList.contains(
                "invisible"
            )
        ) {

            toggleCartModal();

            return;

        }


        if (
            checkoutModal &&
            !checkoutModal.classList.contains(
                "invisible"
            )
        ) {

            closeCheckout();

        }

    }
);