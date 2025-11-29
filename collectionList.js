let collectionList = [];

document.addEventListener("DOMContentLoaded", function () {

    const addBtn = document.getElementById("addToCollectionBtn");
    const quantityInput = document.getElementById("quantity");
    const collectionItemsUL = document.getElementById("collectionItems");

    if (!addBtn || !quantityInput || !collectionItemsUL) return;

    addBtn.addEventListener("click", function () {

        let productName = "Owl Hoodie";  
        let qty = parseInt(quantityInput.value);

        if (qty < 1 || isNaN(qty)) {
            alert("Please enter a valid quantity.");
            return;
        }

        let entry = {
            name: productName,
            quantity: qty
        };

        collectionList.push(entry);

        updateCollectionList(collectionItemsUL);
    });
});


function updateCollectionList(ulElement) {
    ulElement.innerHTML = ""; 

    collectionList.forEach(function (item) {
        let li = document.createElement("li");
        li.textContent = `${item.name} × ${item.quantity}`;
        ulElement.appendChild(li);
    });
}
