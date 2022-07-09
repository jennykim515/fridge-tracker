<?php    
    require "../config/start.php";
    if( !isset($_SESSION["loggedin"]) || !$_SESSION["loggedin"]) {

        if ( isset($_POST['email']) && isset($_POST['pass'])) {
            if(empty($_POST['email']) || empty($_POST['pass'])) {
                $error = "Please fill out all required fields.";
            }
            else {
                $hashed_password = hash("sha256", $_POST["pass"]);
                $statement_registered = $mysqli->prepare("SELECT * FROM  users WHERE email=? AND password=?");
                $statement_registered->bind_param("ss", $_POST["email"], $hashed_password);
                $executed_registered = $statement_registered->execute();
                if(!$executed_registered) {
                    echo $mysqli->error;
                }
                $result = $statement_registered->get_result();
                $numrows =  mysqli_num_rows($result);
                if($numrows == 1) {
                    $_SESSION["loggedin"] = true;
                    $row = $result->fetch_assoc();
                    $_SESSION["name"] = $row["fname"];
                    $_SESSION["id"] = $row["id"];
                    $statement_registered->close();
                    echo '<meta http-equiv="refresh" content="0; URL=../index.php" />';
                }
                else {
                    $error = "Incorrect credentials.";
                }
            }

        }
    }
    else {
        echo '<meta http-equiv="refresh" content="0; URL=../index.php" />';
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="../style/navbar.css">
    <link rel="stylesheet" href="../style/main.css">
    <title>eatsafe</title>
    <style>
        
    </style>
</head>
<body>
    <?php include 'nav.php'; ?>
    <div class="center">
        <div id="form-container">
            <!-- login confirmation.php -->
            <form action="login.php" method="POST">
            <!-- <h2>Sign In</h2><br><br> -->
                <label for="email"><span style="color:red">*</span>Email:</label><br>
                <input type="email" id="email" name="email" required><br>
                <label for="pass"><span style="color:red">*</span>Password:</label><br>
                <input type="text" id="pass" name="pass" required><br>
                <input type="submit" value="Sign In">
                <a href="signup.php"><br><br>don't have an account yet? SIGN UP</a>
            </form>
            <br>
            <?php
            if ( isset($error) && !empty($error) ) {
                // <span style="color:red">*</span>
                echo "<span style='color:red'>" . $error . "</span>";
            }
        ?>
        </div>
    </div>
    <script src="../javascript/toggle.js"></script>
</body>
</html>