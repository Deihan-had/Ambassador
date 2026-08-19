(function () {
    // Inisialisasi variabel global di window agar aman dari deklarasi ulang
    window.CART_KEY = window.CART_KEY || "cart";
    window.cart = window.cart || [];
    window.appliedDiscount = window.appliedDiscount || 0;
    window.previousCartBeforeBuyNow = window.previousCartBeforeBuyNow || [];
    window.isBuyNowCheckout = window.isBuyNowCheckout || false;

    // --- HELPER & STORAGE ---

    function getCart() {
        try {
            return JSON.parse(localStorage.getItem(window.CART_KEY)) || [];
        } catch (e) {
            return [];
        }
    }

    function saveState() {
        localStorage.setItem(window.CART_KEY, JSON.stringify(window.cart));
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
        const totalQty = window.cart.reduce((sum, item) => sum + (Number(item.qty || item.quantity) || 0), 0);
        
        const ids = ['cartCount', 'cartHeaderBadge', 'cart-badge'];
        ids.forEach(id => {
            const el = document.getElementById(id);
            if (el) el.textContent = totalQty;
        });

        document.querySelectorAll('.cart-badge-count').forEach(el => {
            el.textContent = totalQty;
        });
    }

    function getProduct(productId) {
        const productList = typeof products !== 'undefined' ? products : window.products;

        if (!Array.isArray(productList)) {
            console.error('Variabel global `products` belum dimuat atau bukan berupa Array.');
            return null;
        }

        return productList.find(p => String(p.id) === String(productId)) || null;
    }

    function showToast(message, type = 'info') {
        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toastMessage');
        
        if (!toast) {
            console.log(`[Toast ${type}]: ${message}`);
            return;
        }
        
        if (toastMessage) {
            toastMessage.textContent = message;
        } else {
            toast.textContent = message;
        }
        
        toast.classList.remove('hidden');
        setTimeout(() => toast.classList.add('hidden'), 3000);
    }

    // --- OPERASI KERANJANG BELANJA ---

    function addToCart(productId, quantity = 1) {
        if (typeof isLoggedIn !== 'undefined' && !isLoggedIn) {
            window.location.href = 'views/auth/login.php';
            return;
        }

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

        const existingIndex = window.cart.findIndex(item => String(item.id) === String(product.id));
        const currentQty = existingIndex > -1 ? Number(window.cart[existingIndex].qty || window.cart[existingIndex].quantity || 0) : 0;
        const nextQty = currentQty + quantity;

        if (nextQty > product.stock) {
            showToast(`Stok ${product.name} hanya ${product.stock} pcs`, "info");
            return;
        }

        if (existingIndex > -1) {
            window.cart[existingIndex].qty = nextQty;
            window.cart[existingIndex].quantity = nextQty;
        } else {
            window.cart.push({
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
        const index = window.cart.findIndex(item => String(item.id) === String(productId));
        if (index === -1) return;

        const product = getProduct(productId);
        let currentQty = Number(window.cart[index].qty || window.cart[index].quantity || 0);
        let newQty = currentQty + Number(change);

        if (product && newQty > product.stock) {
            newQty = product.stock;
            showToast(`Maksimal stok ${product.stock} pcs`, "info");
        }

        if (newQty <= 0) {
            window.cart.splice(index, 1);
        } else {
            window.cart[index].qty = newQty;
            window.cart[index].quantity = newQty;
        }

        saveState();
        renderCartModal();
    }

    function removeFromCart(productId) {
        window.cart = window.cart.filter(item => String(item.id) !== String(productId));
        saveState();
        renderCartModal();
        showToast("Item dihapus dari keranjang", "info");
    }

    // --- UI MODAL KERANJANG ---

    function toggleCartModal() {
        if (typeof isLoggedIn !== 'undefined' && !isLoggedIn) {
            window.location.href = 'views/auth/login.php';
            return;
        }

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

        if (window.cart.length === 0) {
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

            updateBadges();
            return;
        }

        if (checkoutBtn) checkoutBtn.disabled = false;

        let subtotal = 0;

        container.innerHTML = window.cart.map(item => {
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

        const discountAmount = (subtotal * window.appliedDiscount) / 100;
        const finalTotal = subtotal - discountAmount;

        if (subtotalEl) subtotalEl.textContent = formatIDR(subtotal);
        if (discountEl) discountEl.textContent = `- ${formatIDR(discountAmount)}`;
        if (totalEl) totalEl.textContent = formatIDR(finalTotal);

        updateBadges();
    }

    // --- FITUR PROMO ---

    function applyPromoCode() {
        const input = document.getElementById("promoInput");
        const msg = document.getElementById("promoMessage");
        if (!input || !msg) return;

        const code = input.value.trim().toUpperCase();

        if (code === "DISCOUNT10" || code === "AMBASDISKON") {
            window.appliedDiscount = 10;
            msg.textContent = "Kode promo berhasil dipasang! (Diskon 10%)";
            msg.className = "text-xs font-semibold text-emerald-600 block";
            renderCartModal();
        } else {
            window.appliedDiscount = 0;
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

    // --- BELI SEKARANG (BUY NOW) ---

    function buyNow(productId) {
        if (typeof isLoggedIn !== 'undefined' && !isLoggedIn) {
            window.location.href = 'views/auth/login.php';
            return;
        }

        const product = getProduct(productId);
        if (!product) {
            showToast("Produk tidak ditemukan", "info");
            return;
        }

        if (product.stock <= 0) {
            showToast("Produk sedang habis", "info");
            return;
        }

        window.previousCartBeforeBuyNow = [...window.cart];
        window.isBuyNowCheckout = true;

        window.cart = [{
            id: product.id,
            name: product.name,
            price: product.price,
            image: product.image,
            qty: 1,
            quantity: 1
        }];

        window.appliedDiscount = 0;
        saveState();

        const productModal = document.getElementById("productModal");
        if (productModal && !productModal.classList.contains("invisible") && typeof closeProductModal === 'function') {
            closeProductModal();
        }

        goToCheckout();
    }

    // --- BRIDGE KE CHECKOUT PHP ---

    function goToCheckout() {
        if (typeof isLoggedIn !== 'undefined' && !isLoggedIn) {
            window.location.href = 'views/auth/login.php';
            return;
        }

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
                window.location.href = '/webdesign/midtrans/midtrans-php-native/vendor/veritrans/veritrans-php/examples/snap/checkout-process-simple-version.php';
            } else {
                alert('Gagal menyiapkan checkout. Silakan coba lagi.');
                if (payButton) {
                    payButton.disabled = false;
                    payButton.innerText = 'Proses Pembayaran';
                }
            }
        })
        .catch(err => {
            console.error('Checkout error:', err);
            alert('Terjadi kesalahan koneksi. Periksa jaringan kamu.');
            if (payButton) {
                payButton.disabled = false;
                payButton.innerText = 'Proses Pembayaran';
            }
        });
    }

    // Ekspor fungsi ke objek global window
    window.addToCart = addToCart;
    window.updateCartQty = updateCartQty;
    window.removeFromCart = removeFromCart;
    window.toggleCartModal = toggleCartModal;
    window.renderCartModal = renderCartModal;
    window.applyPromoCode = applyPromoCode;
    window.applyPromoQuick = applyPromoQuick;
    window.buyNow = buyNow;
    window.goToCheckout = goToCheckout;

    // Inisialisasi saat dokumen siap
    document.addEventListener('DOMContentLoaded', () => {
        window.cart = getCart();
        updateBadges();
    });
})();