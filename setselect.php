<?php
require 'connection.php';
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
}elseif ($_SESSION['username'] == "superadmin") {
    header("Location:superadminscreen.php");
}
$userID = $_SESSION['id'];
$forbiddenID = [];
$forbiddenCounter = 0;
$takeSubjects = "SELECT * FROM subject_student WHERE student_id = '$userID'";
if($result2 = $conn->query($takeSubjects)){
    while($row2 = $result2->fetch_assoc()){
        $rowID = $row2['subject_id'];
        $forbiddenID[$forbiddenCounter] = $rowID;
        $forbiddenCounter++;

    }
}
            $labelID=0;
            $sql6 = "SELECT * FROM subjects";
            if($result6=$conn->query($sql6)){
                while($row6 = $result6->fetch_assoc()){
                    if(sizeof($forbiddenID)==0 && $row6['id']!=1){
                        echo "<option>".$row6['name']."</option>";
                    }else{
                        for ($i=0;$i<sizeof($forbiddenID);$i++){
                            if($forbiddenID[$i]==$row6['id']){
                                break;
                            }else{
                                if($i==sizeof($forbiddenID)-1 && $row6['id']!=1){
                                    echo "<option>".$row6['name']."</option>";
                                }
                            }
                        }
                    }
                    $labelID=$row6['id'];

                }
            }
