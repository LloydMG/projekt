<?php
include_once('database.php');
checkIfAdmin();
if(!isset($_GET['id'])){
    header('Location: admin-posts.php');
    exit();
}
deletePost();
header('Location: admin-posts.php');
?>