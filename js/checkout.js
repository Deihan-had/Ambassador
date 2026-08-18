// Variabel global sementara untuk menyimpan produk yang baru saja dipesan
let pendingCheckoutCart = [];

// Path halaman checkout Midtrans
const MIDTRANS_CHECKOUT_PATH = "midtrans/midtrans-php-native/vendor/veritrans/veritrans-php/examples/snap/checkout-process-simple-version.php";

// Pada fungsi handlePlaceOrder:
fetch('save_cart_session.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ cart: pendingCheckoutCart })
})

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
        const cartModal = document.getElementById("cartModal");
        if (cartModal && !cartModal.classList.contains("invisible")) {
            toggleCartModal();
        }
    } else {
        // Pastikan cart modal tertutup
        const cartModal = document.getElementById("cartModal");
        if (cartModal && !cartModal.classList.contains("invisible")) {
            toggleCartModal();
        }
    }

    const subtotal = cart.reduce(
        (sum, item) => sum + item.price * item.qty,
        0
    );

    const discountAmount = (subtotal * appliedDiscount) / 100;
    const finalTotal = subtotal - discountAmount;

    const checkoutTotal = document.getElementById("checkoutTotal");
    if (checkoutTotal) checkoutTotal.textContent = formatIDR(finalTotal);

    const modal = document.getElementById("checkoutModal");
    const backdrop = document.getElementById("checkoutBackdrop");
    const panel = document.getElementById("checkoutPanel");

    if (!modal || !backdrop || !panel) return;

    modal.classList.remove("invisible");

    setTimeout(() => {
        backdrop.classList.add("opacity-100");
        panel.classList.remove("opacity-0", "scale-95");
    }, 10);
}

function closeCheckout() {
    const modal = document.getElementById("checkoutModal");
    const backdrop = document.getElementById("checkoutBackdrop");
    const panel = document.getElementById("checkoutPanel");

    if (!modal || !backdrop || !panel) return;

    backdrop.classList.remove("opacity-100");
    panel.classList.add("opacity-0", "scale-95");

    setTimeout(() => {
        modal.classList.add("invisible");
    }, 300);
}

/* =========================================================
   PLACE ORDER
========================================================= */

function handlePlaceOrder(e) {
    e.preventDefault();

    // 1. Simpan produk yang sedang di-checkout
    pendingCheckoutCart = JSON.parse(JSON.stringify(cart));

    if (pendingCheckoutCart.length === 0) {
        if (typeof showToast === 'function') showToast("Keranjang belanja kosong", "info");
        return;
    }

    // 2. Kirim data keranjang ke PHP Session
    fetch('save_cart_session.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ cart: pendingCheckoutCart })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // HANYA PINDAH HALAMAN SETELAH SESSION BERHASIL DISIMPAN!
            if (isBuyNowCheckout) {
                cart = previousCartBeforeBuyNow ? [...previousCartBeforeBuyNow] : [];
                previousCartBeforeBuyNow = null;
                isBuyNowCheckout = false;
            } else {
                cart = [];
            }

            appliedDiscount = 0;

            if (typeof saveState === 'function') saveState();
            if (typeof updateBadges === 'function') updateBadges();
            closeCheckout();

            // Redirect HANYA di dalam blok ini
            window.location.href = MIDTRANS_CHECKOUT_PATH;
        } else {
            alert("Gagal menyimpan keranjang ke session: " + (data.message || ""));
        }
    })
    .catch(err => {
        console.error('Checkout error:', err);
        alert("Terjadi kesalahan koneksi saat menyiapkan pembayaran.");
    });
}

function closeSuccessModal() {
    const modal = document.getElementById("successModal");
    if (modal) modal.classList.add("invisible");
}