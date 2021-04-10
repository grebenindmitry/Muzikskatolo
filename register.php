<?php
if (!isset($_SESSION)) {
    session_start();
}

$db = mysqli_connect('localhost', 'dgrebenin', 'EkZ8zbAx', 'dgrebenin');

if (!$db) {
    exit("Connection failed!");
}

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $plan = $_POST['plan'];
    if (mysqli_query($db, 'INSERT INTO login (username, password_hash, plan) VALUE ("' . $username . '", "' . $password_hash . '", "' . $plan . '")')) {
        unset($_SESSION['wrong_pass']);
        unset($_SESSION['username_exists']);
        $_SESSION['username'] = $username;
        $_SESSION['password_hash'] = $password_hash;
        echo '<script>window.location.replace("' . ($_GET['redir'] ?? './') . '")</script>';
    } else {
        $_SESSION['username_exists'] = true;
    }
}
echo '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Muzikskatolo | Register</title>
    <link type="text/css" rel="stylesheet" href="./style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="./favicon.ico" type="image/x-icon">
    <script src="./script.js" rel="script" type="application/javascript"></script>
</head>
<body>
<nav>
    <a class="button" href="./">
        Home
    </a>
    <a class="button" href="./library.php">
        Library
    </a>
    <a class="button" href="./playlists.php">
        Playlists
    </a>
    <a class="button" href="./about.php">
        About
    </a>';
//if logged in, show log out
if (isset($_SESSION['username'])) echo '<a class="button" href="./login.php?logout&redir=' . urlencode($_SERVER['SCRIPT_NAME'] . '?' . $_SERVER['QUERY_STRING']) . '">Log Out</a>';
//else show log in
else echo '<a class="button" href="./login.php?redir=' . urlencode($_SERVER['SCRIPT_NAME'] . '?' . $_SERVER['QUERY_STRING']) . '">Log In</a>';
echo '
</nav>
<header>
    <div class="logo-container flex-row">
        <a href="./"><img class="logo" alt="Muzikskatolo logo" src="./resources/images/logo.png"></a>
    </div>
</header>
<main>
    <form method="post" class="login-form center-align margin-medium">
        <fieldset class="flex-row flex-row--center">
            <legend>Registration</legend>
            <label class="margin-small"> Username:
                <input class="text-input full-width" required type="text" name="username">
            </label>
            <label class="margin-small"> Password:
                <input class="text-input full-width" onchange="validatePassword()" required type="password" name="password" id="password">
            </label>
            <label class="margin-small"> Confirm password:
                <input class="text-input full-width" onkeyup="validatePassword()" required type="password" name="password-confirm" id="password-confirm">
            </label>
            <label class="margin-small"> Select a plan:
                <select class="text-input" required name="plan" id="plan">';
$result = mysqli_query($db, 'SELECT offer_id, title, price FROM offers');
while ($row = mysqli_fetch_assoc($result)) {
    echo '<option value="' . $row['offer_id'] . '">' . $row['title'] . ': £' . $row['price'] . '</option>';
}
echo '
                </select>
            </label>
            <input class="button margin-small" type="submit" value="Register">';
if(isset($_SESSION['username_exists']) && $_SESSION['username_exists'] == true)
    echo '<div class="error-msg text-input">Username already exists</div>';
echo '
        </fieldset>
    </form>
</main>
<footer>
    <div class="footer-content text-medium">
        <div id="creator" class="text-smaller">
            Created by Dmitry Grebenin. 2021
        </div>
    </div>
</footer>
</body>
</html>';
