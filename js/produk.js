function getProduct(productId) {
    return products.find(
        product => product.id === Number(productId)
    );
}


/* =========================================================
   CATEGORY
========================================================= */

function renderCategories() {
    const container = document.getElementById("categoryContainer");
    if (!container) return;

    container.innerHTML = categories.map(category => {
        const isActive = category === activeCategory;
        const label = category === "All" ? "Semua Kategori" : category;

        return `
            <button
                type="button"
                onclick="filterByCategory('${category}')"
                class="
                    px-4 py-2
                    rounded-xl
                    text-sm
                    font-semibold
                    whitespace-nowrap
                    transition-all
                    duration-200
                    ${isActive
                        ? "bg-emerald-600 text-white shadow-md shadow-emerald-600/30"
                        : "bg-white text-slate-600 hover:bg-slate-100 border border-slate-200"
                    }
                ">
                ${label}
            </button>
        `;
    }).join("");
}

function filterByCategory(category) {
    activeCategory = category;
    renderCategories();
    renderProducts();
}


/* =========================================================
   SEARCH
========================================================= */

function handleSearch(isMobile = false) {
    const inputId = isMobile ? "mobileSearchInput" : "searchInput";
    const input = document.getElementById(inputId);

    if (!input) return;

    searchQuery = input.value.toLowerCase().trim();

    const clearBtn = document.getElementById("clearSearch");
    if (clearBtn) {
        clearBtn.classList.toggle("hidden", searchQuery.length === 0);
    }

    renderProducts();
}

function clearSearch() {
    const searchInput = document.getElementById("searchInput");
    const mobileSearchInput = document.getElementById("mobileSearchInput");

    if (searchInput) searchInput.value = "";
    if (mobileSearchInput) mobileSearchInput.value = "";

    searchQuery = "";

    const clearBtn = document.getElementById("clearSearch");
    if (clearBtn) clearBtn.classList.add("hidden");

    renderProducts();
}


/* =========================================================
   SORT
========================================================= */

function handleSort() {
    const sortSelect = document.getElementById("sortSelect");
    if (!sortSelect) return;

    currentSort = sortSelect.value;
    renderProducts();
}

function resetFilters() {
    activeCategory = "All";
    searchQuery = "";
    currentSort = "featured";

    const searchInput = document.getElementById("searchInput");
    const mobileSearchInput = document.getElementById("mobileSearchInput");
    const sortSelect = document.getElementById("sortSelect");

    if (searchInput) searchInput.value = "";
    if (mobileSearchInput) mobileSearchInput.value = "";
    if (sortSelect) sortSelect.value = "featured";

    const clearBtn = document.getElementById("clearSearch");
    if (clearBtn) clearBtn.classList.add("hidden");

    renderCategories();
    renderProducts();
}


/* =========================================================
   FILTER PRODUCTS
========================================================= */

function getFilteredProducts() {
    return products
        .filter(product => {
            const matchesCategory =
                activeCategory === "All" || product.category === activeCategory;

            const matchesSearch =
                product.name.toLowerCase().includes(searchQuery) ||
                product.description.toLowerCase().includes(searchQuery);

            return matchesCategory && matchesSearch;
        })
        .sort((a, b) => {
            if (currentSort === "price-low") return a.price - b.price;
            if (currentSort === "price-high") return b.price - a.price;
            if (currentSort === "rating") return b.rating - a.rating;
            return a.id - b.id;
        });
}


/* =========================================================
   PRODUCT RENDER
========================================================= */

