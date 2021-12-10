<?php

    session_start();

    require_once "connect.php";

    $conn = @new mysqli($host, $db_user, $db_password, $db_name);

    if ($conn->connect_errno!=0) {
            echo "Error: ".$conn->connect_errno;
    }
    else {

        $conn->query('SET NAMES utf8');
        $conn->query('SET CHARACTER_SET utf8_unicode_ci');
        //$updatelogs = @$conn->query('INSERT INTO logs SET `data`=NOW(), `color`="ff6666", `bg-color`="f2f2f2", `user`="'.$_SESSION['login'].'", `zdarzenie`="Użytkownik wylogował się z panelu."');
        
        $conn->close();
    }

    session_unset();

    header('Location: index.php');

?>