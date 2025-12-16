<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['userId'];

// Get cart data
if (!isset($_SESSION['cart_id'])) {
    header("Location: shoppingCart.php");
    exit;
}

$cartId = $_SESSION['cart_id'];
$cartFile = __DIR__ . "/carts/" . $cartId . ".json";

if (!file_exists($cartFile)) {
    header("Location: shoppingCart.php");
    exit;
}

$cartData = json_decode(file_get_contents($cartFile), true);

if (empty($cartData['items'])) {
    header("Location: shoppingCart.php");
    exit;
}

$orderSuccess = false;
$orderNumber = '';

// Handle order submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Generate order number
    $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
    
    // Create orders directory if it doesn't exist
    $ordersDir = __DIR__ . "/orders";
    if (!is_dir($ordersDir)) {
        mkdir($ordersDir, 0777, true);
    }
    
    // Prepare order data
    $orderData = [
        'order_number' => $orderNumber,
        'user_id' => $userId,
        'order_date' => date('Y-m-d H:i:s'),
        'status' => 'completed',
        'items' => $cartData['items'],
        'totals' => $cartData['totals'],
        'shipping_address' => [
            'name' => $_POST['fullname'] ?? '',
            'address' => $_POST['address'] ?? '',
            'city' => $_POST['city'] ?? '',
            'postal_code' => $_POST['postal_code'] ?? '',
            'country' => $_POST['country'] ?? ''
        ],
        'payment_method' => $_POST['payment_method'] ?? 'credit_card'
    ];
    
    // Save order
    $orderFile = $ordersDir . "/" . $orderNumber . ".json";
    file_put_contents($orderFile, json_encode($orderData, JSON_PRETTY_PRINT));
    
    // Clear cart
    unlink($cartFile);
    unset($_SESSION['cart_id']);
    
    $orderSuccess = true;
}

$darkMode = "🌙 Dark Mode";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Checkout - MemeShop</title>
    <link href="https://fonts.googleapis.com/css2?family=Comic+Neue&family=Bangers&family=Fredoka+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="mystyle.css">
    <style>
        .checkout-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 30px;
        }
        
        .checkout-form {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 8px;
        }
        
        .form-section {
            margin-bottom: 30px;
        }
        
        .form-section h3 {
            margin-bottom: 15px;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 10px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1em;
        }
        
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #4CAF50;
        }
        
        .order-summary {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            border: 2px solid #4CAF50;
            height: fit-content;
            position: sticky;
            top: 20px;
        }
        
        .order-summary h3 {
            margin-bottom: 15px;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 10px;
        }
        
        .order-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        
        .order-item:last-child {
            border-bottom: none;
        }
        
        .summary-totals {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #333;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
        }
        
        .summary-row.total {
            font-size: 1.3em;
            font-weight: bold;
            margin-top: 10px;
        }
        
        .place-order-btn {
            width: 100%;
            background: #4CAF50;
            color: white;
            border: none;
            padding: 15px;
            font-size: 1.2em;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 20px;
        }
        
        .place-order-btn:hover {
            background: #45a049;
        }
        
        .success-message {
            text-align: center;
            padding: 40px;
            background: #d4edda;
            border: 2px solid #28a745;
            border-radius: 8px;
            margin: 40px auto;
            max-width: 600px;
        }
        
        .success-message h2 {
            color: #155724;
            margin-bottom: 20px;
        }
        
        .success-message .order-number {
            font-size: 1.5em;
            font-weight: bold;
            color: #28a745;
            margin: 20px 0;
        }
        
        .payment-options {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }
        
        .payment-option {
            padding: 15px;
            border: 2px solid #ddd;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
        }
        
        .payment-option input[type="radio"] {
            margin-right: 8px;
        }
        
        .payment-option:has(input:checked) {
            border-color: #4CAF50;
            background: #e8f5e9;
        }
    </style>
</head>
<body>

