<?php
include_once('database.php');
checkIfAdmin();
if(!isset($_GET['id'])){
    header('Location: admin-messages.php');
    exit();
}
deleteMessage();
header('Location: admin-messages.php');
?>