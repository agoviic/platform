<?php
require('connection.php');
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
} elseif ($_SESSION['username'] != "superadmin") {
    header("Location:index.php");
}
if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $firstname = $lastname = $email = $password = $gender = $admin = "";
    $sql = "SELECT * FROM requests WHERE id = '$id'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    $firstname = $row['firstname'];
    $lastname = $row['lastname'];
    $email = $row['email'];
    $password = $row['password'];
    $gender = $row['gender'];

    $sql = "DELETE FROM requests WHERE id = '$id'";
    $conn->query($sql);
    $sql2 = $conn->prepare("INSERT INTO students (firstname, lastname, email,password,gender) VALUES (?, ?, ?,?,?)");
    $sql2->bind_param("sssss", $firstname, $lastname, $email, $password, $gender);
    $sql2->execute();
    $sql2->close();
    getRequests();
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
            echo "<td> <button type='button' onclick='approve(" . $row['id'] . ")'>Prihvati</button></td>";
            echo "<td> <button type='button' onclick='decline(" . $row['id'] . ")'>Odbij</a></button></td>";
        }
        echo "</table>";
        $conn->close();
    }

}