<?php
    require "../config/start.php";
    $isUpdated = false;
    if(isset($loggedin) && !empty($loggedin)) {
        if(isset($_SESSION["id"])) {
            $statement = $mysqli->prepare("DELETE 
            FROM item
            WHERE users_id = ?;");
            $statement2 = $mysqli->prepare("DELETE 
            FROM users
            WHERE id = ?;");
            $statement->bind_param("i", $_SESSION["id"]);
            $statement2->bind_param("i", $_SESSION["id"]);
            $executed = $statement->execute();
            if(! $executed) {
                echo $mysqli->error;
            }
            else {
                $executed2 = $statement2->execute();
                if(! $executed2) {
                    echo $mysqli->error;
                }
                else {
                    $isUpdated = true;
                }
                $statement2->close();
            }
            $statement->close();
        }
    }
    $mysqli->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="1;url=../index.php" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="../style/navbar.css">
    <link rel="stylesheet" href="../style/main.css">
    <title>eatsafe</title>
</head>
<body>
    <div class="center">
        <?php if($isUpdated) :?>
            <?php 
                session_unset();
                session_destroy();
            ?>
            Your account was deleted.
        <?php else: ?>
            There was a problem with your request.
        <?php endif;?>
    </div>
</body>
</html>