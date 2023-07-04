<?php
require 'connection.php';
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
}elseif ($_SESSION['username'] == "superadmin") {
    header("Location:superadminscreen.php");
}
$id = "";
if(isset($_POST['id'])){
  $id =  $_POST['id'];
  $sql = "SELEct * FROM comments WHERE id = '$id'";
  if($result = $conn->query($sql)){
      $row = $result->fetch_assoc();
      echo $row['content'];
  }
}
?>
