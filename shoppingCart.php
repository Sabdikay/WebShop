<?php
session_start();

// Check if user is logged in
$isLoggedIn = isset($_SESSION['userId']);
$userId = $isLoggedIn ? $_SESSION['userId'] : null;

// Get cart session ID
if (!isset($_SESSION['cart_id'])) {
    $_SESSION['cart_id'] = 'cart_' . session_id();
}
$cartId = $_SESSION['cart_id'];

// Load cart data
$cartFile = __DIR__ . "/carts/" . $cartId . ".json";
$cartData = [
    'cart_id' => $cartId,
    'items' => [],
    'totals' => [
        'subtotal' => 0,
        'tax' => 0,
        'total' => 0
    ]
];



if (file_exists($cartFile)) {
    $cartData = json_decode(file_get_contents($cartFile), true);
}
$discountAmount = $cartData['totals']['discount'] ?? 0;
$totalAfterDiscount = $cartData['totals']['total'] ?? 0;

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart - MemeShop</title>
    <link href="https://fonts.googleapis.com/css2?family=Comic+Neue&family=Bangers&family=Fredoka+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="mystyle.css">
    
</head>
<body>

<header>
    <div class="header-container">
        <h1>MemeShop</h1>
        <nav class="top-nav">
            <a href="menList.php">Men</a>
            <a href="womenList.php">Women</a>
        </nav>
        <div class="header-icons">
            <a href="customer.php" class="profile-btn">
                <span class="btn-text">👤Profile</span>
            </a>
            <a href="shoppingCart.php" class="cart-btn">
                <span class="btn-text">🛒Cart</span>
            </a>
            <div class="theme-controls">
            <button id="darkToggleBtn">🌙 Dark Mode</button>
        </div>
        </div>
        
    </div>
    
</header>
<hr>

<div class="cart-container">
    <h1>Your Shopping Cart</h1>
    
    <?php if (empty($cartData['items'])): ?>
        <div class="cart-empty">
            <p>Your cart is empty!</p>
            <p><a href="index.php">Continue Shopping</a></p>
        </div>
    <?php else: ?>
        
        <?php if (!$isLoggedIn): ?>
            <div class="login-prompt">
                ⚠️ Please <a href="login.php">log in</a> to complete your order.
            </div>
        <?php endif; ?>
        
        <div class="cart-items">
            <?php foreach ($cartData['items'] as $item): ?>
                <div class="cart-item" data-pid="<?php echo $item['pid']; ?>">
                    <div class="cart-item-image">
                        <img src="<?php echo htmlspecialchars($item['imagepath']); ?>" 
                             alt="<?php echo htmlspecialchars($item['name']); ?>">
                    </div>
                    
                    <div class="cart-item-details">
                        <div class="cart-item-name"><?php echo htmlspecialchars($item['name']); ?></div>
                        <div class="cart-item-price">€<?php echo number_format($item['price'], 2); ?> each</div>
                    </div>
                    
                    <div class="cart-item-quantity">
                        <button class="quantity-btn" onclick="updateQuantity(<?php echo $item['pid']; ?>, -1)">-</button>
                        <input type="number" class="quantity-input" value="<?php echo $item['quantity']; ?>" 
                               min="1" id="qty-<?php echo $item['pid']; ?>"
                               onchange="setQuantity(<?php echo $item['pid']; ?>, this.value)">
                        <button class="quantity-btn" onclick="updateQuantity(<?php echo $item['pid']; ?>, 1)">+</button>
                    </div>
                    
                    <div class="cart-item-subtotal">
                        <strong>€<?php echo number_format($item['subtotal'], 2); ?></strong>
                    </div>
                    
                    <button class="remove-btn" onclick="removeItem(<?php echo $item['pid']; ?>)">Remove</button>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="cart-summary">
            <h2>Order Summary</h2>
            <div class="summary-row">
                <span>Subtotal:</span>
                <span>€<?php echo number_format($cartData['totals']['subtotal'], 2); ?></span>
            </div>
            <div class="summary-row">
                <span>Tax (19%):</span>
                <span>€<?php echo number_format($cartData['totals']['tax'], 2); ?></span>
            </div>
            <?php if ($discountAmount > 0): ?>
<div class="summary-row">
    <span>Discount:</span>
    <span>-€<?php echo number_format($discountAmount, 2); ?></span>
</div>
<?php endif; ?>

            <div class="summary-row total">
                <span>Total:</span>
                <span>€<?php echo number_format($totalAfterDiscount, 2); ?></span>
            </div>
            
            <button class="checkout-btn" 
                    <?php echo !$isLoggedIn ? 'disabled' : ''; ?>
                    onclick="checkout()">
                <?php echo $isLoggedIn ? 'Proceed to Checkout' : 'Login Required'; ?>
            </button>
        </div>
        
        <div class="continue-shopping">
            <a href="index.php">← Continue Shopping</a>
        </div>
        
    <?php endif; ?>
</div>

<script src="task2.js"></script>
<script>
function updateQuantity(pid, change) {
    const input = document.getElementById('qty-' + pid);
    const newValue = parseInt(input.value) + change;
    if (newValue > 0) {
        setQuantity(pid, newValue);
    }
}

function setQuantity(pid, quantity) {
    quantity = parseInt(quantity);
    if (quantity < 1) quantity = 1;
    
    const formData = new FormData();
    formData.append('action', 'update');
    formData.append('pid', pid);
    formData.append('quantity', quantity);
    
    fetch('cart_handler.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error updating cart: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to update cart');
    });
}

function removeItem(pid) {
    if (!confirm('Remove this item from cart?')) return;
    
    const formData = new FormData();
    formData.append('action', 'remove');
    formData.append('pid', pid);
    
    fetch('cart_handler.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error removing item: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to remove item');
    });
}

function checkout() {
    window.location.href = 'checkout.php';
}
</script>

</body>
</html>