<?php
session_start();

// Security check
if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['userId'];
$usersFile = "users.json";
$errorMessage = "";
$successMessage = "";

$users = json_decode(file_get_contents($usersFile), true);

// Find logged-in user
foreach ($users as $user) {
    if ($user['userId'] == $userId) {
        $username = $user['username'];
        $password = $user['password'];
        break;
    }
}

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $newUsername = $_POST['username'] ?? '';
    $newPassword = $_POST['password'] ?? '';

    // Update only the logged-in user
    foreach ($users as &$user) {
        if ($user['userId'] == $userId) {
            $user['username'] = $newUsername;
            $user['password'] = $newPassword;
            break;
        }
    }

    // Save changes
    file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));
    $successMessage = "Profile updated successfully!";

    // Load current user data for display
foreach ($users as $user) {
    if ($user['userId'] == $userId) {
        $username = $user['username'];
        $password = $user['password'];
        break;
    }
}
}


?>


<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Customer Information</title>
  <link href="https://fonts.googleapis.com/css2?family=Comic+Neue&family=Bangers&family=Fredoka+One&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="mystyle.css">
</head>
<body>
<?php
// Page variables
$pageHeading = "Customer Profile";
$darkMode = "🌙 Dark Mode";
?>


<header>
    <div class="header-container">
        <div class="theme-controls">
          <a href="customerOrders.php">
    <button type="button" class="profile-btn">My Orders</button>
</a>
            <button id="darkToggleBtn"><?php echo $darkMode; ?></button>
        </div>
    </div>
</header>

 <?php
 echo "<h1>" . $pageHeading . "</h1>";
 ?>
 <form id="customerForm" method="post">
  <label>Username:</label><br>
  <input type="text" value="<?php echo htmlspecialchars($username ?? ''); ?>">
  <span id="usernameError" class="error"></span>
  <br>
  <label>Password:</label><br>
  <input type="text" id="password" name="password" value="<?php echo htmlspecialchars($password ?? ''); ?>">
  <span id="passwordError" class="error"></span><br><br>
  <?php
if (!empty($successMessage)) {
    echo "<p style='color:green;'>$successMessage</p>";
}
if (!empty($errorMessage)) {
    echo "<p style='color:red;'>$errorMessage</p>";
}
?>

  <button type="submit">Update Information</button>
  </form>
  <p><a href="logout.php">Logout</a></p>
  <p class="men-page"><a href="index.php">Back to Homepage</a></p>
  
<script src="forms.js"></script>
<script src="task2.js"></script>
</body>
</html>