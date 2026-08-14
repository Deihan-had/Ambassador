function toggleWishlist(productId) {

    productId =
        Number(productId);


    const product =
        getProduct(productId);


    if (!product) {

        showToast(
            "Produk tidak ditemukan",
            "info"
        );

        return;

    }


    const index =
        wishlist.indexOf(productId);


    const isRemoving =
        index !== -1;


    if (isRemoving) {

        wishlist.splice(
            index,
            1
        );


        showToast(
            `${product.name} dihapus dari Wishlist`,
            "info"
        );

    } else {

        wishlist.push(
            productId
        );


        showToast(
            `${product.name} ditambahkan ke Wishlist`,
            "heart"
        );

    }


    wishlist =
        [...new Set(wishlist)];


    saveState();
    updateBadges();


    renderProducts();


    const wishlistModal =
        document.getElementById(
            "wishlistModal"
        );


    if (
        wishlistModal &&
        !wishlistModal.classList.contains(
            "invisible"
        )
    ) {

        renderWishlistModal();

    }


    requestAnimationFrame(() => {

        const button =
            document.querySelector(
                `[data-wishlist-id="${productId}"]`
            );


        if (!button)
            return;


        button.classList.add(
            "scale-125"
        );


        setTimeout(() => {

            button.classList.remove(
                "scale-125"
            );

        }, 180);

    });

}


/* =========================================================
   WISHLIST MODAL
========================================================= */

function toggleWishlistModal() {

    const modal =
        document.getElementById(
            "wishlistModal"
        );


    const backdrop =
        document.getElementById(
            "wishlistBackdrop"
        );


    const panel =
        document.getElementById(
            "wishlistPanel"
        );


    if (
        !modal ||
        !backdrop ||
        !panel
    )
        return;


    if (
        modal.classList.contains(
            "invisible"
        )
    ) {

        renderWishlistModal();
        updateBadges();


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

    } else {

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

}


/* =========================================================
   WISHLIST RENDER
========================================================= */

function renderWishlistModal() {

    const container =
        document.getElementById(
            "wishlistItemsContainer"
        );


    if (!container)
        return;


    const wishlistProducts =
        products.filter(
            product =>
                wishlist.includes(
                    product.id
                )
        );


    if (
        wishlistProducts.length === 0
    ) {

        container.innerHTML = `

            <div
                class="
                    text-center
                    py-12
                    px-4
                ">


                <div
                    class="
                        mx-auto
                        mb-4
                        w-16
                        h-16
                        rounded-full
                        bg-red-50
                        flex
                        items-center
                        justify-center
                    ">

                    <i
                        class="
                            fa-regular
                            fa-heart
                            text-2xl
                            text-red-400
                        ">
                    </i>

                </div>


                <h3
                    class="
                        text-sm
                        font-extrabold
                        text-slate-800
                        mb-1
                    ">

                    Wishlist masih kosong

                </h3>


                <p
                    class="
                        text-xs
                        text-slate-500
                        leading-relaxed
                        max-w-xs
                        mx-auto
                    ">

                    Tekan ikon hati pada produk
                    yang kamu suka untuk
                    menyimpannya di sini.

                </p>

            </div>

        `;


        return;

    }


    container.innerHTML = `

        <div
            class="
                mb-3
                flex
                items-center
                justify-between
            ">

            <span
                class="
                    text-xs
                    font-semibold
                    text-slate-500
                ">

                ${wishlistProducts.length}
                produk tersimpan

            </span>


            <button
                type="button"
                onclick="clearWishlist()"
                class="
                    text-xs
                    font-bold
                    text-red-500
                    hover:text-red-600
                    transition-colors
                ">

                Kosongkan

            </button>

        </div>


        <div
            class="
                space-y-3
            ">


            ${
                wishlistProducts.map(item => `

                    <div
                        class="
                            flex
                            items-center
                            gap-3
                            bg-white
                            p-3
                            rounded-2xl
                            border
                            border-slate-200
                            shadow-sm
                            hover:shadow-md
                            transition-shadow
                        ">


                        <!-- IMAGE -->

                        <button
                            type="button"
                            onclick="openProductModal(${item.id})"
                            class="shrink-0">


                            <img
                                src="${item.image}"
                                alt="${item.name}"
                                class="
                                    w-16
                                    h-16
                                    object-cover
                                    rounded-xl
                                    bg-slate-100
                                "
                                onerror="
                                    this.src='https://placehold.co/100x100/e2e8f0/475569?text=Produk'
                                ">

                        </button>


                        <!-- INFO -->

                        <div
                            class="
                                flex-1
                                min-w-0
                            ">


                            <button
                                type="button"
                                onclick="openProductModal(${item.id})"
                                class="
                                    block
                                    text-left
                                    w-full
                                ">


                                <h4
                                    class="
                                        text-xs
                                        font-bold
                                        text-slate-800
                                        line-clamp-2
                                        hover:text-emerald-600
                                        transition-colors
                                    ">

                                    ${item.name}

                                </h4>


                            </button>


                            <div
                                class="
                                    mt-1
                                    flex
                                    items-center
                                    gap-2
                                ">


                                <span
                                    class="
                                        text-sm
                                        font-extrabold
                                        text-emerald-600
                                    ">

                                    ${formatIDR(item.price)}

                                </span>


                                ${
                                    item.oldPrice
                                        ? `
                                            <span
                                                class="
                                                    text-[10px]
                                                    text-slate-400
                                                    line-through
                                                ">

                                                ${formatIDR(item.oldPrice)}

                                            </span>
                                        `
                                        : ""
                                }


                            </div>


                        </div>


                        <!-- ACTIONS -->

                        <div
                            class="
                                flex
                                flex-col
                                gap-1.5
                                shrink-0
                            ">


                            <button
                                type="button"
                                onclick="buyNow(${item.id})"
                                class="
                                    bg-emerald-600
                                    hover:bg-emerald-700
                                    text-white
                                    text-[11px]
                                    font-bold
                                    px-3
                                    py-2
                                    rounded-lg
                                    transition
                                    active:scale-95
                                ">

                                <i
                                    class="
                                        fa-solid
                                        fa-bolt
                                        mr-1
                                    ">
                                </i>

                                Beli

                            </button>


                            <button
                                type="button"
                                onclick="toggleWishlist(${item.id})"
                                aria-label="Hapus ${item.name} dari wishlist"
                                class="
                                    text-slate-400
                                    hover:text-red-500
                                    text-[11px]
                                    py-1
                                    transition-colors
                                ">

                                <i
                                    class="
                                        fa-solid
                                        fa-heart
                                        mr-1
                                    ">
                                </i>

                                Hapus

                            </button>


                        </div>


                    </div>

                `).join("")
            }


        </div>

    `;

}


/* =========================================================
   CLEAR WISHLIST
========================================================= */

function clearWishlist() {

    if (wishlist.length === 0) {

        showToast(
            "Wishlist sudah kosong",
            "info"
        );

        return;

    }


    wishlist = [];


    saveState();
    updateBadges();

    renderProducts();
    renderWishlistModal();


    showToast(
        "Semua produk dihapus dari Wishlist",
        "info"
    );

}