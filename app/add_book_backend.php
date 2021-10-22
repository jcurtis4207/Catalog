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
        // input values into books table
        $db = new SQLite3('DB/catalog.db');
        $books_sql = $db->prepare('INSERT INTO books VALUES (:isbn, :title, :author, :lcno, :year, :read)');
        $books_sql->bindParam(':isbn', $isbn, SQLITE3_TEXT);
        $books_sql->bindParam(':title', $title, SQLITE3_TEXT);
        $books_sql->bindParam(':author', $author, SQLITE3_TEXT);
        $books_sql->bindParam(':lcno', $lcno, SQLITE3_TEXT);
        $books_sql->bindParam(':year', $year, SQLITE3_TEXT);
        $books_sql->bindParam(':read', $read, SQLITE3_INTEGER);
        if($books_sql->execute()){
            $book_outcome = TRUE;
        }
        // input values into genres table
        $genres_sql = "";
        foreach($genre_array as $item){
            $genres_sql = $genres_sql . "INSERT INTO genres VALUES ('$isbn', trim('$item'));";
        }
        if($db->exec($genres_sql)){
            $genre_outcome = TRUE;;
        }
        $db->close();
        if($book_outcome and $genre_outcome){
            $_SESSION['add_result'] = 'Book Added Successfully';
        }else{
            $_SESSION['add_result'] = 'Failed to Add Book';
        }
        header("Location: index.php");
    }
?>
