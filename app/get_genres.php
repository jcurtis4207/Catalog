<?php
    echo '<option value="All">Show All Genres</option>';
    $db = new SQLite3('DB/catalog.db');
    $sql = "SELECT DISTINCT Genre FROM genres ORDER BY Genre ASC";
    if($response = $db->query($sql)){
        while($row = $response->fetchArray()){
            echo '<option value="' . $row['Genre'] . '">' . $row['Genre'] . '</option>';
        }
    }
    $db->close();
?>
