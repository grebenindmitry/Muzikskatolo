<!--
Created by Dmitry Grebenin
2021
-->

<?php
//start a session if not started already
if (!isset($_SESSION)) {
    session_start();
}

$db = mysqli_connect('localhost', 'dgrebenin', 'EkZ8zbAx', 'dgrebenin');

if (!$db) {
    exit("Connection failed!");
}

//if credentials saved
if (isset($_SESSION['username']) && isset($_SESSION['password_hash'])) {
    //if authentication passed
    if ($_SESSION['password_hash'] == mysqli_fetch_assoc(mysqli_query($db, 'SELECT password_hash FROM login WHERE username = "' . $_SESSION['username'] . '"'))['password_hash']) {
        echo '
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Muzikskatolo | ' . $_GET['album'] . '</title>
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
            <h1 class="text-xxlarge">' . $_GET['album'] . '</h1>
            
            <div class="flex-column margin-large">
                <img class="full-width"
                     src="' . mysqli_fetch_assoc(mysqli_query($db, 'SELECT DISTINCT image FROM tracks WHERE album = "' . $_GET['album'] . '"'))['image'] . '" 
                     alt="' . $_GET['album'] . ' album cover">
            </div>

            <div class="diagonal-block brand-background">
                <div class="diagonal-block-content">
                    <table class="library-table full-width">';
        //get the tracks of the album
        $tracks = mysqli_query($db, 'SELECT track_id, name, sample, artist, image
                                           FROM tracks 
                                           WHERE album = "' . $_GET['album'] . '"');
        //for each track create a row
        while ($track_row = mysqli_fetch_assoc($tracks)) {
            echo '
                        <tr>
                            <td style="text-align: left;">';
                                echo '<strong><a href="track.php?track_id=' . $track_row['track_id'] . '">' . $track_row['name'] . '</a></strong><br>';
                                echo '<small>by <a href="artist.php?artist=' . $track_row['artist'] . '">' . $track_row['artist'] . '</a></small>';
                            echo '
                            </td>
                        </tr>
                        <tr><td><audio controls class="full-width" preload="metadata"><source src="./' . $track_row['sample'] . '" type="audio/mpeg"></audio></td></tr>';
            }
            echo '
                    </table>
                </div>
            </div>
        </main>
        <footer>
            <div id="creator" class="text-smaller footer-content">
                Created by Dmitry Grebenin. 2021
            </div>
        </footer>
        <script src="./script.js" rel="script" type="application/javascript"></script>
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