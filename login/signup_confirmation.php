<?php 
    if ( !isset($_POST['email']) || empty($_POST['email'])
	|| !isset($_POST['fname']) || empty($_POST['fname'])
    || !isset($_POST['lname']) || empty($_POST['lname'])
	|| !isset($_POST['pass']) || empty($_POST['pass']) ) {
	    $error = "Please fill out all required fields.";
    }
    else {
        require "../config/start.php";
        $statement_registered = $mysqli->prepare("SELECT * FROM  users WHERE email=?");
        $statement_registered->bind_param("s", $_POST["email"]);
        $executed_registered = $statement_registered->execute();
        if(!$executed_registered) {
            echo $mysqli->error;
        }
        $statement_registered->store_result();
        // then we can get how many rows we got back
        $numrows = $statement_registered->num_rows;
        $statement_registered->close();

        if($numrows > 0) {
            $error = "Username or email has already been taken. Please choose another one.";
            $alreadyExists = true;
        }
        else {
            // use hash sha256 hashing algorithm
            $password = hash("sha256", $_POST["pass"]);
            // generate insert statement
            $statement = $mysqli->prepare("INSERT INTO users(fname, lname, email, password) VALUES(?,?,?,?)");
            $statement->bind_param("ssss", $_POST["fname"], $_POST["lname"], $_POST["email"], $password);

            $executed = $statement->execute();
            if(!$executed) {
                echo $mysqli->error;
            }
            else {
                $statement2 = $mysqli->prepare("SELECT * FROM users WHERE email = ?");
                $statement2->bind_param("s", $_POST["email"]);
                $statement2_executed = $statement2->execute();
                if(!$statement2_executed) {
                    echo $mysqli->error;
                }
                else {
                    $result = $statement2->get_result();
                    $row = $result->fetch_assoc();
                    if($row) {
                        $_SESSION["loggedin"] = true;
                        $_SESSION["id"] = $row["id"];
                        $_SESSION["name"] = $row["fname"];
                    }
                }
            }
            $statement->close();
        }
        $mysqli->close();
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
</head>
<body>
    <div class="center">
        <div class="text">
            <?php if ( isset($error) && !empty($error) ) : ?>
                <h3><?php echo $error ?></h3><br>
                <a href="signup.php">Go back</a>
            <?php else : ?>
                <h3><?php echo "Your account is now registered."?></h3>
                <a href="../index.php">Go back</a>
            <?php endif; ?>
        </div>
    </div>
    <script src="../javascript/toggle.js"></script>
</body>
</html>