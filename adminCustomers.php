<?php
session_start();
if (!isset($_SESSION['isAdmin']) || !$_SESSION['isAdmin']) {
    header("Location: login.php");
    exit;
}

$usersFile = "users.json";
$users = json_decode(file_get_contents($usersFile), true);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    foreach ($users as &$user) {
        if ($user['userId'] == $_POST['userId']) {
            $user['isBlocked'] = !$user['isBlocked'];
        }
    }
    file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));
    header("Location: adminCustomers.php");
    exit;
}
?>

<h1>Manage Customers</h1>

<?php foreach ($users as $user): ?>
    <?php if (!$user['isAdmin']): ?>
        <div>
            <strong><?= $user['username'] ?></strong>
            (<?= $user['isBlocked'] ? "Blocked" : "Active" ?>)

            <form method="post" style="display:inline;">
                <input type="hidden" name="userId" value="<?= $user['userId'] ?>">
                <button>
                    <?= $user['isBlocked'] ? "Unblock" : "Block" ?>
                </button>
            </form>
        </div>
    <?php endif; ?>
<?php endforeach; ?>
