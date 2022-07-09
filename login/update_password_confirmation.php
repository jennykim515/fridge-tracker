<?php
    require "../config/start.php";
    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"]) {
        if ( !isset($_POST['old-password']) || empty($_POST['old-password'])
        || !isset($_POST['new-password']) || empty($_POST['new-password']) ) {
            $error = "Please fill out all required fields.";
        }
        else {
            $hashed_old_password = hash("sha256", $_POST["old-password"]);
            $sql = $mysqli->prepare("SELECT * FROM users WHERE id=?;");
            $sql->bind_param("i", $_SESSION["id"]);
            $executed = $sql->execute();
            if(! $executed) {
                echo $mysqli->error;
            }
            $result = $sql->get_result();
            $row = $result->fetch_assoc();
            $password = null;
            if ($row) {
                $password = $row['password'];
            }
            if($password != $hashed_old_password) {
                $error = "incorrect password";
                $sql->close();
            }
            else {
                $hashed_new_password = hash("sha256", $_POST["new-password"]);
                $insert_sql = $mysqli->prepare("UPDATE users SET password=? WHERE id=?");
                $insert_sql->bind_param("si", $hashed_new_password, $_SESSION["id"]);
                $executed = $insert_sql->execute();
                if(! $executed) {
                    echo $mysqli->error;
                }
                $insert_sql->close();
            }
            $mysqli->close();
        }
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
    <?php if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"]): ?>
        <div class="center">
            <div class="text">
                <?php if ( isset($error) && !empty($error) ) : ?>
                    <h3><?php echo $error ?></h3>
                    <br>
                    <a href="update_password.php">Go back</a>
                <?php else : ?> 
                    <h3>Password updated.</h3><br>
                    <a href="../index.php">Go back</a>
                <?php endif; ?>
            </div>
        </div>
    <?php else: ?>
    <?php echo '<meta http-equiv="refresh" content="0; URL=login.php" />'; ?>
    <?php endif; ?>
</body>
</html>