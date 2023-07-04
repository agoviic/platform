<?php
require "connection.php";
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
}elseif ($_SESSION['username'] == "superadmin") {
    header("Location:superadminscreen.php");
}

$id = $_GET['id'];
$sql = "DELETE FROM posts WHERE id='$id'";
if($conn->query($sql)){
    $conn->close();
    header("Location: index.php");
}
