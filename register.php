<?php
include 'connection.php';
if(isset($_SESSION['username'])&& $_SESSION['username']!="superadmin"){
    header("Location: index.php");
}
$firstname = $lastname = $email = $password = $gender = "";
if (isset($_POST['submit'])) {
    $firstname = $_POST['fname'];
    $lastname = $_POST['lname'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $gender = $_POST['gender'];
    if ($gender[0] == "m") {
        $gender = "Muški";
    } else {
        $gender = "Ženski";
    }
    $sql = "SELECT * FROM students s,professors p WHERE s.email = '$email' OR p.email = '$email'";
    if ($conn->query($sql)) {
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            echo "<script>alert('Ova email adresa je vec registrovana!')</script>";
        } else {
            $sql = "SELECT * FROM blocked WHERE email = '$email'";
            if($result=$conn->query($sql)){
                if($result->num_rows>0){
                    echo "<script>alert('Zahtjev za ovu e-mail adresu je već odbijen!')</script>";
                }else{
                    if($_SESSION['username']=="superadmin"){
                        $sql2 = $conn->prepare("INSERT INTO professors (firstname, lastname, email,password,gender) VALUES (?, ?, ?,?,?)");
                        $sql2->bind_param("sssss", $firstname, $lastname, $email, $password, $gender);
                        $sql2->execute();
                        $sql2->close();
                        $conn->close();
                        header('Location:superadminscreen.php');
                    }else{
                    $sql2 = $conn->prepare("INSERT INTO requests (firstname, lastname, email,password,gender) VALUES (?, ?, ?,?,?)");
                    $sql2->bind_param("sssss", $firstname, $lastname, $email, $password, $gender);
                    $sql2->execute();
                    $sql2->close();
                    $conn->close();
                    header('Location:login.php');
                }
                }
            }

        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register</title>
    <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            list-style: none;
            font-family: 'Montserrat', sans-serif;
        }

        body{
            background: linear-gradient(
                    105deg,
                    lightsteelblue,
                    cornflowerblue
            );
        }

        .wrapper{
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            display: flex;
        }

        .registration_form{
            border-radius: 5px;
            width: 400px;
            background: white;
            padding: 25px;
        }

        .registration_form .title{
            color: white;
            background:rgb(13, 98, 215);
            letter-spacing: 2px;
            font-weight: 700;
            text-align: center;
            font-size: 25px;
            text-transform: uppercase;
            margin-top: -25px;
            margin-left:-25px;
            margin-right:-25px;
        }

        .form_wrap{
            margin-top: 35px;
        }

        .form_wrap .input_wrap{
            margin-bottom: 15px;


        }

        .form_wrap .input_wrap:last-child{
            margin-bottom: 0;
        }

        .form_wrap .input_wrap label{
            margin-bottom: 3px;
            color: #1a1a1f;
            display: block;
        }

        .form_wrap .input_grp{
            display: flex;
            justify-content: space-between;
        }

        .form_wrap .input_grp  input[type="text"]{
            width: 165px;
        }

        .form_wrap  input[type="text"]{
            width: 60%;
            padding: 10px;
            outline: none;
            border-radius: 3px;
            border: 1.3px solid #9597a6;
        }

        .form_wrap  input[type="text"]:focus{
            border-color: #063abd;
        }

        .form_wrap ul{
            padding: 8px 10px;
            border-radius: 20px;
            display: flex;
            justify-content: center;
            border:1px solid rgb(115, 185, 235);
            width:70%;
            background: rgb(206, 238, 242);
            margin-left: 15%;
        }

        .form_wrap ul li:first-child{
            margin-right: 15px;
        }

        .form_wrap ul .radio_wrap{
            position: relative;
            margin-bottom: 0;
        }

        .form_wrap ul .radio_wrap .input_radio{
            position: absolute;
            top: 0;
            right: 0;
            opacity: 0;
        }

        .form_wrap ul .radio_wrap span{
            display: inline-block;
            font-size: 17px;
            padding: 3px 15px;
            border-radius: 15px;
            color: #101749;
        }

        .form_wrap .input_radio:checked ~ span{
            background: #105ce2;
            color:white;
        }

        .form_wrap .submit_btn{
            font-size:17px;
            border-radius: 3px;
            text-transform: uppercase;
            letter-spacing: 2px;
            cursor: pointer;
            width: 100%;
            background: #0d6ad7;
            padding: 10px;
            border: 0;
            color:white;
        }

        .form_wrap .submit_btn:hover{
            background: #051c94;
        }
    </style>
    <script>
        let flagName = false;
        let flagLastname = false;
        let flagEmail = false;
        let flagPassword = false;

        function validate() {
            let pattern = /[a-zA-Z]/;
            let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            let name = "";
            let lastName = "";
            let email = "";
            let password = "";
            name = document.getElementById('fname').value;
            lastName = document.getElementById('lname').value;
            email = document.getElementById('email').value;
            password = document.getElementById('password').value;
            if (!name.match(pattern)) {
                document.getElementById('nameSpan').innerHTML = "Morate unijeti ime!";
                document.getElementById('nameSpan').style.color = "red";
                flagName = false;
            } else if (name.length < 3) {
                document.getElementById('nameSpan').innerHTML = "Minimum 3 karaktera!";
                document.getElementById('nameSpan').style.color = "red";
                flagName = false;
            } else {
                document.getElementById('nameSpan').innerHTML = "OK";
                document.getElementById('nameSpan').style.color = "green";
                flagName = true;
            }
            if (!lastName.match(pattern)) {
                document.getElementById('lastnameSpan').innerHTML = "Morate unijeti prezime!";
                document.getElementById('lastnameSpan').style.color = "red";
                flagLastname = false;

            } else {
                document.getElementById('lastnameSpan').innerHTML = "OK";
                document.getElementById('lastnameSpan').style.color = "green";
                flagLastname = true;
            }
            if (!email.match(emailPattern)) {
                document.getElementById('emailSpan').innerHTML = "E-mail nije validan!";
                document.getElementById('emailSpan').style.color = "red";
                flagEmail = false;
            } else {
                document.getElementById('emailSpan').innerHTML = "OK";
                document.getElementById('emailSpan').style.color = "green";
                flagEmail = true;
            }
            if (password.length < 6 || password.length > 15) {
                document.getElementById('passwordSpan').innerHTML = "Mora biti izmedju 6 i 15 karaktera!";
                document.getElementById('passwordSpan').style.color = "red";
                flagPassword = false;
            } else {
                document.getElementById('passwordSpan').innerHTML = "OK";
                document.getElementById('passwordSpan').style.color = "green";
                flagPassword = true;
            }
        }

        function check() {
            if (flagName && flagLastname && flagEmail && flagPassword) {
                document.getElementById('submit').click();
            } else {
                alert("Nevalidno uneseni podaci za registraciju! Provjerite ponovo sva polja");
            }
        }
    </script>
</head>
<body>
<div class="wrapper">
    <div class="registration_form">
        <div class="title">
            Napravi nalog
        </div>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" onkeydown="return event.key != 'Enter';">
            <div class="form_wrap">
                <div class="input_wrap">
                    <div id="input_grp">
                        <label for="fname">Ime</label>
                        <input type="text" id="fname" name="fname" onkeyup="validate()">
                        <span id="nameSpan"></span>
                    </div>
                </div>
                <div class="input_wrap">
                    <div id="input_grp">
                        <label for="lname">Prezime</label>
                        <input type="text" id="lname" onkeyup="validate()" name="lname">
                        <span id="lastnameSpan"></span>
                    </div>
                </div>
                <div class="input_wrap">
                    <div id="input_grp">
                        <label for="email">E-mail adresa</label>
                        <input type="text" id="email" onkeyup="validate()" name="email">
                        <span id="emailSpan"></span>
                    </div>
                </div>
                <div class="input_wrap">
                    <div id="input_grp">
                        <label for="password">Password</label>
                        <input type="text" id="password" onkeyup="validate()" name="password">
                        <span id="passwordSpan"></span>
                    </div>
                </div>
                <div class="input_wrap">
                    <label>Pol</label>
                    <ul>
                        <li>
                            <label class="radio_wrap">
                                <input type="radio" name="gender" value="male" class="input_radio" checked>
                                <span>Musko</span>
                            </label>
                        </li>
                        <li>
                            <label class="radio_wrap">
                                <input type="radio" name="gender" value="female" class="input_radio">
                                <span>Zensko</span>
                            </label>
                        </li>
                    </ul>
                </div>
                <div class="input_wrap">
                    <input type="button" value="Registruj se" onclick="check()" class="submit_btn">
                    <input type="submit" id="submit" name="submit" hidden>
                </div>
            </div>
        </form>
    </div>
</div>
</body>
</html>
