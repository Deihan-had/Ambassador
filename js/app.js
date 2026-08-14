document.addEventListener("DOMContentLoaded", () => {

    loadStateFromLocalStorage();

    renderCategories();
    renderProducts();

    updateBadges();
    startCountdown();

});