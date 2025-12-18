<?php
session_start();

// Security check
if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['userId'];
$usersFile = "users.json";

$users = json_decode(file_get_contents($usersFile), true);

// Find logged-in user
foreach ($users as $user) {
    if ($user['userId'] == $userId) {
        $username = $user['username'];
        $password = $user['password'];
        break;
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
    <button type="button">My Orders</button>
</a>
            <button id="darkToggleBtn"><?php echo $darkMode; ?></button>
        </div>
    </div>
</header>

 <?php
 echo "<h1>" . $pageHeading . "</h1>";
 ?>
 <form id="customerForm">
  <label>Username:</label><br>
  <input type="text" id="username" value="<?php echo $username; ?>">
  <span id="usernameError" class="error"></span>
  <br>
  <label>Password:</label><br>
  <input type="text" id="password" value="<?php echo $password; ?>">
  <span id="passwordError" class="error"></span><br><br>
  <button type="submit">Update Information</button>
  </form>
  <p><a href="logout.php">Logout</a></p>
  <p class="men-page"><a href="index.php">Back to Homepage</a></p>
  
<script src="forms.js"></script>
<script src="task2.js"></script>
</body>
</html>