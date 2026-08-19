/* =========================================================
   UTILITIES & FORMATTER
========================================================= */

function formatIDR(amount) {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        maximumFractionDigits: 0
    }).format(amount || 0);
}

/* =========================================================
   BADGES
========================================================= */

function updateBadges() {
    // Ambil data keranjang global jika tersedia
    const currentCart = (typeof cart !== 'undefined' && Array.isArray(cart)) ? cart : [];
    const totalCartItems = currentCart.reduce(
        (sum, item) => sum + Number(item.qty || item.quantity || 0),
        0
    );

    // Update semua variasi ID dan Class Badge Keranjang yang ada di HTML
    const cartBadge = document.getElementById("cartCount");
    const cartHeaderBadge = document.getElementById("cartHeaderBadge");
    const cartBadgeAlt = document.getElementById("cart-badge");
    const cartBadgeClasses = document.querySelectorAll(".cart-badge-count");

    if (cartBadge) cartBadge.textContent = totalCartItems;
    if (cartHeaderBadge) cartHeaderBadge.textContent = totalCartItems;
    if (cartBadgeAlt) cartBadgeAlt.textContent = totalCartItems;
    cartBadgeClasses.forEach(el => el.textContent = totalCartItems);

    // Update Badge Wishlist
    const currentWishlist = (typeof wishlist !== 'undefined' && Array.isArray(wishlist)) ? wishlist : [];
    const wishlistBadge = document.getElementById("wishlistCount");

    if (wishlistBadge) {
        wishlistBadge.textContent = currentWishlist.length;
        wishlistBadge.classList.toggle("hidden", currentWishlist.length === 0);
    }
}

/* =========================================================
   TOAST NOTIFICATION
========================================================= */

let toastTimeout;

function showToast(message, iconType = "info") {
    const toast = document.getElementById("toast");
    const msgEl = document.getElementById("toastMessage");
    const iconEl = document.getElementById("toastIcon");

    if (!toast) {
        console.log(`[Toast - ${iconType}]: ${message}`);
        return;
    }

    // Isi konten pesan
    if (msgEl) {
        msgEl.textContent = message;
    } else {
        toast.textContent = message;
    }

    // Ubah ikon jika elemen ikon tersedia
    if (iconEl) {
        if (iconType === "cart") {
            iconEl.className = "fa-solid fa-cart-shopping text-emerald-400 text-lg";
        } else if (iconType === "heart") {
            iconEl.className = "fa-solid fa-heart text-red-500 text-lg";
        } else {
            iconEl.className = "fa-solid fa-circle-info text-blue-400 text-lg";
        }
    }

    toast.classList.remove("hidden");

    clearTimeout(toastTimeout);
    toastTimeout = setTimeout(() => {
        toast.classList.add("hidden");
    }, 3000);
}

/* =========================================================
   COUNTDOWN TIMER
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

        if (hours < 0) hours = 23;

        const hEl = document.getElementById("hours");
        const mEl = document.getElementById("minutes");
        const sEl = document.getElementById("seconds");

        if (hEl) hEl.textContent = String(hours).padStart(2, "0");
        if (mEl) mEl.textContent = String(minutes).padStart(2, "0");
        if (sEl) sEl.textContent = String(seconds).padStart(2, "0");
    }, 1000);
}

/* =========================================================
   KEYBOARD CONTROLLER (ESCAPE)
========================================================= */

document.addEventListener("keydown", event => {
    if (event.key !== "Escape") return;

    const productModal = document.getElementById("productModal");
    const wishlistModal = document.getElementById("wishlistModal");
    const cartModal = document.getElementById("cartModal");
    const checkoutModal = document.getElementById("checkoutModal");

    if (productModal && !productModal.classList.contains("invisible")) {
        if (typeof closeProductModal === "function") closeProductModal();
        return;
    }

    if (wishlistModal && !wishlistModal.classList.contains("invisible")) {
        if (typeof toggleWishlistModal === "function") toggleWishlistModal();
        return;
    }

    if (cartModal && !cartModal.classList.contains("invisible")) {
        if (typeof toggleCartModal === "function") toggleCartModal();
        return;
    }

    if (checkoutModal && !checkoutModal.classList.contains("invisible")) {
        if (typeof closeCheckout === "function") closeCheckout();
    }
});