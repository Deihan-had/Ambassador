function addToCart(productId, quantity = 1) {

    const product =
        getProduct(productId);


    if (!product) {

        showToast(
            "Produk tidak ditemukan",
            "info"
        );

        return;

    }


    if (product.stock <= 0) {

        showToast(
            "Produk sedang habis",
            "info"
        );

        return;

    }


    quantity = Number(quantity);


    if (
        !Number.isFinite(quantity) ||
        quantity <= 0
    ) {

        quantity = 1;

    }


    const existingIndex =
        cart.findIndex(
            item => item.id === product.id
        );


    const currentQty =
        existingIndex > -1
            ? Number(cart[existingIndex].qty)
            : 0;


    const nextQty =
        currentQty + quantity;


    if (nextQty > product.stock) {

        showToast(
            `Stok ${product.name} hanya ${product.stock} pcs`,
            "info"
        );

        return;

    }


    if (existingIndex > -1) {

        cart[existingIndex].qty =
            nextQty;

    } else {

        cart.push({
            ...product,
            qty: quantity
        });

    }


    saveState();
    updateBadges();


    showToast(
        `${product.name} ditambahkan ke keranjang`,
        "cart"
    );

}


function updateCartQty(productId, change) {

    const index =
        cart.findIndex(
            item => item.id === productId
        );


    if (index === -1)
        return;


    const product =
        getProduct(productId);


    cart[index].qty =
        Number(cart[index].qty) +
        Number(change);


    if (
        product &&
        cart[index].qty > product.stock
    ) {

        cart[index].qty =
            product.stock;


        showToast(
            `Maksimal stok ${product.stock} pcs`,
            "info"
        );

    }


    if (cart[index].qty <= 0) {

        cart.splice(index, 1);

    }


    saveState();
    updateBadges();
    renderCartModal();

}


function removeFromCart(productId) {

    cart =
        cart.filter(
            item => item.id !== productId
        );


    saveState();
    updateBadges();
    renderCartModal();


    showToast(
        "Item dihapus dari keranjang",
        "info"
    );

}


/* =========================================================
   CART MODAL
========================================================= */

function toggleCartModal() {

    const modal =
        document.getElementById("cartModal");


    const backdrop =
        document.getElementById("cartBackdrop");


    const panel =
        document.getElementById("cartPanel");


    if (
        !modal ||
        !backdrop ||
        !panel
    )
        return;


    if (
        modal.classList.contains("invisible")
    ) {

        renderCartModal();


        modal.classList.remove(
            "invisible"
        );


        setTimeout(() => {

            backdrop.classList.add(
                "opacity-100"
            );


            panel.classList.remove(
                "translate-x-full"
            );

        }, 10);

    } else {

        backdrop.classList.remove(
            "opacity-100"
        );


        panel.classList.add(
            "translate-x-full"
        );


        setTimeout(() => {

            modal.classList.add(
                "invisible"
            );

        }, 300);

    }

}


