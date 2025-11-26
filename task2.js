document.addEventListener("DOMContentLoaded", function () {
    
    const darkBtn = document.getElementById("darkToggleBtn");

    if (localStorage.getItem("darkMode") === "enabled") {
        document.body.classList.add("dark-mode");
    }

    if (darkBtn) {
        darkBtn.addEventListener("click", function () {
            document.body.classList.toggle("dark-mode");

            if (document.body.classList.contains("dark-mode")) {
                localStorage.setItem("darkMode", "enabled");
            } else {
                localStorage.setItem("darkMode", "disabled");
            }
        });
    }
});

function updateLayout() {
    const productList = document.querySelector(".product-list");

    if (!productList) return;

    if (window.innerWidth <= 768) {
        productList.classList.add("vertical-layout");
        productList.classList.remove("horizontal-layout");
    } else {
        productList.classList.add("horizontal-layout");
        productList.classList.remove("vertical-layout");
    }
}

updateLayout();

window.addEventListener("resize", updateLayout);