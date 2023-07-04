<?php
include 'connection.php';
if(isset($_SESSION['username'])){
    header("Location: index.php");
}
$email = $password = "";
if (isset($_POST['submit'])) {
$email = $_POST['email'];
$password = $_POST['password'];
$sql = "SELECT * FROM requests WHERE email = '$email'";
if ($conn->query($sql)) {
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            echo "<script>alert('Vaš nalog još uvijek nije odobren! Molimo Vas budite strpljivi.')</script>";
        } else {
            echo "<script>alert('Netačna lozinka.')</script>";
        }
    } else {
        $sql = "SELECT * FROM students WHERE email = '$email'";
        $sql2 = "SELECT * FROM professors WHERE email = '$email'";
        if ($conn->query($sql)) {
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                if (password_verify($password, $row['password'])) {
                    $_SESSION['username'] = $row['firstname'];
                    $_SESSION['id'] = $row['id'];
                    header('Location:index.php');
                } else {
                    echo "<script>alert('Netačna lozinka.')</script>";
                }
            } else if ($conn->query($sql2)) {
                $result2 = $conn->query($sql2);
                if ($result2->num_rows > 0) {
                    $row2 = $result2->fetch_assoc();
                    if (password_verify($password, $row2['password'])) {
                        $_SESSION['username'] = $row2['firstname'];
                        $_SESSION['id'] = $row2['id'];

                        header('Location:index.php');
                    } else {
                        echo "<script>alert('Netačna lozinka.')</script>";
                    }
                } else {
                    if ($email == "superadmin" && $password == "superadmin") {
                        $_SESSION['username'] = "superadmin";
                        header('Location:superadminscreen.php');
                    } else if ($email == "superadmin" && $password != "superadmin") {
                        echo "<script>alert('Netačna lozinka.')</script>";
                    } else {
                        echo "<script>alert('Nalog ne postoji.')</script>";
                    }

                }


            } else {
                echo "<script>alert('Nalog ne postoji.')</script>";
            }
            $conn->close();

        }
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
        }
        #main-holder {
            width: 40%;
            height: 50%;
            display: grid;
            justify-items: center;
            align-items: center;
            background-color: white;
            border-radius: 7px;
            box-shadow: 0px 0px 5px 2px darkblue;
        }

        #login-form {
            align-self: flex-start;
            display: grid;
            width: 60%;
            justify-items: center;
            align-items: center;
        }

        .login-form-field::placeholder {
            color: #0d6ad7;
        }

        .login-form-field {
            border: none;
            border-bottom: 1px solid #3a3a3a;
            margin-top: 5px;
            margin-bottom: 10px;
            border-radius: 3px;
            outline: none;
            padding: 0px 0px 5px 5px;
        }

        #submit {
            width: 40%;
            padding: 7px;
            border: none;
            border-radius: 5px;
            color: white;
            font-weight: bold;
            background-color: #0d6ad7;
            cursor: pointer;
            outline: none;
        }
    </style>
</head>
<body>
<main id="main-holder">
    <h1 id="login-header">Login</h1>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" id="login-form">
        <input type="text" name="email" id="email-field" class="login-form-field" placeholder="E-mail">
        <input type="password" name="password" id="password-field" class="login-form-field" placeholder="Password">
        <input type="submit" value="Login" name="submit" id="submit">
        <p>Nemate nalog? <a href="register.php">Registrujte se</a></p>
    </form>
</main>
</body>
</html>