<header>
    <div class="header-container">
        <h1>MemeShop</h1>
        <div class="header-icons">
            <div class="theme-controls">
                <button id="darkToggleBtn" class="profile-btn"><?php echo $darkMode; ?></button>
            </div>
        </div>
    </div>
</header>
<hr>

<?php if ($orderSuccess): ?>
    <div class="success-message">
        <h2>✅ Order Placed Successfully!</h2>
        <p>Thank you for your purchase!</p>
        <div class="order-number">Order #<?php echo $orderNumber; ?></div>
        <p>A confirmation email has been sent to your registered email address.</p>
        <p style="margin-top: 30px;">
            <a href="customerOrders.php" style="color: #4CAF50; font-weight: bold;">View My Orders</a> | 
            <a href="index.php" style="color: #4CAF50; font-weight: bold;">Continue Shopping</a>
        </p>
    </div>
<?php else: ?>

<div class="checkout-container">
    <div class="checkout-form">
        <h2>Checkout</h2>
        
        <form method="POST">
            <div class="form-section">
                <h3>Shipping Information</h3>
                
                <div class="form-group">
                    <label for="fullname">Full Name *</label>
                    <input type="text" id="fullname" name="fullname" required>
                </div>
                
                <div class="form-group">
                    <label for="address">Address *</label>
                    <input type="text" id="address" name="address" required>
                </div>
                
                <div class="form-group">
                    <label for="city">City *</label>
                    <input type="text" id="city" name="city" required>
                </div>
                
                <div class="form-group">
                    <label for="postal_code">Postal Code *</label>
                    <input type="text" id="postal_code" name="postal_code" required>
                </div>
                
                <div class="form-group">
                    <label for="country">Country *</label>
                    <select id="country" name="country" required>
                        <option value="">Select Country</option>
                        <option value="Germany">Germany</option>
                        <option value="Austria">Austria</option>
                        <option value="Switzerland">Switzerland</option>
                        <option value="France">France</option>
                        <option value="Italy">Italy</option>
                        <option value="Netherlands">Netherlands</option>
                    </select>
                </div>
            </div>
            
            <div class="form-section">
                <h3>Payment Method</h3>
                <div class="payment-options">
                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="credit_card" checked>
                        Credit Card
                    </label>
                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="paypal">
                        PayPal
                    </label>
                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="bank_transfer">
                        Bank Transfer
                    </label>
                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="cash_on_delivery">
                        Cash on Delivery
                    </label>
                </div>
            </div>
            
            <button type="submit" class="place-order-btn">Place Order</button>
        </form>
        
        <p style="text-align: center; margin-top: 20px;">
            <a href="shoppingCart.php">← Back to Cart</a>
        </p>
    </div>
    
    <div class="order-summary">
        <h3>Order Summary</h3>
        
        <?php foreach ($cartData['items'] as $item): ?>
            <div class="order-item">
                <div>
                    <strong><?php echo htmlspecialchars($item['name']); ?></strong><br>
                    <small>Qty: <?php echo $item['quantity']; ?> × €<?php echo number_format($item['price'], 2); ?></small>
                </div>
                <div>
                    €<?php echo number_format($item['subtotal'], 2); ?>
                </div>
            </div>
        <?php endforeach; ?>
        
        <div class="summary-totals">
            <div class="summary-row">
                <span>Subtotal:</span>
                <span>€<?php echo number_format($cartData['totals']['subtotal'], 2); ?></span>
            </div>
            <div class="summary-row">
                <span>Tax (19%):</span>
                <span>€<?php echo number_format($cartData['totals']['tax'], 2); ?></span>
            </div>
            <?php if ($cartData['totals']['discount'] > 0): ?>
                <div class="summary-row">
                    <span>Discount:</span>
                    <span>-€<?php echo number_format($cartData['totals']['discount'], 2); ?></span>
                </div>
            <?php endif; ?>
            <div class="summary-row total">
                <span>Total:</span>
                <span>€<?php echo number_format($cartData['totals']['total'], 2); ?></span>
            </div>
        </div>
    </div>
</div>

<?php endif; ?>

<script src="task2.js"></script>

</body>
</html>