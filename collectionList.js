let collectionList = [];

document.addEventListener("DOMContentLoaded", function () {
  const addBtn = document.getElementById("addToCollectionBtn");
  const quantityInput = document.getElementById("quantity");
  const collectionItemsUL = document.getElementById("collectionItems");

  addBtn.addEventListener("click", function () {
    let productName = document.getElementById("productName").textContent;
    let qty = parseInt(quantityInput.value);

    let warning = document.getElementById("quantityWarning");

    if (qty < 1 || isNaN(qty)) {
      warning.textContent = "Please enter a valid quantity.";
      return;
    } else {
      warning.textContent = "";
    }

    let entry = {
      name: productName,
      quantity: qty,
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