function renderProducts() {
    const grid = document.getElementById("productGrid");
    const emptyState = document.getElementById("emptyState");
    const countLabel = document.getElementById("productCount");
    const catalogTitle = document.getElementById("catalogTitle");

    if (!grid) return;

    const filtered = getFilteredProducts();

    if (countLabel) countLabel.textContent = `Menampilkan ${filtered.length} produk`;

    if (catalogTitle) {
        catalogTitle.textContent =
            activeCategory === "All" ? "Semua Produk" : activeCategory;
    }

    if (filtered.length === 0) {
        grid.innerHTML = "";
        if (emptyState) emptyState.classList.remove("hidden");
        return;
    }

    if (emptyState) emptyState.classList.add("hidden");

    grid.innerHTML = filtered.map(product => {
        const isWishlisted = wishlist.includes(product.id);
        const isOutOfStock = product.stock <= 0;

        return `
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col overflow-hidden group">
                
                <!-- IMAGE -->
                <div class="relative overflow-hidden aspect-square bg-slate-100">
                    <img
                        src="${product.image}"
                        alt="${product.name}"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                        onerror="this.src='https://placehold.co/400x400/e2e8f0/475569?text=Gambar+Tidak+Tersedia'"
                    >

                    ${product.badge ? `
                        <span class="absolute top-3 left-3 bg-slate-900/90 backdrop-blur text-white text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">
                            ${product.badge}
                        </span>
                    ` : ""}

                    <!-- WISHLIST -->
                    <button
                        type="button"
                        data-wishlist-id="${product.id}"
                        onclick="toggleWishlist(${product.id})"
                        aria-label="${isWishlisted ? "Hapus dari wishlist" : "Tambah ke wishlist"}"
                        aria-pressed="${isWishlisted}"
                        title="${isWishlisted ? "Hapus dari Wishlist" : "Tambah ke Wishlist"}"
                        class="absolute top-3 right-3 w-10 h-10 bg-white/95 backdrop-blur rounded-full flex items-center justify-center shadow-md transition-all duration-200 active:scale-90 hover:scale-110 ${isWishlisted ? "text-red-500 ring-2 ring-red-100" : "text-slate-500 hover:text-red-500"}">
                        <i class="${isWishlisted ? "fa-solid fa-heart text-red-500" : "fa-regular fa-heart"} text-sm"></i>
                    </button>

                    <!-- DETAIL OVERLAY -->
                    <div class="absolute inset-0 bg-slate-900/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center p-4">
                        <button
                            type="button"
                            onclick="openProductModal(${product.id})"
                            class="bg-white text-slate-800 text-xs font-bold px-4 py-2.5 rounded-xl shadow-lg transform translate-y-2 group-hover:translate-y-0 transition-all hover:bg-emerald-600 hover:text-white">
                            <i class="fa-solid fa-eye mr-1"></i> Lihat Detail
                        </button>
                    </div>
                </div>

                <!-- PRODUCT INFO -->
                <div class="p-4 flex-1 flex flex-col justify-between">
                    <div>
                        <!-- RATING -->
                        <div class="flex items-center gap-1 text-xs text-amber-500 font-semibold mb-1">
                            <i class="fa-solid fa-star"></i>
                            <span>${product.rating}</span>
                            <span class="text-slate-400 font-normal">(${product.reviews})</span>
                        </div>

                        <!-- NAME -->
                        <h3
                            onclick="openProductModal(${product.id})"
                            class="font-bold text-slate-800 text-sm leading-snug line-clamp-2 hover:text-emerald-600 cursor-pointer transition-colors mb-2">
                            ${product.name}
                        </h3>
                    </div>

                    <!-- PRICE -->
                    <div class="mt-2 pt-2 border-t border-slate-100">
                        <div class="flex items-baseline gap-2 mb-3">
                            <span class="text-base font-extrabold text-slate-900">
                                ${formatIDR(product.price)}
                            </span>
                            ${product.oldPrice ? `
                                <span class="text-xs text-slate-400 line-through">
                                    ${formatIDR(product.oldPrice)}
                                </span>
                            ` : ""}
                        </div>

                        <!-- ACTION BUTTONS -->
                        <div class="flex gap-2">
                            <!-- KERANJANG -->
                            <button
                                type="button"
                                onclick="addToCart(${product.id})"
                                ${isOutOfStock ? "disabled" : ""}
                                class="flex-1 bg-slate-900 hover:bg-slate-700 disabled:bg-slate-300 disabled:cursor-not-allowed text-white font-semibold text-[11px] py-2.5 rounded-xl transition-all duration-200 flex items-center justify-center gap-1.5 shadow-sm active:scale-95">
                                <i class="fa-solid fa-cart-plus"></i>
                                <span>Keranjang</span>
                            </button>

                            <!-- BELI SEKARANG -->
                            <button
                                type="button"
                                onclick="buyNow(${product.id})"
                                ${isOutOfStock ? "disabled" : ""}
                                class="flex-1 bg-emerald-600 hover:bg-emerald-700 disabled:bg-slate-300 disabled:cursor-not-allowed text-white font-semibold text-[11px] py-2.5 rounded-xl transition-all duration-200 flex items-center justify-center gap-1.5 shadow-sm active:scale-95">
                                <i class="fa-solid fa-bolt"></i>
                                <span>Beli Sekarang</span>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        `;
    }).join("");
}


