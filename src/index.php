<?php
    // Start Session
    session_start();
    $loggedin = (isset($_SESSION['username']));
    if ($loggedin)
        $user = $_SESSION['username'];

    // Connect to database
    require("config.php");
    $connection = @new mysqli($db_host, $db_user, $db_password, $db_name); // @ used to suppress errors (which will be handled later)
                                                                           // if debugging, you may want to remove @
?>
<html>
<head>
    <title>AMS Music Store</title>
    <link rel="stylesheet" href="css/style.css" media="all" />
</head>

<body>
    <div id="header">
        <div id="hcontent">
            <img src="img/logo.png" />
            <div id="categories">
                <a href="?p=list_items"><div id="cat-all" class="cat">All</div></a>
                <a href="?p=list_items&cat=cd"><div id="cat-cds" class="cat">CDs</div></a>
                <a href="?p=list_items&cat=dvd"><div id="cat-dvds" class="cat">DVDs</div></a>
            </div>
            <div id="login">
                <div id="signin"><form action="?act=login" method="post">
                    <div id="signin-fields">
                        <input id="username" placeholder="Username" type="text" /><br />
                        <input id="password" placeholder="Password" type="password" />
                    </div>
                    <div id="signin-button">
                        <input name="signin" value="Sign in" type="submit" />
                    </div>
                </form></div>
                <div id="register-link">
                    <p>Don't have an account?</p>
                    <p><a href="?p=register">Register here!</a></p>
                </div>
            </div>
        </div>
        <div id="stripe-1"></div>
        <div id="stripe-2"></div>
        <div id="stripe-3"></div>
        
    </div>
    <div id="container">
        <div id="content">
        <?php
            // Handle possible connection errors
            if (mysqli_connect_errno()) {
                echo '<h1>Connection Error</h1>';
                echo '<p>There was an error connecting to the database: '.mysqli_connect_error().'</p>';
            } else {
                // Decide which page to require
                if (isset($_GET['p']))
                    $page = $_GET['p'] . ".php";
                elseif (isset($_GET['act']))
                    $page = "action/" . $_GET['act'] . ".php";
                else
                    $page = "list_items.php";

                // Require page
                if (file_exists($page))
                        require $page;
                    else
                        require "404.php";
            }
        ?>
        </div>
    </div>
</body>
</html>