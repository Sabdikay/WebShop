<?php
session_start();

$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $usersFile = "users.json";

    if (file_exists($usersFile)) {
        $users = json_decode(file_get_contents($usersFile), true);

        foreach ($users as $user) {
            if (
                $user['username'] === $username &&
                $user['password'] === $password
            ) {

                $_SESSION['userId'] = $user['userId'];
                $_SESSION['isAdmin'] = $user['isAdmin'];

                header("Location: customer.php");
                exit;
            }
        }
    }

    $errorMessage = "Invalid username or password.";
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Login Page</title>
        <link href="https://fonts.googleapis.com/css2?family=Comic+Neue&family=Bangers&family=Fredoka+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="mystyle.css">
    </head>
    <body>
<?php
// Page variables
$pageHeading = "Login to your account";
$usernameLabel = "Username: ";
$passwordLabel = "Password: ";
$notRegisteredText = "Not Registered? Click Here!";
$verifyButtonText = "Verify";
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
        echo "<h1>" . $pageHeading . "</h1>";
        ?>
        <hr>
        <form id="loginForm" method="post" action="login.php">
            <label><?php echo $usernameLabel; ?></label>
            <input type="text" id="username" name = "username">
            <span id="usernameError" class="error"></span>
            <br>

            <label><?php echo $passwordLabel; ?></label>
            <input type="password" id="password" name="password">
            <span id="passwordError" class="error"></span>
            <br><br>

            <?php
if (!empty($errorMessage)) {
    echo "<p style='color:red;'>$errorMessage</p>";
}
?>


            <a href="registration.php"><button type="button"><?php echo $notRegisteredText; ?></button></a>
            <br><br>
            <button type = "submit"><?php echo $verifyButtonText; ?></button>
        </form>

        <p class="men-page"><a href="index.php">Back to Homepage</a></p>

        <script src="forms.js?v=2"></script>
        <script src="task2.js"></script>
    </body>
</html>