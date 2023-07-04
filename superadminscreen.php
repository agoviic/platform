<?php
require 'connection.php';
if ($_SESSION['username'] != "superadmin") {
    header("Location:index.php");
} else if (!isset($_SESSION['username'])) {
    header("Location:login.php");
}
$myfile = fopen("izvjestajplatforme.csv", "w") or die("Unable to open file!");

$sql = "SELECT COUNT(id)as professors FROM professors";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$noProfessors = $row['professors'];
$date = date("Y.m.d");
$txt = "Broj profesora: " . $noProfessors . "\n";
$sql = "SELECT COUNT(id)as students FROM students";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$noStudents = $row['students'];
$txt .= "Broj studenata: " . $noStudents . "\n";
$sql = "SELECT COUNT(id)as subjects FROM subjects";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$noSubjects = $row['subjects'] - 1;
$txt .= "Broj predmeta: " . $noSubjects . "\n";
$sql = "SELECT COUNT(id)as posts FROM posts";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$noPosts = $row['posts'];
$txt .= "Broj postova: " . $noPosts . "\n";
$txt .= "Datum izlistavanja: " . $date;
fwrite($myfile, $txt);
fclose($myfile);
?>
<!doctype html>
<html lang="en">
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <style>
        .btn-group button {
            background-color: #0d6ad7;
            border: 1px solid #0a2e73;
            color: white;
            padding: 10px 24px;
            cursor: pointer;
            float: left;
            width: 49%;
            margin: 5px;
        }

        h1 {
            color: white;
        }

        html, body {
            height: 100%;
        }


        body {
            background: linear-gradient(
                    105deg,
                    lightsteelblue,
                    cornflowerblue
            );
        }

        .btn-group:after {
            content: "";
            clear: both;
            display: table;
        }

        .btn-group button:not(:last-child) {
            border-right: none;
        }

        .btn-group button:hover {
            background-color: #6F8FAF;
        }

        .button {
            display: block;
            margin-left: auto;
            margin-right: auto;
            margin-top: 10px;
            width: 35%;
            background-color: #0d6ad7;
            border: 1px solid #008CBA;
            color: white;
            height: 35px;
            text-align: center;
            text-decoration: none;
            transition-duration: 0.4s;
            cursor: pointer;
            border-radius: 5px;

        }

        .button:hover {
            background-color: #008CBA;
        }

        p {
            margin-top: 40px;
            color: white;
            text-align: center;
            font-size: 20px;
        }

        table {
            margin-top: 35px;
            margin-left: auto;
            margin-right: auto;
            width: 60%;
            text-align: center;
            background-color: blue;
            color: white;
        }

        body, body, * {
            font-family: 'Montserrat';
            font-size: 20px;
        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SUPERADMIN PANEL</title>
    <script>
        let selector = "";

        function getValue(id) {
            selector = id;
        }

        function fetch_data() {
            $.ajax({
                type: 'post',
                url: 'superadmin.php',
                data: {
                    id: selector,
                },
                success: function (response) {
                    document.getElementById('table').innerHTML = response;
                }
            });
        }
        function approve(id) {
            $.ajax({
                type: 'post',
                url: 'approve.php',
                data: {
                    id: id,
                },
                success: function (response) {
                    document.getElementById('table').innerHTML = response;
                }
            });
        }
        function decline(id) {
            $.ajax({
                type: 'post',
                url: 'decline.php',
                data: {
                    id: id,
                },
                success: function (response) {
                    document.getElementById('table').innerHTML = response;
                }
            });
        }

    </script>
</head>
<body>
<h1 style="text-align: center">SUPERADMIN PANEL <a href="logout.php"> LOGOUT</a></h1>
<div class="btn-group">
    <a href="register.php">
        <button id="addNew">Dodaj novog profesora</button>
    </a>
    <a href="addsubject.php">
        <button id="addSubject">Dodaj novi predmet</button>
    </a>
    <button id="showRequests" onclick="getValue(this.id);fetch_data()">Pregled zahtjeva</button>
    <button id="showAll" onclick="getValue(this.id);fetch_data()">Pregled svih korisnika</button>

</div>

    <div id="table" style="margin-left: auto;margin-right: auto" name="table">
    </div>
<a style="text-decoration: none" href="izvjestajplatforme.csv" download="izvjestajplatforme.csv">
    <button class="button">Odštampaj izvještaj</button>
</a>

<p>Klikom na određeno polje otvorite njegov sadržaj</p>
</body>
</html>