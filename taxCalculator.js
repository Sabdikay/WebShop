document.addEventListener("DOMContentLoaded", function () {
    
    function getTotalPrice(priceWOTax) {
        return priceWOTax * 1.19;
    }



    const btn = document.getElementById("calcPriceBtn");
    const input = document.getElementById("priceInput");
    const output = document.getElementById("priceWithTaxOutput");

    if (!btn) return;

    btn.addEventListener("click", function () {
        let price = parseFloat(input.value);

        if (isNaN(price) || price < 0) {
            output.textContent = "Please enter a valid price.";
            return;
        }

        let result = getTotalPrice(price);
        output.textContent = `Price with tax: €${result.toFixed(2)}`;
    });
});
