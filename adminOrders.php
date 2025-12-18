<?php
session_start();

// Admin check
if (!isset($_SESSION['userId']) || $_SESSION['isAdmin'] !== true) {
    header("Location: login.php");
    exit;
}

$ordersByState = [
    "ordered" => [],
    "processing" => [],
    "rejected" => [],
    "completed" => []
];

// Load all orders
$orderFiles = glob("orders/*.json");
foreach ($orderFiles as $file) {
    $order = json_decode(file_get_contents($file), true);
    $ordersByState[$order['state']][] = $order;
}

// Handle status updates
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $orderId = $_POST['orderId'];
    $orderFile = "orders/$orderId.json";
    $order = json_decode(file_get_contents($orderFile), true);

    if (isset($_POST['sendOrder'])) {
        $order['state'] = "processing";
    }

    if (isset($_POST['rejectOrder'])) {
        $order['state'] = "rejected";
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
    <title>Admin – Orders</title>
    <link rel="stylesheet" href="mystyle.css">
</head>
<body>

<header class="header-container">
    <h1>Administrator – Orders</h1>
    <a href="adminCustomers.php"><button>Manage Customers</button></a>
    <a href="logout.php"><button>Logout</button></a>
</header>

<main>

<?php foreach ($ordersByState as $state => $orders): ?>
    <h2><?= ucfirst($state) ?> Orders</h2>

    <?php if (empty($orders)): ?>
        <p>No orders.</p>
    <?php endif; ?>

    <?php foreach ($orders as $order): ?>
        <div class="product-card">
            <h3>Order #<?= $order['orderId'] ?></h3>
            <p>Status: <?= $order['state'] ?></p>

            <?php if ($state === "ordered"): ?>
                <form method="post">
                    <input type="hidden" name="orderId" value="<?= $order['orderId'] ?>">
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
