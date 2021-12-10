<?php
session_start();
if (isset($_POST['from']) && isset($_POST['to'])) {
    $from = $_POST['from']; $to = $_POST['to'];
    $from = htmlentities($from, ENT_QUOTES, "UTF-8");
    $to = htmlentities($to, ENT_QUOTES, "UTF-8");
    require_once('Message.php');
    $newRef = new Message(0);
    $newRef->refresh($from, $to);
}