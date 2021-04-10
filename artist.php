<?php
//start a session if not started already
if (!isset($_SESSION)) {
    session_start();
}

$db = mysqli_connect('localhost', 'dgrebenin', 'EkZ8zbAx', 'dgrebenin');

if (!$db) {
    exit("Connection failed!");
}

if (isset($_SESSION['username']) && isset($_SESSION['password_hash'])) {
    //if authentication passed
    if ($_SESSION['password_hash'] == mysqli_fetch_assoc(mysqli_query($db, 'SELECT password_hash FROM login WHERE username = "' . $_SESSION['username'] . '"'))['password_hash']) {
        echo '
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Muzikskatolo | ' . $_GET['artist'] . '</title>
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
            <h1 class="text-xxlarge">' . $_GET['artist'] . '</h1>

            <div class="diagonal-block brand-background">
                <div class="diagonal-block-content">
                    <h3>Artist biography:</h3>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent sodales lacinia
                    quam, ut lacinia velit vehicula vitae. Aenean vel sagittis lorem. Mauris varius nibh justo, in viverra urna
                    consequat aliquet. Nullam nec dolor venenatis, elementum felis sed, tempus erat. Proin fermentum dolor ac
                    mi pharetra condimentum. Phasellus ut viverra mauris. Duis ac sapien ligula. Suspendisse consequat sed purus
                    nec feugiat. Aenean efficitur libero eu leo ultricies elementum.
                </div>
            </div>

            <div>
                <h3 class="text-xlarge">Albums: </h3>
                <div class="flex-row">';
        $result = mysqli_query($db, 'SELECT DISTINCT album, image FROM tracks WHERE artist="'. $_GET['artist'] .'"');

        while ($row = mysqli_fetch_assoc($result)) {
            echo '
        <a href="album.php?album=' . $row['album'] . '" class="album-thumb-container">
            <img class="album-thumb" alt="' . $row['album'] . ' album cover" src="./' . $row['image'] . '">
            <div>' . $row['album'] . '</div>
        </a>';
        }
        echo '
                </div>
            </div>
        </main>
        <footer>
            <div id="creator" class="text-smaller footer-content">
                Created by Dmitry Grebenin. 2021
            </div>
        </footer>
    </body>
</html>';
    }
    //if wrong credentials
    else {
        unset($_SESSION['username']);
        unset($_SESSION['password_hash']);
        $_SESSION['wrong_pass'] = true;
        echo '<script>window.location.replace("./login.php?redir=' . urlencode($_SERVER['SCRIPT_NAME'] . '?' . $_SERVER['QUERY_STRING']) . '")</script>';
    }
}
//if no credentials
else {
    echo '<script>window.location.replace("./login.php?redir=' . urlencode($_SERVER['SCRIPT_NAME'] . '?' . $_SERVER['QUERY_STRING']) . '")</script>';
}