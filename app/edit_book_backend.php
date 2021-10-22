<?php
    session_start();
	function test_input($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $book_outcome = FALSE;
        $genre_outcome = FALSE;
        // get values from form
        $isbn = test_input($_POST['ISBN']);
        $title = test_input($_POST['Title']);
        $author = test_input($_POST['Author']);
        $lcno = test_input($_POST['LCNo']);
        $year = test_input($_POST['Year']);
        $read = (int)isset($_POST['Read']);
        $genre = test_input($_POST['Genre']);
        $genre_array = explode(";", $genre);
        // update values in books table
        $db = new SQLite3('DB/catalog.db');
        $books_sql = $db->prepare('UPDATE books SET Title=:title, Author=:author, LCNo=:lcno, 
            Year=:year, Read=:read WHERE ISBN=:isbn');
        $books_sql->bindParam(':isbn', $isbn, SQLITE3_TEXT);
        $books_sql->bindParam(':title', $title, SQLITE3_TEXT);
        $books_sql->bindParam(':author', $author, SQLITE3_TEXT);
        $books_sql->bindParam(':lcno', $lcno, SQLITE3_TEXT);
        $books_sql->bindParam(':year', $year, SQLITE3_TEXT);
        $books_sql->bindParam(':read', $read, SQLITE3_INTEGER);
        if($books_sql->execute()){
            $book_outcome = TRUE;
        }
        // delete old and insert new values into genres table
        $db->exec("DELETE FROM genres WHERE ISBN='$isbn';");
        $genres_sql = "";
        foreach($genre_array as $item){
            $genres_sql = $genres_sql . "INSERT INTO genres VALUES ('$isbn', trim('$item'));";
        }
        if($db->exec($genres_sql)){
            $genre_outcome = TRUE;
        }
        $db->close();
    }
    if($book_outcome and $genre_outcome){
        $_SESSION['edit_result'] = 'Book Edited Successfully';
    }else{
        $_SESSION['edit_result'] = 'Failed to Edit Book';
    }
    header("Location: index.php");
?>
