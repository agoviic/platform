<?php
require 'connection.php';
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
}elseif ($_SESSION['username'] == "superadmin") {
    header("Location:superadminscreen.php");
}
$subjectName = $_POST['name'];
$userID = $_SESSION['id'];
$subjectID = 0;
$sql3 = "SELECT * FROM subjects WHERE name = '$subjectName'";
if($results=$conn->query($sql3)) {
    $row = $results->fetch_assoc();
    $subjectID = $row['id'];
}
$sql = $conn->prepare("INSERT INTO subject_student (subject_id, student_id) VALUES (?, ?)");
$sql->bind_param("ii", $subjectID, $userID);
$sql->execute();
$sql->close();
$sql2 = "SELECT * FROM subject_student WHERE student_id = '$userID'";
if($results=$conn->query($sql2)) {
    echo "<ul>";
    while($row = $results->fetch_assoc()){
        $sql4="SELECT * FROM subjects WHERE id = '".$row['subject_id']."'";
        if($results2=$conn->query($sql4)) {

                while($row2 = $results2->fetch_assoc()){
                    $sql4="SELECT * FROM subjects WHERE id = '".$row['id']."'";
                    echo "<li><a href='view.php?id=" . $subjectID . "'>" . $row2['name'] . "</a></li>";
                }
            }

    }
    echo "</ul>";
}
$conn->close();

?>
