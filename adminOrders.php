<?php
session_start();
$state = $_SESSION["null"] ?? "default_value";
$darkMode = "🌙 Dark Mode";
// Admin check
if (!isset($_SESSION['userId']) || $_SESSION['isAdmin'] !== true) {
    header("Location: login.php");
    exit;
}

$ordersByStatus = [
    "ordered" => [],
    "processing" => [],
    "rejected" => [],
    "completed" => []
];

// Load all orders
$orderFiles = glob("orders/*.json");
foreach ($orderFiles as $file) {
    $order = json_decode(file_get_contents($file), true);
    $ordersByStatus[$order['status']][] = $order;
}

// Handle status updates
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $orderId = $_POST['orderId'];
    $orderFile = "orders/$orderId.json";
    $order = json_decode(file_get_contents($orderFile), true);

    if (isset($_POST['sendOrder'])) {
        $order['status'] = "processing";
    }

    if (isset($_POST['rejectOrder'])) {
        $order['status'] = "rejected";
        $order['rejectionReason'] = $_POST['reason'];
    }

    file_put_contents($orderFile, json_encode($order, JSON_PRETTY_PRINT));
    header("Location: adminOrders.php");
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
	<h1>Administrator - Orders</h1>
	<link href="https://fonts.googleapis.com/css2?family=Comic+Neue&family=Bangers&family=Fredoka+One&display=swap"
		rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="mystyle.css">
</head>

<body>
<title>Administrator - Orders</title>
    <header class="header-container">
        <div class="theme-controls">
            
            <a href="adminCustomers.php"><button class="profile-btn">Manage Customers</button></a>
            <a href="logout.php"><button class="profile-btn">Logout</button></a>
            <div>
    </header>

    <main>

        <?php foreach ($ordersByStatus as $status => $orders): ?>
            <h2><?= ucfirst($status) ?> Orders</h2>

            <?php if (empty($orders)): ?>
                <p>No orders.</p>
            <?php endif; ?>

            <?php foreach ($orders as $order): ?>
                <div class="product-card">
                    <h3>Order #<?= $order['order_number'] ?></h3>
                    <p>Status: <?= $order['status'] ?></p>

                    <?php if ($status === "ordered"): ?>
                        <form method="post">
                            <input type="hidden" name="orderId" value="<?= $order['order_number'] ?>">
                            <button name="sendOrder">Mark as Sent</button>
                            <br><br>
                            <textarea name="reason" placeholder="Reason for rejection"></textarea>
                            <br>
                            <button name="rejectOrder">Reject Order</button>
                        </form>
                    <?php endif; ?>

                    <?php if ($state === "rejected"): ?>
                        <p style="color:red;">
                            Reason: <?= htmlspecialchars($order['rejectionReason']) ?>
                        </p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

        <?php endforeach; ?>

    </main>
</body>

</html>