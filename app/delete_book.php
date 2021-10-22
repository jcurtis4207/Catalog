<?php
    session_start();
    $isbn = $_GET['isbn'];
    $books_outcome = FALSE;
    $genres_outcome = FALSE;
    $db = new SQLite3('DB/catalog.db');
    $books_query = "DELETE FROM books WHERE ISBN='$isbn'";
    if($db->exec($books_query)){
        $books_outcome = TRUE;
    }
    $genres_query = "DELETE FROM genres WHERE ISBN='$isbn'";
    if($db->exec($genres_query)){
        $genres_outcome = TRUE;
    }
    $db->close();
    if($books_outcome and $genres_outcome){
        $_SESSION['delete_result'] = 'Book Deleted Successfully';
    }else{
        $_SESSION['delete_result'] = 'Failed to Delete Book';
    }
    header("Location: index.php");
?>
