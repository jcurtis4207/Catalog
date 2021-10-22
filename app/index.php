<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>Library Catalog</title>
    <link rel="icon" type="image/png" href="img/favicon.png">
    <link rel="stylesheet" type="text/css" href="style.css?ts=<?=time()?>">
    <script src="sort_table.js"></script>
    <?php include 'create_database.php'; ?>
</head>
<body>
    <div id="title-container">
        <div id="title">
            <h1>Library Catalog</h1>
        </div>
    </div>
    <div id="heading">
        <button id="add_button" onclick="window.location.href='add_book.php'">Add Book</button>
        <select id="genres_dropdown" onchange="filterGenres()">
            <?php include 'get_genres.php'; ?>
        </select>
        <div id="edit-instructions">Click a row to edit the entry</div>
    </div>
    <div id="table">
        <table id="results_table">
            <?php include 'fetch_data.php'; ?>
        </table>
    </div>
    <?php
        if(isset($_SESSION['add_result'])){
            echo '<script language="javascript">';
            echo 'alert("' . $_SESSION['add_result'] . '")';
            echo '</script>';
            unset($_SESSION['add_result']);
        }
        if(isset($_SESSION['edit_result'])){
            echo '<script language="javascript">';
            echo 'alert("' . $_SESSION['edit_result'] . '")';
            echo '</script>';
            unset($_SESSION['edit_result']);
        }
        if(isset($_SESSION['delete_result'])){
            echo '<script language="javascript">';
            echo 'alert("' . $_SESSION['delete_result'] . '")';
            echo '</script>';
            unset($_SESSION['delete_result']);
        }
    ?>
</body>
</html>
