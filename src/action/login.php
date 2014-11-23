<div>
    <?php
        if (empty($_POST['username']) || empty($_POST['password'])) {
            echo '<h1>Error signing in</h1>';
            echo '<p>Please fill in your username and password.</p>';
        } else {
            // (Try to) Select row with user's info
            $query = $connection->prepare('SELECT * FROM customer WHERE cid=?');
            $query->bind_param('i', $_POST['username']);
            $query->execute();
            $result = $query->get_result();

            if ($result->num_rows > 0) {
                // Turns query's result into an array with its info
                $result = $result->fetch_assoc();

                if ($_POST['password'] == $result['cpassword']) {
                    // Set username of the session
                    $_SESSION['username'] = $_POST['username'];

                    // Redirect user to main page
                    header("Location: index.php");
                } else {
                    echo '<h1>Error logging in</h1>';
                    echo 'The username and password provided do not match.';
                }
            } else {
                echo '<h1>Error logging in</h1>';
                echo 'The username provided is invalid.';
            }
        }
    ?>
</div>