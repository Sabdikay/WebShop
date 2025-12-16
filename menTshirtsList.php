<?php

$jsonPath = __DIR__ . "/products.json";
$products = [];
$error = "";

// Load JSON data
if (!file_exists($jsonPath)) {
    $error = "Product data file not found!";
} else {
    $jsonContent = file_get_contents($jsonPath);
    $data = json_decode($jsonContent, true);

    if ($data === null) {
        $error = "Could not decode JSON.";
    } else {
        $products = $data["product"];
    }
}


$filteredProducts = [];

foreach ($products as $p) {
    if ($p["category"] === "Men" && $p["subcategory"] === "Mens T-Shirts") {
        $filteredProducts[] = $p;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Men's T-Shirts</title>

    <link href="https://fonts.googleapis.com/css2?family=Comic+Neue&family=Bangers&family=Fredoka+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="mystyle.css">
</head>

<body class="men-page">

<header>
    <div class="header-container">
        <div class="theme-controls">
            <button id="darkToggleBtn">🌙 Dark Mode</button>
        </div>
    </div>
</header>

<h2>Here are all the T-Shirts we offer for men:</h2>

<?php if (!empty($error)) : ?>

    <p style="color:red;"><?php echo $error; ?></p>

<?php else : ?>

    <div class="product-section">
        <ol class="product-list">

            <?php foreach ($filteredProducts as $product) : ?>
                <li class="product-item">

                    <img src="<?php echo $product['imagepath']; ?>" 
                         alt="<?php echo htmlspecialchars($product['name']); ?>" 
                         width="150">

                    <p><?php echo htmlspecialchars($product['name']); ?></p>

                    <p>Price: <?php echo number_format($product['price'], 2); ?> €</p>

                    <a href="product.php?pid=<?php echo $product['pid']; ?>">Details</a>

                </li>
                <br>
            <?php endforeach; ?>

            <?php if (empty($filteredProducts)) : ?>
                <p>No products found in this category.</p>
            <?php endif; ?>

        </ol>
    </div>

<?php endif; ?>

<p><a href="index.php">Back to Homepage</a></p>

<script src="task2.js"></script>

</body>
</html>