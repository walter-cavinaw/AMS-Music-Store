<?php
    if (isset($_GET['id'])) {
        $query = $connection->prepare('SELECT * FROM item WHERE upc=?');
        $query->bind_param('i', $_GET['id']);
        $query->execute();
        $result = $query->get_result();

        $result = $result->fetch_assoc();
        
        echo '<div class="cover_container"><img src="img/covers/';
        
        // If album cover exists in folder, uses it, otherwhise use the (default) unknown album cover
        if (file_exists("img/covers/".$result['upc'].".jpg"))
            echo $result['upc'];
        else
            echo 'unknown';
        
        echo '.jpg" width="128px" height="128px" /></div><div class="album_info">';
        echo '<h1>'.$result['title'].'</h1>';
        echo '<p>'.$result['itemtype'].'</p>';
        echo '<p>'.$result['category'].'</p>';
        echo '<p>'.$result['company'].'</p>';
        echo '<p>'.$result['releaseyear'].'</p>';
        echo '<div id="album_lists">';

        // Print artists of the album
        echo '<div><h2>Artists</h2>';
        $query = $connection->prepare('SELECT * FROM lead_singer WHERE upc=?');
        $query->bind_param('i', $_GET['id']);
        $query->execute();
        $result2 = $query->get_result();
        while ($row = $result2->fetch_assoc())
            echo '<p>'.$row['sname'].'</p>';
        echo '</div>';
        
        // Print artists of the album
        echo '<div><h2>Tracks</h2>';
        $query = $connection->prepare('SELECT * FROM has_song WHERE upc=?');
        $query->bind_param('i', $_GET['id']);
        $query->execute();
        $result2 = $query->get_result();
        while ($row = $result2->fetch_assoc())
            echo '<p>'.$row['title'].'</p>';
        echo '</div>';
        
        echo '</div></div>';
        
        echo '<div class="album_price">';
        echo '<h1>$'.number_format($result['price'], 2).'</h1>';
        echo '<a href="?p=buy&id='.$_GET['id'].'"><div id="buy_album"><h2>Buy</h2>';
        echo '<p>'.$result['stock'].' left in stock</p></div></a></div>';
    } else
        require("404.php");
?>