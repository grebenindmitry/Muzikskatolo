<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Muzikskatolo</title>
        <link type="text/css" rel="stylesheet" href="./style.css">
        <link type="text/css" rel="stylesheet" href="./index.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="./favicon.ico" type="image/x-icon">
    </head>
    <body>
        <nav>
            <a class="button button--select" href="./">
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
            <div id="headphones-background-container" class="flex-column">
                <p id="atm">All the music</p>
                <img id="headphones-background" src="./resources/images/headphones.jpg" alt="headphones backgound" height="853" width="1280">
                <p id="att">all the time</p>
            </div>

            <div id="membership-levels" class="diagonal-block brand-background">
                <div id="membership-levels-content" class="diagonal-block-content">
                    <table class="table-big center-align text-large">
                        <caption class="text-xxlarge">Membership levels</caption>
                        <tr>
                            <th>Level</th>
                            <th>Description</th>
                            <th>Price</th>
                            <?php
                            //start a session if not started already
                            if (!isset($_SESSION)) {
                                session_start();
                            }
                            $db = mysqli_connect('localhost', 'dgrebenin', 'EkZ8zbAx', 'dgrebenin');

                            //check if connected
                            if (!$db) {
                                exit("Connection failed!");
                            }

                            //get the offers
                            $result = mysqli_query($db, 'SELECT title, price, description FROM offers');

                            while ($row = mysqli_fetch_assoc($result)) {
                                echo '<tr><th>' . $row['title'] . '</th>';
                                echo '<td>' . $row['description'] . '</td>';
                                echo '<td>£' . $row['price'] . '</td></tr>';
                            }
                            ?>
                        </tr>
                    </table>
                </div>
            </div>
            <div>
                <div class="text-xlarge">
                    Featured artist
                </div>
                <a href="./artist.php?artist=Disoriented Scoundrel">
                    <div class="flex-column">
                        <img id="featured-pic" src="./images/chapintherecess.jpg" alt="Chap in the recess album cover">
                        <label class="text-large">Disoriented Scoundrel</label>
                    </div>
                </a>
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
