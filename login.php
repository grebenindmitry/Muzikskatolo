<?php
if (!isset($_SESSION)) {
    session_start();
}

//logout
if (isset($_GET['logout'])) {
    unset($_SESSION['username']);
    unset($_SESSION['password_hash']);
    unset($_SESSION['wrong_pass']);
    unset($_SESSION['username_exists']);
    echo '<script>window.location.replace("' . ($_GET['redir'] ?? './') . '")</script>';
}
//if credentials saved, verify them and remove if incorrect
else if (isset($_SESSION['username']) && isset($_SESSION['password_hash'])) {
    $db = mysqli_connect('localhost', 'dgrebenin', 'EkZ8zbAx', 'dgrebenin');

    if (!$db) {
        exit("Connection failed!");
    }
    //get the password
    $password_hash = mysqli_fetch_assoc(mysqli_query($db, 'SELECT password_hash FROM login WHERE username = "' . $_SESSION['username'] . '"'))['password_hash'];

    //success
    if ($password_hash == $_SESSION['password_hash']) {
        unset($_SESSION['wrong_pass']);
        unset($_SESSION['username_exists']);
        echo '<script>window.location.replace("' . ($_GET['redir'] ?? './') . '")</script>';
    }
    //fail
    else {
        unset($_SESSION['username']);
        unset($_SESSION['password_hash']);
    }
}
//if credentials POSTed, verify them and notify if incorrect
else if (isset($_POST['username']) && isset($_POST['password'])) {
    $db = mysqli_connect('localhost', 'dgrebenin', 'EkZ8zbAx', 'dgrebenin');

    if (!$db) {
        exit("Connection failed!");
    }
    //get the password
    $password_hash = mysqli_fetch_assoc(mysqli_query($db, 'SELECT password_hash FROM login WHERE username = "' . $_POST['username'] . '"'))['password_hash'];

    //success
    if (password_verify($_POST['password'], $password_hash)) {
        unset($_SESSION['wrong_pass']);
        unset($_SESSION['username_exists']);
        $_SESSION['username'] = $_POST['username'];
        $_SESSION['password_hash'] = $password_hash;
        //redirect to redir parameter (and index.php if redir !isset)
        echo '<script>window.location.replace("' . ($_GET['redir'] ?? './') . '")</script>';
    }
    //fail
    else {
        $_SESSION['wrong_pass'] = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Muzikskatolo | Login</title>
    <link type="text/css" rel="stylesheet" href="./style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="./favicon.ico" type="image/x-icon">
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
    </a>
    <?php
    //if logged in, show log out
    if (isset($_SESSION['username'])) echo '<a class="button" href="./login.php?logout&redir=' . urlencode($_SERVER['SCRIPT_NAME'] . '?' . $_SERVER['QUERY_STRING']) . '">Log Out</a>';
    //else show log in
    else echo '<a class="button" href="./login.php?redir=' . urlencode($_SERVER['SCRIPT_NAME'] . '?' . $_SERVER['QUERY_STRING']) . '">Log In</a>';
    ?>
</nav>
<header>
    <div class="logo-container flex-row">
        <a href="./"><img class="logo" alt="Muzikskatolo logo" src="./resources/images/logo.png"></a>
    </div>
</header>
<main>
    <form method="post" class="login-form center-align margin-medium">
        <fieldset class="flex-row flex-row--center">
            <legend>Login</legend>
            <label class="margin-small"> Username:
                <input class="text-input" autocorrect="off" autocapitalize="none" autocomplete="off" required type="text" name="username">
            </label>
            <label class="margin-small"> Password:
                <input class="text-input" required type="password" name="password">
            </label>
<?php if($_SESSION['wrong_pass'] ?? false):?>
            <div class="error-msg text-input margin-small">Wrong credentials</div>
<?php endif ?>
            <input class="button margin-small" type="submit" value="Log In">
            <hr class="full-width">
            Don't have an account?
            <a class="button margin-small" href="./register.php<?php if (isset($_GET['redir'])) echo '?redir=' . urlencode($_GET['redir']) ?>">Register</a>
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
</html>