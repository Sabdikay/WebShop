<?php

$errorMessage = "";
$selectedProduct = null;

// 1) Check if pid exists
if (!isset($_GET["pid"])) {
    $errorMessage = "Parameter 'pid' is missing in the URL!";
} else {
    $pid = $_GET["pid"];

    if (empty($pid)) {
        $errorMessage = "No value for the parameter 'pid'!";
    } else {
        // 2) Load JSON file
        $jsonPath = __DIR__ . "/products.json";

        if (!file_exists($jsonPath)) {
            $errorMessage = "Product data file not found!";
        } else {
            $jsonContent = file_get_contents($jsonPath);
            $data = json_decode($jsonContent, true);

            if ($data === null) {
                $errorMessage = "Could not decode JSON file!";
            } else {
                // 3) Search for product
                if (isset($data["product"])) {
                    foreach ($data["product"] as $product) {
                        if ((string)$product["pid"] === (string)$pid) {
                            $selectedProduct = $product;
                            break;
                        }
                    }

                    if ($selectedProduct === null) {
                        $errorMessage = "No product found for pid = " . htmlspecialchars($pid);
                    }
                } else {
                    $errorMessage = "JSON file has no 'product' array!";
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>
        <?php 
            echo $selectedProduct ? htmlspecialchars($selectedProduct["name"]) . " - Product Information" 
                                  : "Product Details";
        ?>
    </title>

    <!-- Fonts + CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Comic+Neue&family=Bangers&family=Fredoka+One&display=swap" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="mystyle.css" />
</head>

<!-- Header (dark mode button) -->
<header>
    <div class="header-container">
        <div class="theme-controls">
            <button id="darkToggleBtn">Dark Mode</button>
        </div>
    </div>
</header>

<body class="product-page">

<header class="product-header">
    <h1 id="productName">
        <?php echo $selectedProduct ? htmlspecialchars($selectedProduct["name"]) : "Product Details"; ?>
    </h1>

    <nav class="product-nav">
    <a href="index.php">Home</a>
    <a href="index.php">Back to Homepage</a>
    <a href="shoppingCart.php">Shopping Cart</a>
</nav>
</header>

<main class="product-main">
    <section class="product-details">

        <?php if (!empty($errorMessage)) : ?>

            <p style="color:red;"><?php echo htmlspecialchars($errorMessage); ?></p>

        <?php else : ?>

            <!-- Product Image -->
            <?php if (!empty($selectedProduct["imagepath"])) : ?>
                <img src="<?php echo htmlspecialchars($selectedProduct["imagepath"]); ?>" 
                     alt="<?php echo htmlspecialchars($selectedProduct["name"]); ?>" 
                     width="200" />
            <?php endif; ?>

            <!-- Price -->
            <h2>Price: €<?php echo number_format($selectedProduct["price"], 2); ?></h2>

            <!-- Description -->
            <p><?php echo htmlspecialchars($selectedProduct["description"]); ?></p>

            <!-- Basic Details -->
            <ul>
                <li><strong>Material:</strong> <?php echo htmlspecialchars($selectedProduct["material"]); ?></li>
                <li><strong>Available Sizes:</strong> 
                    <?php echo implode(", ", $selectedProduct["sizes"]); ?>
                </li>
            </ul>

            <!-- Buttons + Quantity -->
            <button class="add-to-cart-btn">Add to Shopping Cart</button> 
            <br />

            <label for="quantity"><strong>Quantity:</strong></label>
            <input type="number" id="quantity" min="1" value="1" />
            <p id="quantityWarning" style="color: red"></p>

            <!-- Collection List -->
            <button id="addToCollectionBtn" class="add-to-cart-btn">Add to Collection List</button>

            <div id="collectionList">
                <h3>Your Collection List:</h3>
                <ul id="collectionItems"></ul>
            </div>

            <!-- Price Calculator -->
            <h3>Price Calculator</h3>

            <label for="priceInput">Price without tax (€):</label>
            <input type="number" id="priceInput" min="0" step="0.01" />

            <button id="calcPriceBtn" class="add-to-cart-btn">Calculate Price with Tax</button>
            <p id="priceWithTaxOutput"></p>

        <?php endif; ?>

    </section>
</main>

<footer class="product-footer">
    <p>&copy; 2025 WebShop – All rights reserved.</p>
</footer>

<!-- JS Scripts -->
<script src="task2.js"></script>
<script src="collectionList.js"></script>
<script src="taxCalculator.js"></script>

</body>
</html>
