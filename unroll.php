<?php
require "connection.php";
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
}elseif ($_SESSION['username'] == "superadmin") {
    header("Location:superadminscreen.php");
}
$id = $_GET['id'];
$userID = $_SESSION['id'];

$sql = "DELETE FROM subject_student WHERE subject_id = '$id' AND student_id='$userID'";
if($conn->query($sql)){
    header("Location: index.php");
}
