/*
|--------------------------------------------------------------------------
| DATA PRODUK DARI DATABASE
|--------------------------------------------------------------------------
*/

let products = [];
let categories = ["All"];
let catalogLoaded = false;

/*
|--------------------------------------------------------------------------
| LOAD CATALOG DATABASE
|--------------------------------------------------------------------------
*/

async function loadCatalogFromDatabase() {
    try {
        const apiUrl = new URL("api/catalog.php", document.baseURI).href;
        console.log("Mengambil catalog dari:", apiUrl);

        const response = await fetch(apiUrl, {
            method: "GET",
            cache: "no-store",
            headers: {
                "Accept": "application/json"
            }
        });

        if (!response.ok) {
            throw new Error("catalog.php HTTP " + response.status);
        }

        const data = await response.json();
        console.log("Response catalog:", data);

        if (!data.success) {
            throw new Error(data.message || "API catalog gagal.");
        }

        /*
        |--------------------------------------------------------------------------
        | PRODUK
        |--------------------------------------------------------------------------
        */

        products = Array.isArray(data.products)
            ? data.products.map(product => {
                const id = Number(product.id_produk ?? product.id ?? 0);
                const nama = product.nama ?? product.name ?? "Produk";
                const kategori = product.kategori ?? product.category ?? "Tanpa Kategori";
                const harga = Number(product.harga ?? product.price ?? 0);
                const stok = Number(product.stok ?? product.stock ?? 0);
                const foto = product.foto ?? product.image ?? "";
                const detail = product.detail ?? product.description ?? "";

                return {
                    id: id,
                    id_produk: id,
                    name: nama,
                    nama: nama,
                    category: kategori,
                    kategori: kategori,
                    kategori_id: Number(product.kategori_id ?? 0),
                    price: harga,
                    harga: harga,
                    oldPrice: Number(product.price_was ?? harga),
                    price_was: Number(product.price_was ?? harga),
                    image: foto,
                    foto: foto,
                    description: detail,
                    detail: detail,
                    stock: stok,
                    stok: stok,
                    rating: Number(product.rating ?? 0),
                    reviews: Number(product.reviews ?? 0),
                    sold: Number(product.sold ?? 0),
                    badge: product.badge ?? (stok > 0 ? "Tersedia" : "Habis"),
                    status: product.status ?? (stok > 0 ? "Aktif" : "Habis"),
                    sku: product.sku ?? "PROD-" + id,
                    initials: product.initials ?? nama.trim().split(/\s+/).slice(0, 2).map(x => x[0]).join("").toUpperCase()
                };
            })
            : [];

        /*
        |--------------------------------------------------------------------------
        | KATEGORI
        |--------------------------------------------------------------------------
        */

        categories = ["All"];

        if (Array.isArray(data.categories)) {
            data.categories.forEach(category => {
                let name = "";

                if (typeof category === "object" && category !== null) {
                    name = category.name ?? category.nama ?? "";
                } else {
                    name = String(category);
                }

                name = name.trim();

                if (name !== "" && name.toLowerCase() !== "all") {
                    const alreadyExists = categories.some(
                        item => item.toLowerCase() === name.toLowerCase()
                    );

                    if (!alreadyExists) {
                        categories.push(name);
                    }
                }
            });
        }

        /*
        |--------------------------------------------------------------------------
        | STATUS & LOG
        |--------------------------------------------------------------------------
        */

        catalogLoaded = true;

        console.log("================================");
        console.log("CATALOG DATABASE BERHASIL");
        console.log("Produk:", products.length);
        console.log("Kategori:", categories);
        console.log("================================");

        /*
        |--------------------------------------------------------------------------
        | RENDER
        |--------------------------------------------------------------------------
        */

        if (typeof renderCategories === "function") {
            renderCategories();
        }

        if (typeof renderProducts === "function") {
            renderProducts();
        }

        if (typeof updateProductCount === "function") {
            updateProductCount();
        }

        /*
        |--------------------------------------------------------------------------
        | EVENT
        |--------------------------------------------------------------------------
        */

        document.dispatchEvent(
            new CustomEvent("catalogLoaded", {
                detail: {
                    products: products,
                    categories: categories
                }
            })
        );

    } catch (error) {
        console.error("================================");
        console.error("GAGAL LOAD CATALOG DATABASE");
        console.error(error);
        console.error("================================");

        products = [];
        categories = ["All"];
        catalogLoaded = false;
    }
}

/*
|--------------------------------------------------------------------------
| START
|--------------------------------------------------------------------------
*/

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", loadCatalogFromDatabase);
} else {
    loadCatalogFromDatabase();
}