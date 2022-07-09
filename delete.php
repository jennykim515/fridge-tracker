<?php
    require "config/start.php";
    if(isset($_SESSION["loggedin"]) && ($_SESSION["loggedin"])) {
        $sql = $mysqli->prepare("DELETE FROM item WHERE id=? AND users_id=?");
        $sql->bind_param("ii", $_GET["item_id"], $_GET["user_id"]);
        $executed = $sql->execute();
        if(! $executed) {
            $error = $mysqli->error;
        }
        if($sql->affected_rows == 1) {
            echo '<meta http-equiv="refresh" content="0; URL=myfridge.php" />';
        }
        else {
            echo "There was a problem with your request.";
        }
        $sql->close();
    }
    else {
        $error = "You are not logged in.";
    }
    $mysqli->close();    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="style/navbar.css">
    <link rel="stylesheet" href="style/main.css">
    <title>eatsafe</title>
</head>
<body>

</body>
</html>