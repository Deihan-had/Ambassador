let activeCategory = "All";
let searchQuery = "";
let currentSort = "featured";

let cart = [];
let wishlist = [];

let appliedDiscount = 0;

/* Buy Now state (dipakai oleh cart.js & checkout.js) */
let previousCartBeforeBuyNow = null;
let isBuyNowCheckout = false;


/* =========================================================
   LOCAL STORAGE
========================================================= */

function saveState() {

    try {

        localStorage.setItem(
            "nusa_cart",
            JSON.stringify(cart)
        );


        localStorage.setItem(
            "nusa_wishlist",
            JSON.stringify(wishlist)
        );

    }

    catch (error) {

        console.error(
            "Local storage error:",
            error
        );

    }

}


function loadStateFromLocalStorage() {

    try {

        /* CART */

        const savedCart =
            localStorage.getItem(
                "nusa_cart"
            );


        if (savedCart) {

            const parsedCart =
                JSON.parse(
                    savedCart
                );


            if (
                Array.isArray(
                    parsedCart
                )
            ) {

                cart =
                    parsedCart

                        .filter(item =>
                            item &&
                            Number.isFinite(
                                Number(item.id)
                            )
                        )

                        .map(item => {

                            const product =
                                getProduct(
                                    Number(item.id)
                                );


                            if (!product)
                                return null;


                            let qty =
                                Number(item.qty);


                            if (
                                !Number.isFinite(qty) ||
                                qty <= 0
                            ) {

                                qty = 1;

                            }


                            qty =
                                Math.min(
                                    qty,
                                    product.stock
                                );


                            return {
                                ...product,
                                qty
                            };

                        })

                        .filter(Boolean);

            }

        }


        /* WISHLIST */

        const savedWishlist =
            localStorage.getItem(
                "nusa_wishlist"
            );


        if (savedWishlist) {

            const parsedWishlist =
                JSON.parse(
                    savedWishlist
                );


            if (
                Array.isArray(
                    parsedWishlist
                )
            ) {

                wishlist =
                    [
                        ...new Set(

                            parsedWishlist

                                .map(Number)

                                .filter(id =>
                                    products.some(
                                        product =>
                                            product.id === id
                                    )
                                )

                        )
                    ];

            }

        }

    }

    catch (error) {

        console.error(
            "Local storage load error:",
            error
        );


        cart = [];
        wishlist = [];

    }

}