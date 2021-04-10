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
        //save to playlist
        if (isset($_POST['playlist_id'])) {
            if(mysqli_query($db, 'INSERT INTO playlists_contents (playlist_id, track_id) VALUE (' . $_POST['playlist_id'] . ', ' . $_GET['track_id'] . ')'))
                $success_playlist = true;
            else $fail_playlist = true;
        }

        //submit rating
        if (isset($_POST['track_id'])) {
            if (isset($_POST['username']) && isset($_POST['review']) && isset($_POST['rating']))
                $success_review = true;
            else $fail_review = true;
        }
        //get the track data
        $track = mysqli_fetch_assoc(mysqli_query($db, 'SELECT * FROM tracks WHERE track_id = ' . $_GET['track_id']));

        echo '
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Muzikskatolo | ' . $track['name'] . '</title>
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
        <main>';
        if (isset($success_review)) echo '<div class="success-msg text-input">Review submitted</div>';
        if (isset($fail_review)) echo '<div class="error-msg text-input">Review submission failed 
        (you have probably already submitted a review for this track)</div>';

        if (isset($success_playlist)) echo '<div class="success-msg text-input">Track saved to playlist</div>';
        if (isset($fail_playlist)) echo '<div class="error-msg text-input">Track not saved to playlist 
        (the track is probably already in the playlist)</div>';

        $avg_rating = mysqli_fetch_assoc(mysqli_query($db, 'SELECT ROUND(AVG(rating), 1) AS rating 
                                                                  FROM reviews LEFT JOIN tracks ON reviews.product_id = tracks.track_id
                                                                  WHERE tracks.name = "' . $track['name'] . '"'))['rating'];

        echo '
        <div class="margin-medium"><img class="full-width" src="./' . $track['image'] . '" alt="' . $track['album'] . ' album cover"></div>
        <h1 class="margin-medium">' . $track['name'] . '</h1>
        <h2 class="margin-medium"><a href="./album.php?album=' . $track['album'] . '">' . $track['album'] . '</a></h2>
        <h2 class="margin-medium">By <a href="./artist.php?artist=' . $track['artist'] . '">' . $track['artist'] . '</a></h2>
        <h2 class="margin-medium">Rating: ' . $avg_rating . '</h2>
        <div class="diagonal-block brand-background">
            <div class="diagonal-block-content">
                <h3>Track description:</h3>
                ' . $track['description'] . '
            </div>
        </div>
        <div class="margin-medium">
            <form method="post" action="' . $_SERVER['SCRIPT_NAME'] . '?' . $_SERVER['QUERY_STRING'] . '">
                <label>Save to: 
                    <select name="playlist_id" class="text-input">';
        $playlists = mysqli_query($db, '
            SELECT * 
            FROM playlists 
            WHERE user = "' . $_SESSION['username'] . '"');
        while ($playlist = mysqli_fetch_assoc($playlists)) {
            echo '      <option value="' . $playlist['playlist_id'] . '">' . $playlist['playlist_name'] . '</option>';
        }
        echo '
                    </select>
                </label>
                <input class="button" type="submit" value="Save">
            </form>
        </div>
        <div class="margin-medium">
            <form method="post">
                <fieldset class="flex-column">
                    <legend>Leave a review: </legend>
                    <label class="full-width">Rating: <input id="rating-input" class="text-input" type="number" required name="rating" min="1" max="10">/10</label>
                    <input class="gone" type="text" name="username" value="' . $_SESSION['username'] . '">
                    <input class="gone" type="text" name="track_id" value="' . $_GET['track_id'] . '">
                    <textarea class="text-input full-width" name="review"></textarea>
                    <input class="button" type="submit" value="Post">
                </fieldset>
            </form>
            <table class="review-table full-width table-big text-smaller">
                <caption class="text-xxlarge">User reviews</caption>
                <th colspan="1">User</th>
                <th colspan="2">Review</th>
                <th colspan="1">Rating</th>';
        $reviews_query = mysqli_query($db, 'SELECT user, review, rating FROM reviews WHERE product_id = ' . $_GET['track_id']);

        while ($review = mysqli_fetch_assoc($reviews_query)) {
            echo '<tr>';
            echo '<td colspan="1">' . $review['user'] . '</td>';
            echo '<td colspan="2">' . $review['review'] . '</td>';
            echo '<td colspan="1">' . $review['rating'] . '/10</td>';
            echo '</tr>';
        }
        echo '
            </table>
        </div>
        </main>
        <footer>
            <div id="creator" class="text-smaller footer-content">
                Created by Dmitry Grebenin. 2021
            </div>
        </footer>
    </body>
</html>';
    } //if wrong credentials
    else {
        unset($_SESSION['username']);
        unset($_SESSION['password_hash']);
        $_SESSION['wrong_pass'] = true;
        echo '<script>window.location.replace("./login.php?redir=' . urlencode($_SERVER['SCRIPT_NAME'] . '?' . $_SERVER['QUERY_STRING']) . '")</script>';
    }
} //if no credentials
else {
    echo '<script>window.location.replace("./login.php?redir=' . urlencode($_SERVER['SCRIPT_NAME'] . '?' . $_SERVER['QUERY_STRING']) . '")</script>';
}