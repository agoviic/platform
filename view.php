<?php
require "connection.php";
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
} elseif ($_SESSION['username'] == "superadmin") {
    header("Location:superadminscreen.php");
}
$id = $_GET['id'];
$userid = $_SESSION['id'];
$name = "";
$description = "";
$unrollButton = "";
$hidden = "";
$hiddenProfessor = "";
$counter = 1;
$counter2 = 0;
$table = "<table>";
$username = $_SESSION['username'];
$searchProfessor = "SELECT * FROM professors WHERE id = '$userid'";
if ($result = $conn->query($searchProfessor)) {
    if ($result->num_rows > 0) {
        $hiddenProfessor = "hidden";
        $sql2 = "SELECT * FROM posts WHERE subject_id = '$id' AND professor_id = '$userid';";
        if ($result2 = $conn->query($sql2)) {
            if ($result2->num_rows == 0) {
                while ($counter < 16) {
                    if ($counter2 == 0) {
                        $table .= "<tr><td style='width: 8%'>" . $counter . ". nedelja.</td><td><a style='text-decoration: none' href='post.php?id=" . $id . "'><button style='margin: auto; display: block;font-size: 19px'>Unesite post</button></a></td></tr>";
                        $counter++;
                        $counter2++;
                    } else {
                        $table .= "<tr><td style='width: 8%'>" . $counter . ". nedelja.</td><td></td></tr>";
                        $counter++;
                    }
                }
                $table .= "</table>";
            } else {
                while ($row2 = $result2->fetch_assoc()) {
                    $table .= "<tr><td style='width: 8%'>" . $counter . ". nedelja.</td><td>
                              <h4 style='text-align: center'>" . $row2['title'] . "</h4>
                               <p style='text-align: center'>" . $row2['content'] . " <br>
                               <a href='editpost.php?id=" . $row2['id'] . "'><button style='margin-top: 10px'>IZMIJENI</button></a>
                               </p></td></tr>";
                    $counter++;
                }
                while ($counter < 16) {
                    if ($counter2 == 0) {
                        $table .= "<tr><td style='width: 8%'>" . $counter . ". nedelja.</td><td><a style='text-decoration: none' href='post.php?id=" . $id . "'><button style='margin: auto; display: block;font-size: 19px'>Unesite post</button></a></td></tr>";
                        $counter++;
                        $counter2++;
                    } else {
                        $table .= "<tr><td style='width: 8%'>" . $counter . ". nedelja.</td><td></td></tr>";
                        $counter++;
                    }
                }
            }
        }
    } else {
        $unrollButton = '<a href="unroll.php?id=' . $id . '" style="text-decoration: none"><button style="margin:0 auto;display: block; font-size: 20px;background: cornflowerblue; color: white;border-color: white; cursor: pointer;">Ispisi se sa predmeta</button></a>';
        $searchStudent = "SELECT * FROM posts WHERE subject_id = '$id'";
        $hidden = "hidden";
        if ($result2 = $conn->query($searchStudent)) {
            if ($result2->num_rows == 0) {
                while ($counter < 16) {

                    $table .= "<tr><td style='width: 8%'>" . $counter . ". nedelja.</td><td></td></tr>";
                    $counter++;

                }
                $table .= "</table>";
            } else {
                while ($row2 = $result2->fetch_assoc()) {
                    $table .= "<tr><td style='width: 8%'>" . $counter . ". nedelja.</td><td><h4 style='text-align: center'>" . $row2['title'] . "</h4><p style='text-align: center'>" . $row2['content'] . "</p></td></tr>";
                    $counter++;
                }
                while ($counter < 16) {
                    $table .= "<tr><td style='width: 8%'>" . $counter . ". nedelja.</td><td></td></tr>";
                    $counter++;
                }
                $table .= "</table>";


            }
        }
    }
}


$sql = "SELECT * FROM subjects WHERE id = '$id'";
if ($result = $conn->query($sql)) {
    $row = $result->fetch_assoc();
    $name = $row['name'];
    $description = $row['description'];
}

