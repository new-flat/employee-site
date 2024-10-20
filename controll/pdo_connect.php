<?php

function getPdoConnection() {
    try {
        return new PDO('mysql:host=mysql310.phy.lolipop.lan;dbname=LAA1623691-site', "LAA1623691", "root", [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
        ]);
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit;
    }
}

?>