document.addEventListener("DOMContentLoaded", function () {

    const suggestBtn = document.getElementById("suggestBtn");
    const output = document.getElementById("suggestionOutput");

    if (!suggestBtn || !output) return;

    const products = [
        "Owl Hoodie",
        "Brain Power Hoodie",
        "wow Shirt",
        "Relationship Status Shirt",
        "Plans with Cat Shirt",
        "What I am doing Shirt",
        "Pikachu Hoodie",
        "Stressed Hoodie"

    ];

    suggestBtn.addEventListener("click", function () {

        let randomIndex = Math.floor(Math.random() * products.length);

        let randomProduct = products[randomIndex];

        output.textContent = "You may also like: " + randomProduct;
    });
});
