<?php
require "connection.php";
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
}elseif ($_SESSION['username'] == "superadmin") {
    header("Location:superadminscreen.php");
}
$name = $_POST['name'];
$id = $_POST['id'];


echo "<table style='height: auto;margin-right: 200px;text-align:center;width:40%;margin-bottom: 15px'><tr><th>Ime</th><th>Prezime</th></tr>";
$sql2 = "SELECT * FROM students s, subject_student ss WHERE (s.firstname LIKE '%$name%' OR s.lastname LIKE '%$name%') AND ss.student_id = s.id AND ss.subject_id = '$id'GROUP BY s.id";
if ($result2 = $conn->query($sql2)) {
    while ($row2 = $result2->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row2['firstname'] . "</td>";
        echo "<td>" . $row2['lastname'] . "</td>";
        echo "</tr>";
    }
}

echo "</table>";
?>
