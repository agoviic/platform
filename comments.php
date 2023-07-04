<?php
require 'connection.php';
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
}elseif ($_SESSION['username'] == "superadmin") {
    header("Location:superadminscreen.php");
}
$value = $subjectID = $userID = $username = "";
if(isset($_POST['value'])){
    $value = $_POST['value'];
    $subjectID = $_POST['id'];
    $userID = $_SESSION['id'];
    $username = $_SESSION['username'];
    $sql = $conn->prepare("INSERT INTO comments (content,subject_id,student_id) VALUES (?,?,?)");
    $sql->bind_param("sii", $value, $subjectID,$userID);
    $sql->execute();
   makeTable();


}
function makeTable(){
    $conn = $GLOBALS['conn'];
    $subjectID = $GLOBALS['subjectID'];
    $userID = $GLOBALS['userID'];
    $username = $_SESSION['username'];
    echo "<table style='height: auto;float: left;margin-left: 25px'> <th>Komentar</th><th>Izmjena</th><th>Brisanje</th>";
    $sql2 = "SELECT * FROM comments WHERE subject_id = '$subjectID'";
    $sql3 = "SELECT * FROM professors WHERE id = '$userID' AND firstname = '$username'";
    if($result3 = $conn->query($sql3)){
        if($result3->num_rows==0){


            if($result2 = $conn->query($sql2)){
                while ($row2 = $result2->fetch_assoc()){
                    if($row2['student_id']==$userID){
                        echo "<tr><td>".$row2['content']."</td><td><button  class='".$row2['id']."' onclick='changeVisibility();take_class(this.className);take_comment()'>IZMIJENI</button></td>
                <td><button id='".$row2['id']."' onclick='delete_comments(this.id)'>IZBRISI</button></td></tr>";
                    }else{
                        echo "<tr><td>".$row2['content']."</td><td>unable</td><td>unable</td></tr>";
                    }

                }
                echo "</table>";
            }
        }else{
            if($result2 = $conn->query($sql2)){
                while ($row2 = $result2->fetch_assoc()){

                    echo "<tr><td>".$row2['content']."</td><td>unable</td>
                <td><button id='".$row2['id']."' onclick='delete_comments(this.id)'>IZBRISI</button></td></tr>";


                }
                echo "</table>";
                $conn->close();
            }
        }
    }
}



?>
