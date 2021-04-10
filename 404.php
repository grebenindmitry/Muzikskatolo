<!--
Created by Dmitry Grebenin
2021
-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Muzikskatolo | Uh oh...</title>
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
    if (!isset($_SESSION)) {
        session_start();
    }
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
    <div class="margin-medium">
        <h1>Uh oh...</h1>
        <h2>The page you tried to access doesn't seem to exist.</h2>
    </div>
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