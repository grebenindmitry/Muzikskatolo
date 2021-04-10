<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Muzikskatolo | Library</title>
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
    <a class="button button--select" href="./library.php">
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

//get the order parameter (and default to genre)
$order = $_GET['order'] ?? 'genre';

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
<main>';
$db = mysqli_connect('localhost', 'dgrebenin', 'EkZ8zbAx', 'dgrebenin');

if (!$db) {
    exit("Connection failed!");
}

if (isset($_SESSION['username']) && isset($_SESSION['password_hash'])) {
    //check credentials
    if ($_SESSION['password_hash'] == mysqli_fetch_assoc(mysqli_query($db, 'SELECT password_hash FROM login WHERE username = "' . $_SESSION['username'] . '"'))['password_hash']) {
        echo '
    <form class="flex-row flex-row--center" method="get">
        <input class="text-input margin-small" type="text" name="search" value="' . ($_GET['search'] ?? '') . '">
        <input class="button margin-small" type="submit" value="Search">
    </form>
    <div class="flex-column margin-small">
        <span class="margin-small">Group by: </span>
        <div class="flex-row full-width">
            <a href="./library.php?order=artist" class="button' . ($order == 'artist' ? ' button--select' : '') . '">Artist</a>
            <a href="./library.php?order=album" class="button' . ($order == 'album' ? ' button--select' : '') . '">Album</a>
            <a href="./library.php?order=genre" class="button' . ($order == 'genre' ? ' button--select' : '') . '">Genre</a>
        </div>
    </div>';

        //formulate the query (and apply the where if parameter was specified)
        $order_column = mysqli_query($db, 'SELECT DISTINCT ' . $order . ' FROM tracks');

        //for each genre create a collapsable
        while ($order_row = mysqli_fetch_assoc($order_column)) {
            //get the tracks of a genre
            $tracks = mysqli_query($db, 'SELECT track_id, artist, album, genre, name, sample, thumb, description 
                          FROM tracks 
                          WHERE ' . $order . ' = "' . $order_row[$order] . '"' .
                          (isset($_GET['search']) ? ' AND (LOWER(album) LIKE LOWER("%' . $_GET['search'] . '%") OR 
                                                           LOWER(artist) LIKE LOWER("%' . $_GET['search'] . '%") OR 
                                                           LOWER(name) LIKE LOWER("%' . $_GET['search'] . '%"))' : ''));

            if (mysqli_num_rows($tracks) > 0) {
                echo '
                    <div class="collapsable text-medium">
                        <div class="collapsable-button collapsable-button-inactive">' . $order_row[$order] . '</div>
                        <div class="collapsable-content">
                            <table class="library-table full-width">';//for each track create a row
                while ($track_row = mysqli_fetch_assoc($tracks)) {
                    echo '
                                <tr>
                                    <td colspan="1"><img width="60" height="60" src=" ' . $track_row['thumb'] . '" alt="' . $track_row['album'] . ' album art"></td>
                                    <td colspan="9" style="text-align: left;">';
                    echo '<strong><a href="track.php?track_id=' . $track_row['track_id'] . '">' . $track_row['name'] . '</a></strong><br>';
                    if ($order != 'album') echo '<a href="album.php?album=' . $track_row['album'] . '">' . $track_row['album'] . '</a><br>';
                    if ($order != 'artist') echo '<small>by <a href="artist.php?artist=' . $track_row['artist'] . '">' . $track_row['artist'] . '</a></small><br>';
                    if ($order != 'genre') echo '<small>' . $track_row['genre'] . '</small>';
                    echo '
                                    </td>
                                </tr>
                                <tr><td colspan="10"><audio controls class="full-width" preload="metadata"><source src="./' . $track_row['sample'] . '" type="audio/mpeg"></audio></td></tr>';
                }
                echo '
                                </table>
                            </div>
                        </div>';
            }
        }
        echo '
                </main>
                <footer>
                    <div class="footer-content text-medium">
                        <div id="creator" class="text-smaller">
                            Created by Dmitry Grebenin. 2021
                        </div>
                    </div>
                </footer>
                <script>parseCollapsable();</script>
            </body>
        </html>';
    //wrong credentials
    } else {
        $_SESSION['wrong_pass'] = true;
        echo '<script>window.location.replace("./login.php?redir=' . urlencode($_SERVER['SCRIPT_NAME'] . '?' . $_SERVER['QUERY_STRING']) . '")</script>';
    }
//if no credentials
} else {
    echo '<script>window.location.replace("./login.php?redir=' . urlencode($_SERVER['SCRIPT_NAME'] . '?' . $_SERVER['QUERY_STRING']) . '")</script>';
}