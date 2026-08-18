/**
 * cart.js
 * Penggabungan Manajemen Keranjang Belanja, UI Modal, Promo, 
 * serta Bridge Checkout ke PHP Session.
 */

const CART_KEY = 'cart';

// State Global Keranjang
let cart = getCart();
let appliedDiscount = 0;
let previousCartBeforeBuyNow = [];
let isBuyNowCheckout = false;

// ---------- Helper & Helper Storage ----------

function getCart() {
    try {
        return JSON.parse(localStorage.getItem(CART_KEY)) || [];
    } catch (e) {
        return [];
    }
}

function saveState() {
    localStorage.setItem(CART_KEY, JSON.stringify(cart));
    updateBadges();
}

function formatIDR(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0
    }).format(amount);
}

function updateBadges() {
    const totalQty = cart.reduce((sum, item) => sum + (Number(item.qty || item.quantity) || 0), 0);
    
    // Update badge berbasis ID
    const badge = document.getElementById('cart-badge');
    if (badge) badge.textContent = totalQty;

    // Update semua elemen dengan selector badge jika ada
    document.querySelectorAll('.cart-badge-count').forEach(el => {
        el.textContent = totalQty;
    });
}

function getProduct(productId) {
    if (typeof products !== 'undefined' && Array.isArray(products)) {
        return products.find(p => p.id === productId);
    }
    console.error('Variabel global `products` belum dimuat.');
    return null;
}

function showToast(message, type = 'info') {
    const toast = document.getElementById('toast');
    if (!toast) {
        console.log(`[Toast ${type}]: ${message}`);
        return;
    }
    toast.textContent = message;
    toast.className = `fixed bottom-4 right-4 px-4 py-2 rounded-lg text-white text-xs font-bold z-50 transition-all ${
        type === 'cart' ? 'bg-emerald-600' : 'bg-slate-800'
    }`;
    toast.classList.remove('hidden');
    setTimeout(() => toast.classList.add('hidden'), 3000);
}

// ---------- Operasi Keranjang Belanja ----------

function addToCart(productId, quantity = 1) {
    const product = getProduct(productId);

    if (!product) {
        showToast("Produk tidak ditemukan", "info");
        return;
    }

    if (product.stock <= 0) {
        showToast("Produk sedang habis", "info");
        return;
    }

    quantity = Number(quantity);
    if (!Number.isFinite(quantity) || quantity <= 0) {
        quantity = 1;
    }

    const existingIndex = cart.findIndex(item => item.id === product.id);
    const currentQty = existingIndex > -1 ? Number(cart[existingIndex].qty || cart[existingIndex].quantity || 0) : 0;
    const nextQty = currentQty + quantity;

    if (nextQty > product.stock) {
        showToast(`Stok ${product.name} hanya ${product.stock} pcs`, "info");
        return;
    }

    if (existingIndex > -1) {
        cart[existingIndex].qty = nextQty;
        cart[existingIndex].quantity = nextQty;
    } else {
        cart.push({
            id: product.id,
            name: product.name,
            price: product.price,
            image: product.image,
            qty: quantity,
            quantity: quantity
        });
    }

    saveState();
    showToast(`${product.name} ditambahkan ke keranjang`, "cart");
}

function updateCartQty(productId, change) {
    const index = cart.findIndex(item => item.id === productId);
    if (index === -1) return;

    const product = getProduct(productId);
    let currentQty = Number(cart[index].qty || cart[index].quantity || 0);
    let newQty = currentQty + Number(change);

    if (product && newQty > product.stock) {
        newQty = product.stock;
        showToast(`Maksimal stok ${product.stock} pcs`, "info");
    }

    if (newQty <= 0) {
        cart.splice(index, 1);
    } else {
        cart[index].qty = newQty;
        cart[index].quantity = newQty;
    }

    saveState();
    renderCartModal();
}

function removeFromCart(productId) {
    cart = cart.filter(item => item.id !== productId);
    saveState();
    renderCartModal();
    showToast("Item dihapus dari keranjang", "info");
}

// ---------- UI Modal Keranjang ----------

function toggleCartModal() {
    const modal = document.getElementById("cartModal");
    const backdrop = document.getElementById("cartBackdrop");
    const panel = document.getElementById("cartPanel");

    if (!modal || !backdrop || !panel) return;

    if (modal.classList.contains("invisible")) {
        renderCartModal();
        modal.classList.remove("invisible");
        setTimeout(() => {
            backdrop.classList.add("opacity-100");
            panel.classList.remove("translate-x-full");
        }, 10);
    } else {
        backdrop.classList.remove("opacity-100");
        panel.classList.add("translate-x-full");
        setTimeout(() => {
            modal.classList.add("invisible");
        }, 300);
    }
}

