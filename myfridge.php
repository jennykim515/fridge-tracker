<?php
    require "config/start.php";
    if(isset($loggedin) && !empty($loggedin)) {
        $user_id = $_SESSION["id"];
        $statement = $mysqli->prepare("SELECT item.id AS item_id, item.name AS item_name, expire, received, quantity, users_id, category_id, category.name AS category_name
        FROM item
        JOIN category
        ON category.id = item.category_id
        WHERE users_id = ?
        ORDER BY DATEDIFF(expire, NOW()), category_id;
        ");
        $statement->bind_param("i", $user_id);
        $executed = $statement->execute();
        if(! $executed) {
            echo $mysqli->error;
        }
        $result = $statement->get_result();
        $statement->close();
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
    <link rel="stylesheet" href="style/navbar.css">
    <link rel="stylesheet" href="style/grid.css">
    <title>eatsafe</title>
</head>

<body>
    <?php include 'nav-light.php'; ?>
    <div class="nav-space"></div>
    <div class="container">
        <?php if(isset($loggedin) && !empty($loggedin)) : ?>
            <ul>
            <?php while ( $row = $result->fetch_assoc() ) : ?>
            <li class="<?php echo strtok($row["category_name"], " "); ?>">
                <a href="delete.php?user_id=<?php echo $user_id;?>&item_id=<?php echo $row["item_id"];?>">
                    <?php echo "<h2>" . $row["quantity"] . " " . $row["item_name"] . "(s)" . "</h2>"; ?>
                    <?php echo "<strong>category: </strong>"?>
                    <?php echo "<p>" . $row["category_name"] . "</p>"; ?> 
                    <?php echo "<br>"; ?>
                    <?php echo "<strong>expires in: </strong>"; ?>
                    <?php
                        $expire_date = strtotime($row["expire"]);
                        $today_date = strtotime(date("Y/m/d")); 
                        $days_left = (($expire_date - $today_date) / (60 * 60 * 24));
                    ?>
                    <?php echo "<p>";?>
                    <?php if($days_left < 4) :?>
                        <span style="color:red"> 
                        <?php echo $days_left; ?>
                        <?php echo "day(s)."; ?>
                    <?php else: ?>
                    <?php echo $days_left; ?>
                    <?php echo "day(s)."; ?>
                    <?php endif;?>
                    <?php echo "</p>";?>
                </a>
            </li>
            <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <!-- Not centering -->
            <div class = "center"> 
                <p><?php echo "You are not logged in" ?></p>
                <a href="login/login.php">Login here</a>
            </div>
        <?php endif; ?>
    </div>
    <!-- <footer>
        <p>Made with ♡ by Jenny<br>
        <a href="mailto:yjkim@usc.edu@example.com">yjkim@usc.edu</a></p>
    </footer> -->

    <script src="javascript/toggle.js"></script>

</body>
</html>