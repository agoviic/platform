<?php
require 'connection.php';
if(!isset($_SESSION['username'])){
    header("Location: login.php");
}elseif ($_SESSION['username']=="superadmin"){
    header("Location:superadminscreen.php");
}
$html = "";
$pageContent = "";
$name = $_SESSION['username'];
$subjects = "<ul>";
$userID = $_SESSION['id'];
$pageID = 0;
$forbiddenID = [];
$forbiddenCounter = 0;
$counter = 0;
$hidden = "";
$checkID = "SELECT * FROM professors WHERE id = '$userID'";
if($result = $conn->query($checkID)){
   if($result->num_rows>0){
       $takeSubjects = "SELECT * FROM subjects WHERE professor_id = '$userID'";
       if($result2 = $conn->query($takeSubjects)){
           while($row2 = $result2->fetch_assoc()){
            $subjects.= "<li><a href='view.php?id=".$row2['id']."'>".$row2['name']."</a></li>";
           }
           $hidden = "hidden";
           $subjects.= "</ul>";
       }
   }else{
       $takeSubjects = "SELECT * FROM subject_student WHERE student_id = '$userID'";
       if($result2 = $conn->query($takeSubjects)){
           while($row2 = $result2->fetch_assoc()){
               $rowID = $row2['subject_id'];
               $forbiddenID[$forbiddenCounter] = $rowID;
               $forbiddenCounter++;
               $sql = "SELECT * FROM subjects WHERE id = '$rowID'";
               if($results=$conn->query($sql)) {
                   $row = $results->fetch_assoc();
                        $subjects .= "<li><a href='view.php?id=" . $rowID . "'>" . $row['name'] . "</a></li>";
               }
           }
           $subjects.= "</ul>";
       }
   }
}



$sql = "SELECT * FROM subjects WHERE name = 'main'";
if($result = $conn->query($sql)){
    if($result->num_rows>0){
        $row = $result->fetch_assoc();
        $pageID = $row['id'];
    }
}
$check = "SELECT * FROM professors WHERE firstname = '$name'";
if($result = $conn->query($check)){
    if($result->num_rows>0){
        $html.="<p style='font-size: 20px;margin-bottom: 50px;margin-top: 30px;
                font-weight: bold;border: 1px solid'>Dodajte novu vijest: ";
        $html.="<a href='post.php?id=".$pageID."'>
        <button style='font-size: 17px;margin-left: 10px'>Kreiraj</button></a></p>";
    }
}

$sql2 = "SELECT * FROM posts WHERE subject_id = '$pageID' ORDER BY id DESC";
if($result = $conn->query($sql2)) {
    while ($row = $result->fetch_assoc()) {
        $pageContent .= "<div style='background-color: white; border:1px solid cornflowerblue; margin-top: 20px ;width: 70%;margin-left:auto;margin-right: auto '>";
        $pageContent .= "<h3 style='color: black'>" . $row['title'] . "</h3>";
        $pageContent .= "<p style='color: black;white-space: pre-line'>" . $row['content'] . "</p>";
        $sql3 = "SELECT * FROM professors WHERE id = '" . $row['professor_id'] . "'";
        if ($result2 = $conn->query($sql3)) {
            if ($result2->num_rows > 0) {
                $row2 = $result2->fetch_assoc();
                $pageContent .= "<h5 style='color: black'> Profesor:" . $row2['firstname'] . " " . $row2['lastname'] . "</h5>";
                if($row['professor_id'] == $_SESSION['id']){
                $pageContent.=  "<a href='editpost.php?id=".$row['id']."'><button style='margin-bottom: 12px; margin-right: 5px'>IZMIJENI</button></a>";
                $pageContent.=  "<a href='delete.php?id=".$row['id']."'><button style='margin-bottom: 12px'>IZBRISI</button></a></div>";

                }else{
                    $pageContent.="</div>";
                }

                $counter++;
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <title>Početna</title>
    <style>
        * {
            box-sizing: border-box;
        }
        body{
            background: linear-gradient(
                    105deg,
                    lightsteelblue,
                    cornflowerblue
            );
            color: white;
        }
        html,body{
            height: 100%;
            margin: 0;
        }

        .column {
            float: left;
            background-color: cornflowerblue;
            border: 1px solid;
            box-shadow: 5px 10px 18px white;
            width: 20%;
            padding: 10px;
            height: 100%;
        }
        .column1{
            float:left;
            border: 1px solid;
            box-shadow: 5px 10px 18px white;
            width: 80%;
            background-color: lightsteelblue;
            padding: 10px;
            text-align: center;

        }
        #enroll{
            width: 70%;
            margin-left: 15%;
            margin-right: 15%;
            margin-top:10px;
        }
        #enrollbutton{
            width: 40%;
            margin-left: 30%;
            margin-right: 30%;
            margin-top:10px;

        }
        #showbutton{
            width: 40%;
            margin-left: 30%;
            margin-right: 30%;
        }


        h4{
            text-align: right;
            margin-right: 10px;
        }
        h2 {
            text-align: center;
        }
    </style>
    <script>
        function enableEnroll(){
            if(document.getElementById('enroll').hidden==true){
                document.getElementById('enroll').hidden=false;
                document.getElementById('enrollbutton').hidden=false;

            }else{
                document.getElementById('enroll').hidden=true;
                document.getElementById('enrollbutton').hidden=true;
            }
        }
        function fetch_data(){
            $.ajax({
                type:'post',
                url: 'enroll.php',
                cache: false,
                data:{
                    name: document.getElementById('enroll').value,
                },
                success: function(response){
                    document.getElementById('currentSubjects').innerHTML = response;
                }
            });
        }

        function setAgain(){
            $.ajax({
                type:'post',
                url: 'setselect.php',
                cache: false,
                data:{
                },
                success: function(response){
                    document.getElementById('enroll').innerHTML = response;
                }
            });
        }

    </script>
</head>
<body>
<h4>Dobrodosao/la <a href="profile.php"><?php echo $_SESSION['username']?></a>&nbsp<a href="logout.php" style="text-decoration: none ;margin-left: 10px">LOGOUT</a></h4>
    <div id="column" class="column">
        <h2 style="text-align: center;color: ">Predmeti</h2>
        <div id="currentSubjects">
            <?php
            echo $subjects;
            ?>
        </div>

      <button id="showbutton" <?php echo $hidden?> onclick="enableEnroll()">SVI PREDMETI</button>
        <select id="enroll" hidden>
            <?php
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
            ?>
        </select>

       <button id='enrollbutton' hidden data-id="<?php echo 'enroll'?>" onclick="fetch_data();setAgain();enableEnroll()">Enroluj se</button>

    </div>
    <div id="column1" class="column1">
        <h1 style="text-align: center;">Vijesti</h1>
        <?php
         echo $html;
         echo $pageContent;
        ?>
    </div>
</body>
</html>
