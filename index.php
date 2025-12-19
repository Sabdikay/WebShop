<?php
session_start();
$isBlocked = false;

if (isset($_SESSION['userId'])) {
    $users = json_decode(file_get_contents(__DIR__ . "/users.json"), true);

    foreach ($users as $user) {
        if ($user['userId'] == $_SESSION['userId']) {
            $isBlocked = $user['isBlocked'];
            break;
        }
    }
}

?>


<!DOCTYPE html>
<html>
<head>
	<title>MemeShop</title>
	<link href="https://fonts.googleapis.com/css2?family=Comic+Neue&family=Bangers&family=Fredoka+One&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="mystyle.css">
</head>
<body>
<?php
// Page variables
$storeName = "MemeShop";
$welcomeHeading = "About Our Store";
$welcomeText = "Welcome to our MemeShop, where style meets comfort and sense of humor! We offer a wide selection of quality clothing for men and women. Browse our collection of trendy t-shirts and cozy hoodies.";
$featuredHeading = "Featured Products";
$featuredText = "Check out our latest arrivals in the Men and Women sections!";
$copyrightText = "Copyright 2025 MemeShop. All rights reserved.";
$needInfoText = "Need more Information?";
$storeInfoLink = "Store Info";
$darkMode = "🌙 Dark Mode";

// Best seller product
$bestSellerName = "Owl Hoodie";
$bestSellerPrice = "€40.00";
$bestSellerImage = "Webshop pictures/owl.png";
$bestSellerLink = "product.php?pid=4";
?>

	<header>
		<div class="header-container">
				<h1><?php echo $storeName; ?></h1>
			<nav class="top-nav">
				<a href="menList.php">Men</a>
				<a href="womenList.php">Women</a>
			</nav>
			<div class="header-icons">
				<a href="customer.php" class="profile-btn">
					<span class="btn-text">Profile</span>
				</a>
				<?php if ($isBlocked): ?>
    <p style="color:red;">
        Your account is blocked by the administrator.
    </p>
<?php else: ?>
	<a href="shoppingCart.php" class="cart-btn">
		<span class="btn-text">🛒 Cart</span>
	</a>
<?php endif; ?>

				</a>
                   <div class="theme-controls">
                      <button id="darkToggleBtn" class="profile-btn"><?php echo $darkMode; ?></button>
                   </div>
			</div>
		</div>
	</header>
    <hr>

	<!-- Banner -->
	<div class="banner">
		<img src="Webshop pictures/Main page image.jpg" alt="MemeShop Banner">
	</div>

		<!-- Best Sellers -->
	<section>
		<h1>Best Sellers</h1>
			<div class="product-card">
				<a href="<?php echo $bestSellerLink; ?>">
					<img src="<?php echo $bestSellerImage; ?>" alt="<?php echo $bestSellerName; ?>">
					<h3><?php echo $bestSellerName; ?></h3>
					<p class="price"><?php echo $bestSellerPrice; ?></p>
				</a>
			</div>
			<div class="suggestion-box">
    			<h2>Need Inspiration?</h2>
    			<button id="suggestBtn" class="add-to-cart-btn">Show Random Product</button>
    			<p id="suggestionOutput"></p>
			</div>
	</section>

	<!--Navigation Menu--> 
	<nav class="category-nav">
		<h2>Shop by Category</h2>
		<ul>
			<li><a href="menList.php">Men</a>
			<ul>
				<li><a href="menTshirtsList.php">T-Shirts</a></li>
			    <li><a href="menHoodiesList.php">Hoodies</a></li>
			</ul>
		    </li>
		    <li><a href="womenList.php">Women</a>
		    	<ul>
		    		<li><a href="womenTshirtList.php">T-Shirts</a></li>
		    		<li><a href="womenHoodiesList.php">Hoodies</a></li>
		    	</ul>	
		    </li>
		</ul>    	    
	</nav>

	<!-- Login Area -->
	<section class="auth-section">
		<h2>Authentication</h2>
		<ul>
			<li><a href="login.php">Login</a></li>
			<p>Not a member? Create an account</p>
            <li><a href="registration.php">Create New Account</a></li>
            <li><a href="shoppingCart.php">Shopping Cart</a></li>
		</ul>
	</section>

	<!--Main-->
	<main>
		<?php
		echo "<h2>" . $welcomeHeading . "</h2>";
		echo "<p>" . $welcomeText . "</p>";
		echo "<h3>" . $featuredHeading . "</h3>";
		echo "<p>" . $featuredText . "</p>";
		?>
	</main>


	<footer>
        <h3><?php echo $needInfoText; ?></h3>
        <p><a href="about.php"><?php echo $storeInfoLink; ?></a></p>
        <p><?php echo $copyrightText; ?></p>
    </footer>

<script src="task2.js"></script>
<script src="productSuggestion.js"></script>
<script src="cartIcon.js"></script>
</body>
</html>