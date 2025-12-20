<?php
session_start();

//Clear all session variables
$_SESSION = array();

//Destroy the session cookie if it exists
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

//Destroy the session on the server
session_destroy();
?>
<!DOCTYPE html>
<html>
<head>
 <title>Logout</title>
 <link href="https://fonts.googleapis.com/css2?family=Comic+Neue&family=Bangers&family=Fredoka+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="mystyle.css">
</head>
<body>
<?php
// Page variables
$logoutHeading = "You have successfully logged out. :)";
$logoutText = "Would you like to ";
$loginLinkText = "log in again";
$orText = " or go back to the ";
$homeLinkText = "home page";
$darkMode = "🌙 Dark Mode";
?>
<header>
    <div class="header-container">
        <div class="theme-controls">
            <button id="darkToggleBtn"><?php echo $darkMode; ?></button>
        </div>
    </div>
</header>

 <?php
 echo "<h1>" . $logoutHeading . "</h1>";
 echo '<p class="men-page">' . $logoutText . '<a href="login.php">' . $loginLinkText . '</a>' . $orText . '<a href="index.php">' . $homeLinkText . '</a>?</p>';
 ?>

<script src="task2.js"></script>
</body>
</html>