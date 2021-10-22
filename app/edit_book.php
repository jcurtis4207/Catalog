<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>Edit/Delete Book</title>
    <link rel="icon" type="image/png" href="img/favicon.png">
    <link rel="stylesheet" type="text/css" href="style.css?ts=<?=time()?>">
    <?php
        $isbn = $title = $author = $lcno = $year = $read = $genres = '';
        if(isset($_GET['isbn'])){
            $isbn = $_GET['isbn'];
            $db = new SQLite3('DB/catalog.db');
            $sql_books = "SELECT * FROM books WHERE ISBN='$isbn'";
            if($books_response = $db->query($sql_books)){
                while($books_row = $books_response->fetchArray()){
                    $title = $books_row['Title'];
                    $author = $books_row['Author'];
                    $lcno = $books_row['LCNo'];
                    $year = $books_row['Year'];
                    $read = $books_row['Read'];
                    $sql_genres = "SELECT * FROM genres WHERE ISBN='$isbn'";
                    if($genres_response = $db->query($sql_genres)){
                        while($genres_row = $genres_response->fetchArray()){
                            $genres = $genres . $genres_row['Genre'] . ';';
                        }
                    }
                }
            }
            $db->close();
            $genres = substr($genres, 0, -1);
        }
    ?>
</head>
<body>
    <div id="title-container">
        <div id="title">
            <h1>Edit/Delete Book</h1>
        </div>
    </div>
    <div id="form-container">
        <form method="POST" action="edit_book_backend.php">
            <table id="book-form">
                <tr>
                    <td>ISBN</td>
                    <td><input type="text" name="ISBN" value="<?php echo $isbn; ?>" readonly></td>
                </tr>
                <tr>
                    <td>Title</td>
                    <td><input type="text" name="Title" value="<?php echo $title; ?>"></td>
                </tr>
                <tr>
                    <td>Author</td>
                    <td><input type="text" name="Author" value="<?php echo $author; ?>"></td>
                </tr>
                <tr>
                    <td><a href="https://catalog.loc.gov/vwebv/ui/en_US/htdocs/help/searchBrowse.html" target="_blank">LCNo</a></td>
                    <td><input type="text" name="LCNo" value="<?php echo $lcno; ?>"></td>
                </tr>
                <tr>
                    <td>Year</td>
                    <td><input type="text" name="Year" value="<?php echo $year; ?>"></td>
                </tr>
                <tr>
                    <td>Read</td>
                    <td><input type="checkbox" name="Read" <?php if($read){ echo 'checked';}?>></td>
                </tr>
                <tr>
                    <td>Genre</td>
                    <td><input type="text" name="Genre" value="<?php echo $genres; ?>"></td>
                </tr>
            </table>
            <input type="submit" value="Submit" id="submit">
        </form>
        <button id="delete_button" onclick="if(confirm('Are you sure you want to delete this book?')){
            window.location.href='delete_book.php?isbn=<?php echo $isbn; ?>';}">Delete Book</button>
        <button id="home_button" onclick="window.location.href='index.php'">Return Home</button>
    </div>
</body>
</html>

