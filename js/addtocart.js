async function addToCart(productId, quantity = 1) {
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

    try {
        const formData = new FormData();

        formData.append("id_produk", productId);
        formData.append("jumlah", quantity);

        const response = await fetch("proses/add-to-cart.php", {
            method: "POST",
            body: formData
        });

        const result = await response.json();

        if (result.login) {
            window.location.href = "login.php";
            return;
        }

        if (!result.success) {
            showToast(result.message, "info");
            return;
        }

        showToast(result.message, "cart");

    } catch (error) {
        console.error(error);
        showToast("Terjadi kesalahan", "info");
    }
}