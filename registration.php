<?php
$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $usersFile = "users.json";

    if (!file_exists($usersFile)) {
        $errorMessage = "User database not found.";
    } else {
        $users = json_decode(file_get_contents($usersFile), true);

        foreach ($users as $user) {
            if ($user['username'] === $username) {
                $errorMessage = "Username already exists.";
                break;
            }
        }

        if ($errorMessage === "") {
            $newUserId = empty($users) ? 1 : end($users)['userId'] + 1;

            $users[] = [
                "userId" => $newUserId,
                "username" => $username,
                "password" => $password,
                "isBlocked" => false,
                "isAdmin" => false
            ];

            file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));
            header("Location: login.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Registration Page</title>
        <link href="https://fonts.googleapis.com/css2?family=Comic+Neue&family=Bangers&family=Fredoka+One&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="mystyle.css">
    </head>
    <body>
<?php
// Page variables
$pageHeading = "Register for an account";
$usernameLabel = "Username: ";
$passwordLabel = "Password: ";
$confirmPasswordLabel = "Confirm Password: ";
$cancelButtonText = "Cancel";
$registerButtonText = "Register";
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
        <form id="regForm" method="post">
            <label><?php echo $usernameLabel; ?></label> 
            <input type="text" id="username">
            <span id="usernameError" class="error"></span> 
            <br>
            <label><?php echo $passwordLabel; ?></label>
            <input type="password" id="password" name="password">
            <span id="passwordError" class="error"></span> 
            <br>
            <label><?php echo $confirmPasswordLabel; ?></label>
            <input type="password" id="confirmPassword" name="confirmPassword">
            <span id="confirmError" class="error"></span>
            <br>

            <?php
if (!empty($errorMessage)) {
    echo "<p style='color:red;'>$errorMessage</p>";
}
?>


            <a href="login.php">
    <button type="button"><?php echo $cancelButtonText; ?></button>
</a>
<br><br>
<button type="submit"><?php echo $registerButtonText; ?></button>

        </form>

        <p class="men-page"><a href="index.php">Back to Homepage</a></p>

        <script src="forms.js"></script>
        <script src="task2.js"></script>

    </body>
</html>