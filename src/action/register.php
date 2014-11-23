<div>
    <?php
        if (!empty($_POST['password']) && !empty($_POST['name']) && !empty($_POST['address']) && !empty($_POST['phone'])) {
                // Add new member to database
                $query = $connection->prepare('INSERT INTO customer (cpassword, cname, address, phone) VALUES (?, ?, ?, ?)');
                $query->bind_param('ssss', $_POST['password'], $_POST['name'], $_POST['address'], $_POST['phone']);
                $query->execute();

                // Check if intert was successful
                if ($query->affected_rows >= 1) {
                    echo '<h1>New user registered with success</h1>';
                    echo '<p>The new user has been successfully added to the database.</p>';
                    echo '<p><a href="index.php">Go to the main page</a></p>';
                } else {
                    echo '<h1>Error registering</h1>';
                    echo '<p>New user could not be added to the database. Please check the data you input and try again.</p>';
                    echo '<p><a href="javascript:history.go(-1)">Go Back</a></p>';
                }
        } else {
            echo '<h1>Error registering</h1>';
            echo '<p>You must fill in all of the fields to be able to register.</p>';
            echo '<p><a href="javascript:history.go(-1)">Go Back</a></p>';
        }
    ?>
</div>