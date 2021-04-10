<!--
Created by Dmitry Grebenin
2021
-->

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Muzikskatolo | About</title>
        <link href="./style.css" rel="stylesheet" type="text/css">
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
            <a class="button button--select" href="./about.php">
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
                The app follows the standards of WCAG up to at least level A with most level AA and some level AAA
                requirements met. The navigation is always in the bottom of the display as mobile users tend to use
                their
                phones with one hand and reaching the top part of the display is difficult (especially with the advent
                of
                tall diplay aspect ratios) (Fei, 2013). The company's brand color is used as an accent in some sections
                (the
                membership table and artist biography background, and the library). The app is responsive and supports
                most
                smartphone, tablet and desktop displays. The images have been scaled down appropriately to reduce
                network
                usage. The name of the app stands for music box in Esperanto.<br><br>

                <em>Fei, Q. (2013). Designing for a thumb: An Ideal Mobile Touchscreen Interface for Chinese Users.
                    In: A. Marcus, ed., International Conference of Design, User Experience, and Usability. [online]
                    Springer
                    Berlin Heidelberg, pp.44–53. Available at:
                    https://link.springer.com/chapter/10.1007/978-3-642-39241-2_6
                    [Accessed 6 Dec. 2020].</em>
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
