<?php
    session_start();
    require "config.php";
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ( $mysqli->errno ) {
        echo $mysqli->error;
        exit();
    }
    $loggedin = false;
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']) {
        $loggedin = true;
    }
    $mysqli->set_charset('utf8');
?>