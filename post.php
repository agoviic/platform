<?php
require 'connection.php';
$professorID = 0;
if(!isset($_SESSION['username'])){
    header("Location: login.php");
}elseif ($_SESSION['username']=="superadmin"){
    header("Location:superadminscreen.php");
}
$subjectID = $_REQUEST['id'];
if(isset($_POST['submit'])) {
    $title = $_POST['headline'];
    $content = $_POST['content'];
    $id = $_SESSION['id'];
    $sql = "SELECT * FROM professors WHERE id = '$id'";
    if ($result = $conn->query($sql)) {
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $professorID = $row['id'];
            $sql2 = $conn->prepare("INSERT INTO posts (title, content ,professor_id,subject_id) VALUES (?, ?, ?,?)");
            $sql2->bind_param("ssii", $title, $content, $professorID, $subjectID);
            $sql2->execute();
            $sql2->close();
        }
    }
    $sql2 = "SELECT * FROM subjects WHERE id = '$subjectID'";
    if ($result2 = $conn->query($sql2)) {
        if ($result2->num_rows > 0) {
            $row2 = $result2->fetch_assoc();
            if ($row2['name']=="main"){
                header('Location:index.php');
            }else{
                header('Location:view.php?id='.$subjectID);
            }
        }
    }
}
if($subjectID==1){
    $button = "<a href='index.php'><button style='font-size: 20px; background: lightsteelblue; color: white; border-color: white; cursor: pointer';>Vrati se nazad</button></a>";
}else{
    $button = "<a href='view.php?id=$subjectID'><button style='font-size: 20px; background: lightsteelblue; color: white; border-color: white; cursor: pointer';>Vrati se nazad</button></a>";
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        html {
            height: 100%;
        }

        body {
            height: 100%;
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            display: grid;
            justify-items: center;
            align-items: center;
            background: linear-gradient(
                    105deg,
                    #88beee ,
                    #0a2e73
            );
            color: white;
        }
        h1{
            color: #0d6ad7;
            text-align: center;
        }
        #submit{
            margin: 0 auto;
            font-size: 20px;
            display: block;
        }

    </style>
    <script>
        let flagContent = false;
        let flagHeadline = false;
     function validate(){
         let headline = document.getElementById('headline').value;
         let content  = document.getElementById('content').value;
         if(headline.length==0) {
             document.getElementById('headlineSpan').innerHTML = "Polje za naslov ne smije biti prazno";
             document.getElementById('headlineSpan').style.color = 'darkred';
             flagHeadline = false;
         }else{
             document.getElementById('headlineSpan').style.color = 'lime';
             document.getElementById('headlineSpan').innerHTML = "OK";
             flagHeadline = true;
         }
         if(content.length==0){
             document.getElementById('contentSpan').innerHTML = "Polje za sadržaj ne smije biti prazno";
             document.getElementById('contentSpan').style.color = 'darkred';
             flagContent = false;

         }else{
             document.getElementById('contentSpan').innerHTML = "OK";
             document.getElementById('contentSpan').style.color = 'lime';
             flagContent = true;
         }

         if(flagHeadline && flagContent){
             document.getElementById('submit').disabled = false;
         }else{
             document.getElementById('submit').disabled = true;
         }
     }
    </script>

    <title>POST</title>
</head>
<body>
<?php echo $button?>
<h2>Unesite novi post: </h2>
<form method="post" action="<?php $_SERVER['PHP_SELF']?>">
    <div class="wrapper">
<label for="headline"><b>Unesite naslov posta:</b></label>
<input type="text" name="headline" id="headline" onkeyup="validate()">
    <span id="headlineSpan"></span><br><br>
<label for="content"><b>Unesite tekst posta: </b></label><br>
<textarea cols="45" rows="8" id="content" onkeyup="validate()" name="content" style="resize: none"></textarea>
    <span id="contentSpan"></span><br>
<br>
    <input type="submit" name="submit" id="submit" value="Posalji"  disabled>
    </div>
</form>
</body>
</html>
