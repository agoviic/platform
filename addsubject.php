<?php
require 'connection.php';
if(!isset($_SESSION['username'])){
    header("Location: login.php");
}elseif ($_SESSION['username']!="superadmin"){
    header("Location:index.php");
}
if(isset($_POST['submit'])){
$name = $_POST['title'];
$description = $_POST['description'];
$professor = $_POST['professors'];
$niz = explode(" ",$professor);
$id = 0;
$sql = "SELECT * FROM professors WHERE firstname = '$niz[0]' AND lastname = '$niz[1]'";
if($result = $conn->query($sql)){
    if($result->num_rows>0){
        $row = $result->fetch_assoc();
        $id = $row['id'];
        $sql2 = $conn->prepare("INSERT INTO subjects (name, description,professor_id) VALUES (?,?,?)");
        $sql2->bind_param("ssi", $name, $description,$id);
        $sql2->execute();
        $sql2->close();
        $conn->close();
        header("Location: superadminscreen.php");
    }
}

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
       }
       h1{
           color: #0d6ad7;
           text-align: center;
       }
       #main-holder {
           width: 60%;
           height: 70%;
           background-color: white;
           border-radius: 7px;
           box-shadow: 0px 0px 5px 2px darkblue;
       }

       .element1{
           margin-left: 40px;
       }
       .element2{
           margin-left: 37px;
       }

       .element3{
           margin-left: 40px;
       }

       .element4{
           margin: 0 auto;
           display: block;

       }

       #professors{
           margin-left:25px ;

       }

       #submit{
           margin-top: 30px;
           font-size: 20px;
       }



   </style>
    <script>
        let flagName = false;
        let flagDescription = false;
        function validate(){
            let title = document.getElementById('title').value;
            let description  = document.getElementById('description').value;
            if(title.length==0) {
                document.getElementById('titleSpan').innerHTML = "Polje za ime predmeta ne smije biti prazno";
                document.getElementById('titleSpan').style.color = 'red';
                flagName = false;
            }else{
                document.getElementById('titleSpan').style.color = 'green';
                document.getElementById('titleSpan').innerHTML = "OK";
                flagName = true;
            }
            if(description.length==0){
                document.getElementById('descriptionSpan').innerHTML = "Polje za opis predmeta ne smije biti prazno";
                document.getElementById('descriptionSpan').style.color = 'red';
                flagDescription = false;

            }else{
                document.getElementById('descriptionSpan').innerHTML = "OK";
                document.getElementById('descriptionSpan').style.color = 'green';
                flagDescription = true;
            }
            console.log(description.length);

            if(flagDescription && flagName){
                document.getElementById('submit').disabled = false;
            }else{
                document.getElementById('submit').disabled = true;
            }



        }
    </script>
</head>
<body>
<main id="main-holder">
    <h1 id="login-header">Dodaj predmet</h1>
    <form method="post" action="<?php $_SERVER['PHP_SELF'] ?>" id="login-form">
        <label for="title" class="element1">Unesite ime predmeta: </label>
       <input type="text" name="title" id="title"  class="login-form-field" onkeyup="validate()"><span id="titleSpan"></span><br><br>
        <label for="description" class="element2">Unesite opis predmeta: </label>
        <textarea style="resize: none;" rows="4" name="description" id="description" class="login-form-field" onkeyup="validate()"></textarea>
       <span id="descriptionSpan"></span><br><br>
        <label class="element3" for="professors">Izaberite profesora: </label>
        <select id="professors" name="professors">
            <?php
            $sql = "SELECT * FROM professors";
            if($result = $conn->query($sql)){
               while($row = $result->fetch_assoc()){
                   echo "<option>".$row['firstname']." ".$row['lastname']."</option>";
               }
            }

            ?>

        </select>
        <input type="submit" value="Dodaj predmet" name="submit" id="submit" class="element4">
    </form>
</main>
</body>
</html>
