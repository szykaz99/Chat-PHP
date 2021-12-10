<?php
if (isset($_POST['mess']) && isset($_POST['from']) && isset($_POST['to'])) {
    $message = $_POST['mess']; $from = $_POST['from']; $to = $_POST['to'];
    $message = htmlentities($message, ENT_QUOTES, "UTF-8");
    $from = htmlentities($from, ENT_QUOTES, "UTF-8");
    $to = htmlentities($to, ENT_QUOTES, "UTF-8");
    require_once('Message.php');
    $newMess = new Message(0);
    $newMess->newMessage($from, $to, $message);
} 