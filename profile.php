<?php
require 'connection.php';
if(!isset($_SESSION['username'])){
    header("Location: login.php");
}elseif ($_SESSION['username']=="superadmin"){
    header("Location:superadminscreen.php");
}
$id = $_SESSION['id'];
$name = $_SESSION['username'];
$list = "";
$isProfessor = 0;
$firstname=$lastname=$email=$gender="";

$sql = "SELECT * FROM students WHERE id = '$id' AND firstname = '$name'";
$sql2 = "SELECT * FROM professors WHERE id = '$id'";
$sql3 = "SELECT * FROM subjects WHERE professor_id = '$id'";
$sql4 = "SELECT * FROM subject_student WHERE student_id = '$id'";
if ($conn->query($sql)) {
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $firstname = $row['firstname'];
        $lastname = $row['lastname'];
        $email = $row['email'];
        $gender = $row['gender'];
        $isProfessor = 1;
        if ($result4 = $conn->query($sql4)) {
            if ($result4->num_rows > 0) {
                while ($row4 = $result4->fetch_assoc()) {
                    $sql5="SELECT * FROM subjects WHERE id = '".$row4['subject_id']."'";
                    if($results5=$conn->query($sql5)) {
                        while($row5 = $results5->fetch_assoc()){
                            $list .= "<li>" . $row5['name'] . "</li>";
                        }
                    }
                }
            }
        }

    } else if ($conn->query($sql2)) {
        $result2 = $conn->query($sql2);
        if ($result2->num_rows > 0) {
            $row2 = $result2->fetch_assoc();
            $firstname = $row2['firstname'];
            $lastname = $row2['lastname'];
            $email = $row2['email'];
            $gender = $row2['gender'];
            if ($result3 = $conn->query($sql3)) {
                if ($result3->num_rows > 0) {
                    while ($row3 = $result3->fetch_assoc()) {
                        $list .= "<li>" . $row3['name'] . "</li>";
                    }
                }
            }
        }
    }
}
if(isset($_POST['submitPassword'])){
    $password = $_POST['changePassword'];
    $passwordHashed = password_hash($password,PASSWORD_DEFAULT);
    if($isProfessor ==0){
        $sqlPass = $conn->prepare("UPDATE professors SET password = ? WHERE id = ? ");
        $sqlPass->bind_param("si", $passwordHashed, $id);
        $sqlPass->execute();
        $sqlPass->close();
        $conn->close();
    }else{
        $sqlPass2 = $conn->prepare("UPDATE students SET password = ? WHERE id = ? ");
        $sqlPass2->bind_param("si", $passwordHashed, $id);
        $sqlPass2->execute();
        $sqlPass2->close();
        $conn->close();
    }

}
if(isset($_POST['submitEmail'])){
    $email = $_POST['changeEmail'];
    if($isProfessor ==0){
        $sqlEmail = $conn->prepare("UPDATE professors SET email = ? WHERE id = ? ");
        $sqlEmail->bind_param("si", $email, $id);
        $sqlEmail->execute();
        $sqlEmail->close();
        $conn->close();
    }else{
        $sqlEmail2 = $conn->prepare("UPDATE students SET email = ? WHERE id = ? ");
        $sqlEmail2->bind_param("si", $email, $id);
        $sqlEmail2->execute();
        $sqlEmail2->close();
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body{
            background: cornflowerblue;
        }
        h2{
            margin-top: 50px;
            text-align: center;
            color: white;
        }
        .card {
            box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
            transition: 0.3s;
            width: 50%;
            height: 400px;
            margin-left: auto;
            margin-right: auto;
            background: white;
        }
        .container {
            padding: 2px 16px;
        }
        .header {
            padding: 15px;
            text-align: left;
            background: cornflowerblue;
            color: white;
            font-size: 30px;
        }
    </style>
    <script>
        let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        function checkPassword(){
            if (document.getElementById('changePassword').value.length < 6 || document.getElementById('changePassword').value.length > 15) {
                document.getElementById('passwordSpan').innerHTML = "Mora biti izmedju 6 i 15 karaktera!";
                document.getElementById('passwordSpan').style.color = "red";
                document.getElementById('submitPassword').disabled = true;

            }else {
                document.getElementById('passwordSpan').innerHTML = "OK";
                document.getElementById('passwordSpan').style.color = "green";
                document.getElementById('submitPassword').disabled = false;

            }
        }
        function checkEmail(){
            if (!document.getElementById('changeEmail').value.match(emailPattern)) {
                document.getElementById('emailSpan').innerHTML = "E-mail nije validan!";
                document.getElementById('emailSpan').style.color = "red";
                document.getElementById('submitEmail').disabled = true;
            } else {
                document.getElementById('emailSpan').innerHTML = "OK";
                document.getElementById('emailSpan').style.color = "green";
                document.getElementById('submitEmail').disabled = false;
            }
        }
        function validatePassword(){
            if(document.getElementById('labelPassword').hidden == true){
       document.getElementById('labelPassword').hidden = false;
       document.getElementById('changePassword').hidden = false;
       document.getElementById('submitPassword').hidden = false;
       document.getElementById('passwordSpan').hidden = false;

            }else{
       document.getElementById('labelPassword').hidden  = true;
       document.getElementById('changePassword').hidden = true;
       document.getElementById('submitPassword').hidden = true;
       document.getElementById('passwordSpan').hidden = true;
            }
        }
        function validateEmail(){
            if(document.getElementById('labelEmail').hidden == true){
                document.getElementById('labelEmail').hidden = false;
                document.getElementById('changeEmail').hidden = false;
                document.getElementById('submitEmail').hidden = false;
                document.getElementById('emailSpan').hidden = false;

            }else{
                document.getElementById('labelEmail').hidden  = true;
                document.getElementById('changeEmail').hidden = true;
                document.getElementById('submitEmail').hidden = true;
                document.getElementById('emailSpan').hidden = true;


            }
        }

    </script>
</head>
<body>

<div class="header">
    <div>
        <a href="index.php"><button style="font-size: 20px; background:  cornflowerblue; color: white; border-color: white; cursor: pointer";>Vrati se na glavnu stranicu</button></a>
        <a href="logout.php"><button style="font-size: 20px; background:  cornflowerblue; color: white; border-color: white; cursor: pointer; margin-left: 790px">LOGOUT</button></a>
        <h3 style="text-align: center;">Profil</h3>
    </div>
</div>
<div class="card">
    <div class="container">
        <p style="">Ime i prezime: <b><?php echo $firstname." ". $lastname?></b></p>
        <p style="">E-mail adresa: <b><?php echo $email?></b></p>
        <p style="">Pol: <b><?php echo $gender?></b></p>
        <p style="">Predmeti:  <ul style=""><?php echo $list?></ul></p>
        <button onclick="validatePassword()">Promijeni lozinku</button>
        <button onclick="validateEmail()">Promijeni e-mail</button>
        <form action="<?php $_SERVER['PHP_SELF']?>" method="post">
        <br><br><label for="changePassword" id="labelPassword" hidden>Unesite novu lozinku: </label>
        <input type="text" onkeyup="checkPassword()" id="changePassword" name="changePassword" style="margin-left: 37px" hidden>
        <span id="passwordSpan"></span>
        <input type="submit" name="submitPassword" id="submitPassword" value="Pošalji" hidden disabled><br><br>
        <label for="changeEmail" id="labelEmail" hidden>Unesite novu e-mail adresu: </label>
        <input type="text" onkeyup="checkEmail()" id="changeEmail" name="changeEmail" hidden>
        <span id="emailSpan"></span>
        <input type="submit" name="submitEmail" id="submitEmail" value="Pošalji" hidden disabled>
        </form>

    </div>
</div>

