    function increaseTextSize() {
    let text = document.getElementById("textBlock");
    let address = document.querySelector("address");

    let style = window.getComputedStyle(text, null).getPropertyValue("font-size");
    let currentSize = parseFloat(style);

    let newSize = currentSize + 2;

    text.style.fontSize = newSize + "px";
    address.style.fontSize = newSize + "px";

    document.getElementById("fontInfo").innerHTML =
        "Current font size: " + newSize + "px";

    if (newSize > 60) {
        text.style.fontSize = "60px";
        address.style.fontSize = "60px";
        document.getElementById("fontInfo").innerHTML =
            "Maximum size reached (60px)";
    }
}
