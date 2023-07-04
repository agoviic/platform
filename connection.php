<?php
$conn = new mysqli("localhost","root","","platforma");
if($conn->error){
    die("Greska u konekciji");
}else{
    session_start();
}




?>