function renderCartModal() {
    const container = document.getElementById("cartItemsContainer");
    const subtotalEl = document.getElementById("cartSubtotal");
    const discountEl = document.getElementById("cartDiscount");
    const totalEl = document.getElementById("cartTotal");
    const checkoutBtn = document.getElementById("checkoutBtn") || document.getElementById("checkout-button");

    if (!container) return;

    if (cart.length === 0) {
        container.innerHTML = `
            <div class="text-center py-12">
                <i class="fa-solid fa-basket-shopping text-4xl text-slate-300 mb-3"></i>
                <p class="text-slate-500 text-sm font-medium">Keranjang Anda masih kosong</p>
            </div>
        `;

        if (subtotalEl) subtotalEl.textContent = "Rp 0";
        if (discountEl) discountEl.textContent = "- Rp 0";
        if (totalEl) totalEl.textContent = "Rp 0";
        if (checkoutBtn) checkoutBtn.disabled = true;
        return;
    }

    if (checkoutBtn) checkoutBtn.disabled = false;

    let subtotal = 0;

    container.innerHTML = cart.map(item => {
        const itemQty = item.qty || item.quantity || 1;
        const itemTotal = item.price * itemQty;
        subtotal += itemTotal;

        return `
            <div class="flex gap-3 bg-white p-3 rounded-xl border border-slate-200/80 shadow-xs">
                <img src="${item.image}" alt="${item.name}" class="w-16 h-16 object-cover rounded-lg bg-slate-100" onerror="this.src='https://placehold.co/100x100/e2e8f0/475569?text=Produk'">
                <div class="flex-1 min-w-0 flex flex-col justify-between">
                    <div>
                        <h4 class="text-xs font-bold text-slate-800 truncate">${item.name}</h4>
                        <span class="text-xs font-semibold text-emerald-600">${formatIDR(item.price)}</span>
                    </div>
                    <div class="flex items-center justify-between pt-1">
                        <div class="flex items-center border border-slate-200 rounded-lg overflow-hidden bg-slate-50">
                            <button type="button" onclick="updateCartQty('${item.id}', -1)" class="px-2 py-0.5 text-xs text-slate-600 hover:bg-slate-200">-</button>
                            <span class="px-2 text-xs font-bold">${itemQty}</span>
                            <button type="button" onclick="updateCartQty('${item.id}', 1)" class="px-2 py-0.5 text-xs text-slate-600 hover:bg-slate-200">+</button>
                        </div>
                        <button type="button" onclick="removeFromCart('${item.id}')" class="text-slate-400 hover:text-red-500 text-xs">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
    }).join("");

    const discountAmount = (subtotal * appliedDiscount) / 100;
    const finalTotal = subtotal - discountAmount;

    if (subtotalEl) subtotalEl.textContent = formatIDR(subtotal);
    if (discountEl) discountEl.textContent = `- ${formatIDR(discountAmount)}`;
    if (totalEl) totalEl.textContent = formatIDR(finalTotal);
}

// ---------- Fitur Promo ----------

function applyPromoCode() {
    const input = document.getElementById("promoInput");
    const msg = document.getElementById("promoMessage");
    if (!input || !msg) return;

    const code = input.value.trim().toUpperCase();

    if (code === "DISCOUNT10") {
        appliedDiscount = 10;
        msg.textContent = "Kode promo berhasil dipasang! (Diskon 10%)";
        msg.className = "text-xs font-semibold text-emerald-600 block";
        renderCartModal();
    } else {
        appliedDiscount = 0;
        msg.textContent = "Kode promo tidak valid.";
        msg.className = "text-xs font-semibold text-red-500 block";
        renderCartModal();
    }
}

function applyPromoQuick(code) {
    toggleCartModal();
    const input = document.getElementById("promoInput");
    if (input) input.value = code;
    applyPromoCode();
}

// ---------- Beli Sekarang (Buy Now) ----------

function buyNow(productId) {
    const product = getProduct(productId);

    if (!product) {
        showToast("Produk tidak ditemukan", "info");
        return;
    }

    if (product.stock <= 0) {
        showToast("Produk sedang habis", "info");
        return;
    }

    previousCartBeforeBuyNow = [...cart];
    isBuyNowCheckout = true;

    cart = [{
        id: product.id,
        name: product.name,
        price: product.price,
        image: product.image,
        qty: 1,
        quantity: 1
    }];

    appliedDiscount = 0;
    saveState();

    const productModal = document.getElementById("productModal");
    if (productModal && !productModal.classList.contains("invisible") && typeof closeProductModal === 'function') {
        closeProductModal();
    }

    goToCheckout();
}

// ---------- Bridge ke Checkout PHP ----------

function goToCheckout() {
    const activeCart = getCart();

    if (activeCart.length === 0) {
        alert('Keranjang belanja kamu masih kosong.');
        return;
    }

    const payButton = document.getElementById('checkoutBtn') || document.getElementById('checkout-button');
    if (payButton) {
        payButton.disabled = true;
        payButton.innerText = 'Memproses...';
    }

    // Standardisasi properti qty dan quantity agar PHP tidak mengalamin eror
    const formattedCart = activeCart.map(item => ({
        id: item.id,
        name: item.name,
        price: item.price,
        image: item.image,
        quantity: item.qty || item.quantity || 1
    }));

    fetch('save_cart_session.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ cart: formattedCart })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            window.location.href = 'checkout-process-simple-version.php';
        } else {
            alert('Gagal menyiapkan checkout. Silakan coba lagi.');
            if (payButton) {
                payButton.disabled = false;
                payButton.innerText = 'Checkout';
            }
        }
    })
    .catch(err => {
        console.error('Checkout error:', err);
        alert('Terjadi kesalahan koneksi. Periksa jaringan kamu.');
        if (payButton) {
            payButton.disabled = false;
            payButton.innerText = 'Checkout';
        }
    });
}

// Inisialisasi badge awal saat DOM selesai dirender
document.addEventListener('DOMContentLoaded', () => {
    updateBadges();
});