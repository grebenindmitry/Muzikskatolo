<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Muzikskatolo | Playlists</title>
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
    <a class="button button--select" href="./playlists.php">
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
        if (isset($_POST['playlist_name'])) {
            if (mysqli_query($db, 'INSERT INTO playlists (playlist_name, user) VALUE ("' . $_POST['playlist_name'] . '", "' . $_SESSION['username'] . '")')) {
                echo '<div class="success-msg text-input">Playlist added</div>';
            } else {
                echo mysqli_error($db);
                echo $_POST['playlist_name'];
                echo '<div class="error-msg text-input">Playlist failed to add</div>';
            }
        }

        $playlists = mysqli_query($db, 'SELECT playlist_id, playlist_name FROM playlists WHERE user = "' . $_SESSION['username'] . '"');

        echo '
    <form method="post" class="gone">
       <input type="text" name="playlist_name" id="playlist-name-field">
       <input type="submit" id="playlist-create-submit">
    </form>';

        echo '<button onclick="addPlaylist()" class="button full-width margin-medium">+ Add Playlist</button>';

        while ($playlist = mysqli_fetch_assoc($playlists)) {
            echo '
    <div class="collapsable text-medium">
        <div class="collapsable-button collapsable-button-inactive">' . $playlist['playlist_name'] . '</div>
        <div class="collapsable-content">
            <table class="library-table full-width">';

            $tracks = mysqli_query($db, '
                                SELECT tracks.track_id, artist, album, genre, name, sample, thumb, description 
                                FROM playlists 
                                LEFT JOIN playlists_contents ON playlists.playlist_id = playlists_contents.playlist_id 
                                LEFT JOIN tracks ON tracks.track_id = playlists_contents.track_id
                                WHERE playlists.playlist_id = "' . $playlist['playlist_id'] . '" AND tracks.track_id IS NOT NULL');

            //for each track create a row
            while ($track_row = mysqli_fetch_assoc($tracks)) {
                echo '
                <tr>
                    <td colspan="1"><img width="60" height="60" src=" ' . $track_row['thumb'] . '" alt="' . $track_row['album'] . ' album art"></td>
                    <td colspan="9" style="text-align: left;">
                        <strong><a href="track.php?track_id=' . $track_row['track_id'] . '">' . $track_row['name'] . '</a></strong><br>
                        <a href="album.php?album=' . $track_row['album'] . '">' . $track_row['album'] . '</a><br>
                        <small>by <a href="artist.php?artist=' . $track_row['artist'] . '">' . $track_row['artist'] . '</a></small><br>
                        <small>' . $track_row['genre'] . '</small>
                    </td>
                </tr>
                <tr><td colspan="10"><audio controls class="full-width" preload="metadata"><source src="./' . $track_row['sample'] . '" type="audio/mpeg"></audio></td></tr>';
            }

            echo '
            </table>
        </div>
    </div>';
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