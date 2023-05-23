<?php
include_once('functions.php');
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

function addPost(){
    $values= ['title','categoryId','content'];
    if(!isPostValid($values)) return;
    $categoryId = $_POST['categoryId'];
    $authorId = $_SESSION['adminId'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $connection = getConnection();
    $sql = "insert into posts(categoryId,authorId,title,content) values('$categoryId', '$authorId', '$title', '$content')";
    $connection->query($sql);
    $connection->close();
    header('Location: admin-posts.php');
}

function getAllCategories() {
    $connection = getConnection();
    $sql = 'select * from categories';
    $result = $connection->query($sql);
    $rows = $result->fetch_all(MYSQLI_ASSOC);
    $connection->close();
    return $rows;
}

function addMessage(){
    $values = ['email','firstName','lastName','content'];
    if(!isPostValid($values)) return;
    $email = $_POST['email'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $content = $_POST['content'];
    $connection = getConnection();
    $sql = "insert into messages(email,firstName,lastName,content) values('$email', '$firstName', '$lastName', '$content')";
    $connection->query($sql);
    $connection->close();
    header('Location: contact.php?succeeded=1');
}

function login(){
    $values = ['email','password'];
    if(!isPostValid($values)) return;
    $email = $_POST['email'];
    $password = $_POST['password'];
    $connection = getConnection();
    $sql = "select * from admins where email='$email'";
    $result = $connection->query($sql);
    $rows = $result->fetch_all(MYSQLI_ASSOC);
    $connection->close();
    if(count($rows) == 0) return;
    if(!password_verify($password,$rows[0]['password'])) return;
    $_SESSION['adminId'] = $rows[0]['id'];
}

function deleteMessage(){
    $id = $_GET['id'];
    $connection = getConnection();
    $sql = "delete from messages where id='$id'";
    $connection->query($sql);
    $connection->close();
}

function getAllMessages(){
    $connection = getConnection();
    $sql = 'select * from messages';
    $result = $connection->query($sql);
    $rows = $result->fetch_all(MYSQLI_ASSOC);
    $connection->close();
    return $rows;
}

function deletePost(){
    $id = $_GET['id'];
    $connection = getConnection();
    $sql = "delete from posts where id='$id'";
    $connection->query($sql);
    $connection->close();
}