$commentTable = "<table style='height: auto;float: left;margin-left: 25px'> <th>Komentar</th><th>Izmjena</th><th>Brisanje</th>";
$sqlComment = "SELECT * FROM comments WHERE subject_id = '$id'";
$findProfessor = "SELECT * FROM professors WHERE id = '$userid' AND firstname = '$username'";
if ($resultprofessor = $conn->query($findProfessor)) {
    if ($resultprofessor->num_rows == 0) {


        if ($resultcomm = $conn->query($sqlComment)) {
            while ($rowcomm = $resultcomm->fetch_assoc()) {
                if ($rowcomm['student_id'] == $userid) {
                    $commentTable .= "<tr><td>" . $rowcomm['content'] . "</td><td><button class='".$rowcomm['id']."' onclick='changeVisibility();take_class(this.className);take_comment()'>IZMIJENI</button></td>
                <td><button  id='" . $rowcomm['id'] . "' onclick='delete_comments(this.id)'>IZBRISI</button></td></tr>";
                } else {
                    $commentTable .= "<tr><td>" . $rowcomm['content'] . "</td><td>unable</td><td>unable</td></tr>";
                }

            }
            $commentTable .= "</table>";
        }
    } else {
        if ($resultcomm = $conn->query($sqlComment)) {
            while ($rowcomm = $resultcomm->fetch_assoc()) {

                $commentTable .= "<tr><td>" . $rowcomm['content'] . "</td><td>unable</td>
                <td><button id='" . $rowcomm['id'] . "' onclick='delete_comments(this.id)'>IZBRISI</button></td></tr>";


            }
            $commentTable .= "</table>";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>View</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        function hideOnClick(){
            document.getElementById('hiddenComment').value = "";

        }

        function take_class(classname) {
            document.getElementById('hiddenComment').value = classname;
        }

        function changeVisibility(){
            if (document.getElementById('changeComment').hidden == true) {
                document.getElementById('changeComment').hidden = false;
                document.getElementById('newComment').hidden = false;
            } else {
                document.getElementById('changeComment').hidden = true;
                document.getElementById('newComment').hidden = true;

            }
        }
        function show() {
            if (document.getElementById('comment').hidden == true) {
                document.getElementById('comment').hidden = false;
                document.getElementById('submitComment').hidden = false;
            } else {
                document.getElementById('comment').hidden = true;
                document.getElementById('submitComment').hidden = true;

            }
        }

        function fetch_data() {
            $.ajax({
                type: 'post',
                url: 'searchStudent.php',
                cache: false,
                data: {
                    name: document.getElementById('searchfield').value,
                    id: parseInt(document.getElementById('subjectID').value),
                },
                success: function (response) {
                    document.getElementById('search').innerHTML = response;
                }
            });
        }

        function fetch_comments() {
            $.ajax({
                type: 'post',
                url: 'comments.php',
                cache: false,
                data: {
                    value: document.getElementById('comment').value,
                    id: parseInt(document.getElementById('subjectID').value),
                },
                success: function (response) {
                    document.getElementById('allComments').innerHTML = response;
                }
            });
        }


        function delete_comments(id) {
            $.ajax({
                type: 'post',
                url: 'deletecomments.php',
                cache: false,
                data: {
                    value: document.getElementById('comment').value,
                    id: parseInt(document.getElementById('subjectID').value),
                    rowid: id,
                },
                success: function (response) {
                    document.getElementById('allComments').innerHTML = response;
                }
            });
        }
        function update_comments() {
            $.ajax({
                type: 'post',
                url: 'updatecomments.php',
                cache: false,
                data: {
                    value: document.getElementById('changeComment').value,
                    id: parseInt(document.getElementById('hiddenComment').value),
                    subjectid:parseInt(document.getElementById('hiddenSubjectID').value),
                },
                success: function (response) {
                    document.getElementById('allComments').innerHTML = response;
                }
            });
        }
        function take_comment() {
            $.ajax({
                type: 'post',
                url: 'takecomment.php',
                cache: false,
                data: {
                    id: parseInt(document.getElementById('hiddenComment').value),
                },
                success: function (response) {
                    document.getElementById('changeComment').innerHTML = response;
                }
            });
        }

        function reveal() {
            if (document.getElementById('searchfield').hidden == true) {
                document.getElementById('searchfield').hidden = false;
                document.getElementById('search').hidden = false;
            } else {
                document.getElementById('searchfield').hidden = true;
                document.getElementById('search').hidden = true;

            }
        }
    </script>
    <style>
        body {
            font-family: Arial;
            margin: 0;
        }

        h1 {
            text-align: center;
        }

        p {
            text-align: center;
        }

        .header {
            padding: 15px;
            text-align: left;
            background: cornflowerblue;
            color: white;
            font-size: 30px;
        }

        table {
            float: right;
            width: 100%;
            height: 850px;
            border: 1px solid black;

        }


        tr, td {
            border: 1px solid black;
        }

        #searchfield {
            width: 12%;
            margin-right: 44%;
            margin-left: 44%;
            margin-bottom: 10px;
        }

        }


    </style>
</head>
<body>
<div class="header">
    <div>
        <a href="index.php" style="text-decoration:none">
            <button style="font-size: 20px; background: cornflowerblue; color: white; border-color: white; cursor: pointer"
                    >Vrati se na glavnu stranicu
            </button>
        </a>
        <a href="logout.php">
            <button style="font-size: 20px; background:  cornflowerblue; color: white; border-color: white; cursor: pointer; margin-left: 790px">
                LOGOUT
            </button>
        </a>
    </div>
    <h1><?php echo $name ?></h1>
    <p><?php echo $description ?></p>
    <?php echo $unrollButton ?>
</div>
<div class="content" style="float: left;width: 100%;display: inline">
    <h1 style="margin-left: 470px">Sadrzaj</h1>
    <input id='searchfield' type="text" hidden data-id="search" onkeyup="fetch_data()">
    <button style="width: 10%;margin-left: 45%;margin-right: 45%;margin-bottom: 10px"
            id='searchbutton' <?php echo $hidden ?> onclick="reveal()">Pretraga studenata
    </button>
    <input type="hidden" value="<?php echo $id ?>" id="subjectID">
    <div id="search">
    </div>
    <div id="comments" style="width: 28%;float: left">
        <button <?php echo $hiddenProfessor ?> onclick="show()"
                                               style="margin-left: 20%;margin-right: 30%;margin-bottom: 15px;width: 40%">
            Ostavi komentar
        </button>
        <br>
        <textarea rows="8" cols="20" style="resize:none;margin-left: 12%" id="comment" name="comment" hidden></textarea>
        <button id="submitComment" onclick="fetch_comments();show()" hidden>Posalji</button>
        <div id="allComments">
            <?php echo $commentTable ?>
        </div>
        <textarea rows="8" cols="20" style="resize:none;margin-left: 12%" id="changeComment" name="changeComment" hidden></textarea>
        <button id="newComment" onclick="update_comments();changeVisibility();hideOnClick()" hidden>Izmijeni</button>
        <input type="hidden" value="" id="hiddenComment">
        <input type="hidden" value="<?php echo $id?>" id="hiddenSubjectID">
    </div>
    <div id="table" style="width: 65%;float: right;margin-right: 20px">
        <?php echo $table ?>
    </div>

</div>
</body>
</html>