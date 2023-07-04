<?php
include 'connection.php';
if(!isset($_SESSION['username'])){
    header("Location: login.php");
}elseif ($_SESSION['username']!="superadmin"){
    header("Location:index.php");
}
$id = "";
$id = $_POST['id'];
if ($id == "showRequests") {
    $admin = "";
    getRequests();

}elseif ($id == "showAll") {
    $sql = "SELECT * FROM professors";
    if ($conn->query($sql)) {
        $result = $conn->query($sql);
        echo"<p>Profesori: </p>";
        echo "<table><tr> <th>Ime</th><th>Prezime</th><th>E-mail</th><th>Pol</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['firstname'] . "</td>";
            echo "<td>" . $row['lastname'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td>" . $row['gender'] . "</td>";
        }
        echo "</table>";
         }
    $sql = "SELECT * FROM students";
    if ($conn->query($sql)) {
        $result = $conn->query($sql);
        echo"<p>Studenti: </p>";
        echo "<table><tr> <th>Ime</th><th>Prezime</th><th>E-mail</th><th>Pol</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['firstname'] . "</td>";
            echo "<td>" . $row['lastname'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td>" . $row['gender'] . "</td>";
        }
        echo "</table>";
        $conn->close();

    }
}

function getRequests(){
    $conn = $GLOBALS['conn'];
    $sql = "SELECT * FROM requests";
    if ($conn->query($sql)) {
        $result = $conn->query($sql);
        echo "<table><tr> <th>Ime</th><th>Prezime</th><th>E-mail</th><th>Pol</th>
          <th>Prihvati</th><th>Odbij</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['firstname'] . "</td>";
            echo "<td>" . $row['lastname'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td>" . $row['gender'] . "</td>";
            echo "<td> <button type='button' onclick='approve(". $row['id'].")'>Prihvati</button></td>";
            echo "<td> <button type='button'  onclick='decline(". $row['id'].")'>Odbij</button></td>";
        }
        echo "</table>";
        $conn->close();
    }

}
?>

