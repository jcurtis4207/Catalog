<?php
    echo '<tr>
    <th onclick="sortTable(0, 0)">ISBN</th>
    <th onclick="sortTable(1, 0)">Title &darr;</th>
    <th onclick="sortTable(2, 0)">Author</th>
    <th onclick="sortTable(3, 0)">LCNo</th>
    <th onclick="sortTable(4, 0)">Year</th>
    <th onclick="filterRead()">Read</th>
    <th>Genre</th>
    </tr>';
    $db = new SQLite3('DB/catalog.db');
    $sql_books = "SELECT * FROM books ORDER BY Title ASC";
    if($books_response = $db->query($sql_books)){
        while($books_row = $books_response->fetchArray()){
            $read = ($books_row['Read'] == '1') ? '<div id="filled_cell"></div>' : '';
            echo '<tr onclick=window.location="edit_book.php?isbn=' . $books_row['ISBN'] . '">';
            echo '<td>' . $books_row['ISBN'] . '</td>';
            echo '<td>' . $books_row['Title'] . '</td>';
            echo '<td>' . $books_row['Author'] . '</td>';
            echo '<td>' . $books_row['LCNo'] . '</td>';
            echo '<td>' . $books_row['Year'] . '</td>';
            echo '<td>' . $read . '</td>';
            echo '<td>';
            // get genres for each book
            $isbn = $books_row['ISBN'];
            $sql_genres = "SELECT * FROM genres WHERE ISBN='$isbn'";
            if($genres_response = $db->query($sql_genres)){
                while($genres_row = $genres_response->fetchArray()){
                    echo $genres_row['Genre'] . '<br>';
                }
            }
            echo '</td>';
            echo '</tr>';
        }
    }
    $db->close();
?>
