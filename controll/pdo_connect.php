<?php

function getPdoConnection() {
    try {
        return new PDO('mysql:host=localhost;dbname=php-test', "root", "root", [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
        ]);
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit;
    }
}

?>