/* =========================================================
   PRODUCT DETAIL MODAL
========================================================= */

function openProductModal(id) {
    const product = getProduct(id);
    if (!product) return;

    const modal = document.getElementById("productModal");
    const backdrop = document.getElementById("productModalBackdrop");
    const content = document.getElementById("productModalContent");
    const body = document.getElementById("productModalBody");

    if (!modal || !backdrop || !content || !body) return;

    const isWishlisted = wishlist.includes(product.id);

    body.innerHTML = `
        <div class="bg-slate-100 rounded-xl overflow-hidden aspect-square">
            <img
                src="${product.image}"
                alt="${product.name}"
                class="w-full h-full object-cover"
                onerror="this.src='https://placehold.co/500x500/e2e8f0/475569?text=Produk'"
            >
        </div>

        <div class="flex flex-col justify-between">
            <div class="space-y-3">
                <span class="inline-block text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200">
                    ${product.category}
                </span>

                <h2 class="text-xl font-extrabold text-slate-800 leading-tight">
                    ${product.name}
                </h2>

                <div class="flex items-center gap-2 text-sm text-amber-500 font-bold">
                    <i class="fa-solid fa-star"></i>
                    <span>${product.rating}</span>
                    <span class="text-slate-400 font-normal">(${product.reviews} ulasan)</span>
                </div>

                <div class="flex items-baseline gap-3 py-2 border-y border-slate-100">
                    <span class="text-2xl font-black text-slate-900">
                        ${formatIDR(product.price)}
                    </span>
                    ${product.oldPrice ? `
                        <span class="text-sm text-slate-400 line-through">
                            ${formatIDR(product.oldPrice)}
                        </span>
                    ` : ""}
                </div>

                <p class="text-xs text-slate-600 leading-relaxed">
                    ${product.description}
                </p>

                <div class="text-xs text-slate-500 font-medium">
                    <i class="fa-solid fa-box mr-1"></i> Stok Tersedia:
                    <span class="font-bold text-slate-800">${product.stock} pcs</span>
                </div>
            </div>

            <div class="pt-4 mt-4 border-t border-slate-100 space-y-2">
                <!-- WISHLIST -->
                <button
                    type="button"
                    onclick="toggleWishlist(${product.id}); openProductModal(${product.id})"
                    class="w-full ${isWishlisted ? "bg-red-50 text-red-500 border-red-200" : "bg-white text-slate-700 border-slate-200"} border font-bold py-3 rounded-xl transition-all flex items-center justify-center gap-2">
                    <i class="${isWishlisted ? "fa-solid fa-heart" : "fa-regular fa-heart"}"></i>
                    ${isWishlisted ? "Hapus dari Wishlist" : "Tambah ke Wishlist"}
                </button>

                <!-- ACTIONS -->
                <div class="flex gap-2">
                    <!-- KERANJANG -->
                    <button
                        type="button"
                        onclick="addToCart(${product.id}); closeProductModal();"
                        class="flex-1 bg-slate-900 hover:bg-slate-700 text-white font-bold py-3 rounded-xl transition-all flex items-center justify-center gap-2">
                        <i class="fa-solid fa-cart-plus"></i> Keranjang
                    </button>

                    <!-- BELI SEKARANG -->
                    <button
                        type="button"
                        onclick="buyNow(${product.id})"
                        class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-xl shadow-lg shadow-emerald-600/30 transition-all flex items-center justify-center gap-2">
                        <i class="fa-solid fa-bolt"></i> Beli Sekarang
                    </button>
                </div>
            </div>
        </div>
    `;

    modal.classList.remove("invisible");

    setTimeout(() => {
        backdrop.classList.add("opacity-100");
        content.classList.remove("opacity-0", "scale-95");
    }, 10);
}

