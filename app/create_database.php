<?php
    function console_log($output, $with_script_tags = true){
        $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) . ');';
        if($with_script_tags){
            $js_code = '<script>' . $js_code .'</script>';
        }
        echo $js_code;
    }
    
    if(file_exists('DB/catalog.db')){
        console_log("Database Exists");
    }else{
        $db = new SQLite3('DB/catalog.db');
        if(!$db){
            console_log("Couldn't Create Database");
        }
        $sql =<<<EOF
            CREATE TABLE books (
                ISBN TEXT PRIMARY KEY NOT NULL,
                Title TEXT NOT NULL,
                Author TEXT,
                LCNo TEXT,
                Year TEXT,
                Read BOOLEAN
            );
            CREATE TABLE genres (
                ISBN TEXT,
                Genre TEXT,
                PRIMARY KEY(ISBN, Genre),
                FOREIGN KEY (ISBN) REFERENCES books(ISBN)
            );
            EOF;
        $db->exec( 'PRAGMA foreign_keys = ON;' );
        if($db->exec($sql)){
            console_log("Tables Created");
        }else{
            console_log("ERROR: Couldn't Create Tables");
        }
        $db->close();
    }
?>
