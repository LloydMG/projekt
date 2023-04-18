<?php
include_once('config.php');

function getConnection() {
    global $dbHost, $dbName, $dbUser, $dbPassword;
    $connection = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);
    if($connection->connect_errno) {
        $connection->close();
        die('Database connection problem');
    }
    return $connection;
}

function getAllPosts() {
    $connection = getConnection();
    $sql = 'select posts.id AS id, posts.title AS title, categories.name AS categoryName from posts inner join categories on posts.categoryId=categories.id';
    $result = $connection->query($sql);
    $rows = $result->fetch_all(MYSQLI_ASSOC);
    $connection->close();
    return $rows;
}

function getPost($id) {
    $connection = getConnection();
    $sql = "select posts.id AS id, posts.title AS title, posts.content AS content, posts.createdAt as createdAt, categories.name AS categoryName, admins.firstName AS firstName, admins.lastName AS lastName from posts inner join categories on posts.categoryId=categories.id inner join admins on posts.authorId=admins.id where posts.id = $id";
    $result = $connection->query($sql);
    $rows = $result->fetch_all(MYSQLI_ASSOC);
    $connection->close();
    if(count($rows) == 0) {
        header('Location: 404.php');
        exit();
    }
    return $rows[0];
}