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
                <a href="#"><div id="cat-cds" class="cat">CDs</div></a>
                <div id="cat-dvds" class="cat">DVDs</div>
            </div>
            <div id="login">
                <div id="signin"><form action="act/login.php" method="post">
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
                    <p><a href="#">Register here!</a></p>
                </div>
            </div>
        </div>
        <div id="stripe-1"></div>
        <div id="stripe-2"></div>
        <div id="stripe-3"></div>
        
    </div>
    <div id="container"></div>
</body>
</html>