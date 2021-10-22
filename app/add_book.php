<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>Add Book</title>
    <link rel="icon" type="image/png" href="img/favicon.png">
    <link rel="stylesheet" type="text/css" href="style.css?ts=<?=time()?>">
</head>
<body>
    <div id="title-container">
        <div id="title">
            <h1>Add a Book</h1>
        </div>
    </div>
    <div id="form-container">
        <form method="POST" action="add_book_backend.php">
            <table id="book-form">
                <tr>
                    <td>ISBN</td>
                    <td><input type="text" name="ISBN"></td>
                </tr>
                <tr>
                    <td>Title</td>
                    <td><input type="text" name="Title"></td>
                </tr>
                <tr>
                    <td>Author</td>
                    <td><input type="text" name="Author"></td>
                </tr>
                <tr>
                    <td><a href="https://catalog.loc.gov/vwebv/ui/en_US/htdocs/help/searchBrowse.html" target="_blank">LCNo</a></td>
                    <td><input type="text" name="LCNo"></td>
                </tr>
                <tr>
                    <td>Year</td>
                    <td><input type="text" name="Year"></td>
                </tr>
                <tr>
                    <td>Read</td>
                    <td><input type="checkbox" name="Read"></td>
                </tr>
                <tr>
                    <td>Genre</td>
                    <td><input type="text" name="Genre" placeholder='Separate by semicolons'></td>
                </tr>
            </table>
            <input type="submit" value="Submit" id="submit">
        </form>
        <button id="home_button" onclick="window.location.href='index.php'">Return Home</button>
    </div>
</body>
</html>