function closeProductModal() {
    const modal = document.getElementById("productModal");
    const backdrop = document.getElementById("productModalBackdrop");
    const content = document.getElementById("productModalContent");

    if (!modal || !backdrop || !content) return;

    backdrop.classList.remove("opacity-100");
    content.classList.add("opacity-0", "scale-95");

    setTimeout(() => {
        modal.classList.add("invisible");
    }, 300);
}


/* =========================================================
   BELI SEKARANG & CHECKOUT
========================================================= */

function buyNow(productId) {
    // 1. Cek Login
    if (typeof isLoggedIn !== 'undefined' && !isLoggedIn) {
        window.location.href = 'views/auth/login.php';
        return;
    }

    const product = getProduct(productId);

    if (!product) {
        if (typeof showToast === 'function') showToast("Produk tidak ditemukan", "info");
        return;
    }

    // 2. Cek Stok
    if (Number(product.stock) <= 0) {
        if (typeof showToast === 'function') showToast("Produk sedang habis", "info");
        return;
    }

    // 3. Format item checkout
    const checkoutItem = {
        id: Number(product.id),
        name: product.name,
        price: Number(product.price),
        image: product.image || '',
        qty: 1,
        quantity: 1
    };

    // Simpan di LocalStorage
    localStorage.setItem('checkout_cart', JSON.stringify([checkoutItem]));
    localStorage.setItem('checkout_mode', 'buy_now');

    // Tutup modal jika terbuka
    const productModal = document.getElementById('productModal');
    if (productModal && !productModal.classList.contains('invisible') && typeof closeProductModal === 'function') {
        closeProductModal();
    }

    // Path yang sesuai menuju halaman checkout
    const checkoutUrl = "/webdesign/midtrans/midtrans-php-native/vendor/veritrans/veritrans-php/examples/snap/checkout-process-simple-version.php";

    // 4. Kirim ke PHP session via fetch sebelum pindah halaman
    fetch('save_cart_session.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ cart: [checkoutItem] })
    })
    .then(res => res.json())
    .then(() => {
        window.location.href = checkoutUrl;
    })
    .catch(err => {
        console.error('Error saving cart session:', err);
        // Fallback tetap redirect
        window.location.href = checkoutUrl;
    });
}


/* =========================================================
   CHECKOUT DARI KERANJANG
========================================================= */

function checkoutCart() {
    // 1. Cek Login
    if (typeof isLoggedIn !== 'undefined' && !isLoggedIn) {
        window.location.href = 'views/auth/login.php';
        return;
    }

    let cartItems = [];

    // Ambil data keranjang
    if (typeof cart !== 'undefined' && Array.isArray(cart) && cart.length > 0) {
        cartItems = cart;
    } else {
        try {
            cartItems = JSON.parse(localStorage.getItem('cart')) || [];
        } catch (error) {
            cartItems = [];
        }
    }

    // Jika keranjang kosong
    if (!cartItems || cartItems.length === 0) {
        if (typeof showToast === 'function') {
            showToast("Masukkan produk ke keranjang terlebih dahulu", "info");
        }
        return;
    }

    // Standarisasi format data checkout
    const checkoutItems = cartItems.map(item => ({
        id: Number(item.id),
        name: item.name,
        price: Number(item.price),
        image: item.image || '',
        qty: Number(item.qty || item.quantity || 1),
        quantity: Number(item.qty || item.quantity || 1)
    }));

    // Simpan ke LocalStorage
    localStorage.setItem('checkout_cart', JSON.stringify(checkoutItems));
    localStorage.setItem('checkout_mode', 'cart');

    const checkoutUrl = "/webdesign/midtrans/midtrans-php-native/vendor/veritrans/veritrans-php/examples/snap/checkout-process-simple-version.php";

    // Simpan ke PHP session via fetch sebelum redirect
    fetch('save_cart_session.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ cart: checkoutItems })
    })
    .then(res => res.json())
    .then(() => {
        window.location.href = checkoutUrl;
    })
    .catch(err => {
        console.error('Error saving cart session:', err);
        window.location.href = checkoutUrl;
    });
}


/* =========================================================
   GLOBAL
========================================================= */

window.buyNow = buyNow;
window.checkoutCart = checkoutCart;