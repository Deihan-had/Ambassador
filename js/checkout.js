function openCheckout() {

    if (cart.length === 0) {

        showToast(
            "Keranjang masih kosong",
            "info"
        );

        return;

    }


    /*
        Kalau Buy Now,
        jangan toggle cart karena cart modal
        tidak perlu dibuka.
    */

    if (!isBuyNowCheckout) {

        const cartModal =
            document.getElementById(
                "cartModal"
            );


        if (
            cartModal &&
            !cartModal.classList.contains(
                "invisible"
            )
        ) {

            toggleCartModal();

        }

    } else {

        // Pastikan cart modal tertutup
        const cartModal =
            document.getElementById(
                "cartModal"
            );


        if (
            cartModal &&
            !cartModal.classList.contains(
                "invisible"
            )
        ) {

            toggleCartModal();

        }

    }


    const subtotal =
        cart.reduce(
            (sum, item) =>
                sum +
                item.price *
                item.qty,
            0
        );


    const discountAmount =
        subtotal *
        appliedDiscount /
        100;


    const finalTotal =
        subtotal -
        discountAmount;


    const checkoutTotal =
        document.getElementById(
            "checkoutTotal"
        );


    if (checkoutTotal)
        checkoutTotal.textContent =
            formatIDR(finalTotal);


    const modal =
        document.getElementById(
            "checkoutModal"
        );


    const backdrop =
        document.getElementById(
            "checkoutBackdrop"
        );


    const panel =
        document.getElementById(
            "checkoutPanel"
        );


    if (
        !modal ||
        !backdrop ||
        !panel
    )
        return;


    modal.classList.remove(
        "invisible"
    );


    setTimeout(() => {

        backdrop.classList.add(
            "opacity-100"
        );


        panel.classList.remove(
            "opacity-0",
            "scale-95"
        );

    }, 10);

}


function closeCheckout() {

    const modal =
        document.getElementById(
            "checkoutModal"
        );


    const backdrop =
        document.getElementById(
            "checkoutBackdrop"
        );


    const panel =
        document.getElementById(
            "checkoutPanel"
        );


    if (
        !modal ||
        !backdrop ||
        !panel
    )
        return;


    backdrop.classList.remove(
        "opacity-100"
    );


    panel.classList.add(
        "opacity-0",
        "scale-95"
    );


    setTimeout(() => {

        modal.classList.add(
            "invisible"
        );

    }, 300);

}


/* =========================================================
   PLACE ORDER
========================================================= */

function handlePlaceOrder(e) {

    e.preventDefault();


    /*
        Jika Buy Now:
        produk yang sedang dibeli dianggap selesai,
        lalu cart lama dikembalikan.
    */

    if (isBuyNowCheckout) {

        cart =
            previousCartBeforeBuyNow
                ? [...previousCartBeforeBuyNow]
                : [];


        previousCartBeforeBuyNow =
            null;


        isBuyNowCheckout =
            false;

    } else {

        // Checkout normal
        cart = [];

    }


    appliedDiscount = 0;


    saveState();
    updateBadges();


    closeCheckout();


    const randomInv =
        "#INV-" +
        Math.floor(
            10000 +
            Math.random() * 90000
        );


    const invoiceNumber =
        document.getElementById(
            "invoiceNumber"
        );


    if (invoiceNumber)
        invoiceNumber.textContent =
            randomInv;


    const modal =
        document.getElementById(
            "successModal"
        );


    if (modal)
        modal.classList.remove(
            "invisible"
        );

}


function closeSuccessModal() {

    const modal =
        document.getElementById(
            "successModal"
        );


    if (modal)
        modal.classList.add(
            "invisible"
        );

}