function renderCartModal() {

    const container =
        document.getElementById(
            "cartItemsContainer"
        );


    const subtotalEl =
        document.getElementById(
            "cartSubtotal"
        );


    const discountEl =
        document.getElementById(
            "cartDiscount"
        );


    const totalEl =
        document.getElementById(
            "cartTotal"
        );


    const checkoutBtn =
        document.getElementById(
            "checkoutBtn"
        );


    if (!container)
        return;


    if (cart.length === 0) {

        container.innerHTML = `

            <div
                class="
                    text-center
                    py-12
                ">

                <i
                    class="
                        fa-solid
                        fa-basket-shopping
                        text-4xl
                        text-slate-300
                        mb-3
                    ">
                </i>


                <p
                    class="
                        text-slate-500
                        text-sm
                        font-medium
                    ">

                    Keranjang Anda masih kosong

                </p>

            </div>

        `;


        if (subtotalEl)
            subtotalEl.textContent = "Rp 0";


        if (discountEl)
            discountEl.textContent = "- Rp 0";


        if (totalEl)
            totalEl.textContent = "Rp 0";


        if (checkoutBtn)
            checkoutBtn.disabled = true;


        return;

    }


    if (checkoutBtn)
        checkoutBtn.disabled = false;


    let subtotal = 0;


    container.innerHTML =
        cart.map(item => {

            const itemTotal =
                item.price * item.qty;


            subtotal += itemTotal;


            return `

                <div
                    class="
                        flex
                        gap-3
                        bg-white
                        p-3
                        rounded-xl
                        border
                        border-slate-200/80
                        shadow-xs
                    ">


                    <img
                        src="${item.image}"
                        alt="${item.name}"
                        class="
                            w-16
                            h-16
                            object-cover
                            rounded-lg
                            bg-slate-100
                        "
                        onerror="
                            this.src='https://placehold.co/100x100/e2e8f0/475569?text=Produk'
                        ">


                    <div
                        class="
                            flex-1
                            min-w-0
                            flex
                            flex-col
                            justify-between
                        ">


                        <div>

                            <h4
                                class="
                                    text-xs
                                    font-bold
                                    text-slate-800
                                    truncate
                                ">

                                ${item.name}

                            </h4>


                            <span
                                class="
                                    text-xs
                                    font-semibold
                                    text-emerald-600
                                ">

                                ${formatIDR(item.price)}

                            </span>

                        </div>


                        <div
                            class="
                                flex
                                items-center
                                justify-between
                                pt-1
                            ">


                            <div
                                class="
                                    flex
                                    items-center
                                    border
                                    border-slate-200
                                    rounded-lg
                                    overflow-hidden
                                    bg-slate-50
                                ">


                                <button
                                    type="button"
                                    onclick="updateCartQty(${item.id}, -1)"
                                    class="
                                        px-2
                                        py-0.5
                                        text-xs
                                        text-slate-600
                                        hover:bg-slate-200
                                    ">

                                    -

                                </button>


                                <span
                                    class="
                                        px-2
                                        text-xs
                                        font-bold
                                    ">

                                    ${item.qty}

                                </span>


                                <button
                                    type="button"
                                    onclick="updateCartQty(${item.id}, 1)"
                                    class="
                                        px-2
                                        py-0.5
                                        text-xs
                                        text-slate-600
                                        hover:bg-slate-200
                                    ">

                                    +

                                </button>


                            </div>


                            <button
                                type="button"
                                onclick="removeFromCart(${item.id})"
                                class="
                                    text-slate-400
                                    hover:text-red-500
                                    text-xs
                                ">

                                <i
                                    class="fa-solid fa-trash">
                                </i>

                            </button>

                        </div>

                    </div>

                </div>

            `;

        }).join("");


    const discountAmount =
        subtotal *
        appliedDiscount /
        100;


    const finalTotal =
        subtotal -
        discountAmount;


    if (subtotalEl)
        subtotalEl.textContent =
            formatIDR(subtotal);


    if (discountEl)
        discountEl.textContent =
            `- ${formatIDR(discountAmount)}`;


    if (totalEl)
        totalEl.textContent =
            formatIDR(finalTotal);

}


/* =========================================================
   BUY NOW
========================================================= */

/*
    Beli Sekarang:
    - Tidak mengganggu cart normal secara permanen.
    - Menyimpan cart lama.
    - Checkout hanya produk yang dipilih.
    - Setelah order selesai, cart lama dikembalikan.
*/

function buyNow(productId) {

    const product =
        getProduct(productId);


    if (!product) {

        showToast(
            "Produk tidak ditemukan",
            "info"
        );

        return;

    }


    if (product.stock <= 0) {

        showToast(
            "Produk sedang habis",
            "info"
        );

        return;

    }


    // Simpan cart asli
    previousCartBeforeBuyNow =
        [...cart];


    // Tandai mode Buy Now
    isBuyNowCheckout = true;


    // Checkout hanya produk ini
    cart = [
        {
            ...product,
            qty: 1
        }
    ];


    // Buy Now tidak memakai promo lama
    appliedDiscount = 0;


    saveState();
    updateBadges();


    // Tutup detail modal jika sedang terbuka
    const productModal =
        document.getElementById(
            "productModal"
        );


    if (
        productModal &&
        !productModal.classList.contains(
            "invisible"
        )
    ) {

        closeProductModal();

    }


    // Buka checkout langsung
    openCheckout();

}


/* =========================================================
   PROMO
========================================================= */

function applyPromoCode() {

    const input =
        document.getElementById(
            "promoInput"
        );


    const msg =
        document.getElementById(
            "promoMessage"
        );


    if (
        !input ||
        !msg
    )
        return;


    const code =
        input.value
            .trim()
            .toUpperCase();


    if (code === "DISCOUNT10") {

        appliedDiscount = 10;


        msg.textContent =
            "Kode promo berhasil dipasang! (Diskon 10%)";


        msg.className =
            "text-xs font-semibold text-emerald-600 block";


        renderCartModal();

    } else {

        appliedDiscount = 0;


        msg.textContent =
            "Kode promo tidak valid.";


        msg.className =
            "text-xs font-semibold text-red-500 block";


        renderCartModal();

    }

}


function applyPromoQuick(code) {

    toggleCartModal();


    const input =
        document.getElementById(
            "promoInput"
        );


    if (input)
        input.value = code;


    applyPromoCode();

}