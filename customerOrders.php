<?php
session_start();

/* Authentication Check */
if (!isset($_SESSION['userId'])) {
    echo "<p>You must be logged in to view your orders.</p>";
    exit;
}

$userId = $_SESSION['userId'];
$orders = [];
$orderFiles = glob("orders/*.json"); 

foreach ($orderFiles as $file) {
    $orderData = json_decode(file_get_contents($file), true);

    if (isset($orderData['user_id']) && $orderData['user_id'] == $userId) {
        $orders[] = $orderData;
    }
}




if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['cancelOrderId'])) {

    $cancelOrderId = $_POST['cancelOrderId'];

    // Build file path for this order
    $orderFile = "orders/" . $cancelOrderId . ".json";

    if (file_exists($orderFile)) {

        $orderData = json_decode(file_get_contents($orderFile), true);

        // Safety checks
        if (
            $orderData['user_id'] == $userId &&
            $orderData['status'] === "ordered"
        ) {
            $orderData['status'] = "canceled";

            file_put_contents(
                $orderFile,
                json_encode($orderData, JSON_PRETTY_PRINT)
            );
        }
    }

    header("Location: customerOrders.php");
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>My Orders</title>
    <link rel="stylesheet" href="mystyle.css">
</head>

<body>

    <header class="header-container">
        <h1>My Orders</h1>
        <a href="index.php" class="profile-btn">Home</a>
    </header>

    <main>

        <?php
        $hasOrders = false;

        foreach ($orders as $order) {
            if ($order['user_id'] == $userId) {
                $hasOrders = true;
                ?>
                <div class="product-card" style="margin:20px auto;">
                    <h2>Order #<?= htmlspecialchars($order['order_number']) ?></h2>

                    <p><strong>Status:</strong> <?= htmlspecialchars($order['status']) ?></p>

                    <?php if ($order['status'] === "rejected"): ?>
                        <p style="color:red;">
                            <strong>Reason:</strong>
                            <?= htmlspecialchars($order['rejectionReason']) ?>
                        </p>
                    <?php endif; ?>

                    <table style="margin:10px auto;">
                        <tr>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Price</th>
                        </tr>

                        <?php foreach ($order['items'] as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['name']) ?></td>
                                <td><?= $item['quantity'] ?></td>
                                <td><?= number_format($item['price'], 2) ?> €</td>
                            </tr>
                        <?php endforeach; ?>
                    </table>

                    <p><strong>Total:</strong> <?= number_format($order['totals']['total'], 2) ?> €</p>

                    <?php if ($order['discountApplied'] > 0): ?>
                        <p style="color:green;">
                            Discount Applied: <?= $order['discountApplied'] ?>%
                        </p>
                    <?php endif; ?>

                    <?php if (!empty($order['gift_option']['is_gift'])): ?>
                        <p style="background: #fff3cd; padding: 10px; border-left: 5px solid #ffc107;">
                            <strong>🎁 Gift Message:</strong> <?= htmlspecialchars($order['gift_option']['message']) ?>
                        </p>
                    <?php endif; ?>

                    <?php if ($order['status'] === "ordered"): ?>
                        <form method="post">
                            <input type="hidden" name="cancelOrderId" value="<?= $order['order_number'] ?>">
                            <button type="submit">Cancel Order</button>
                        </form>
                    <?php endif; ?>

                </div>
                <?php
            }
        }

        if (!$hasOrders) {
            echo "<p>You have not placed any orders yet.</p>";
        }
        ?>


    </main>

    <footer>
        <p>&copy; WebShop 2025</p>
    </footer>

</body>

</html>