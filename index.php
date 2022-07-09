<?php
    require "config/start.php";
    $sql = "SELECT * FROM category;";
    $results = $mysqli->query($sql);
    if ( $results == false ) {
        echo $mysqli->error;
        exit();
    }
    // if form is filled out
    if ( isset($_POST['item']) && !empty($_POST['item'])
    && isset($_POST['exp']) && !empty($_POST['exp']) 
    && isset($_POST['qty']) && !empty($_POST['qty']) 
    ) {
        if( isset($_SESSION["loggedin"])) {
            if($_SESSION["loggedin"]) {
                // var_dump($_POST);
                date_default_timezone_set("America/Los_Angeles");
                $user_id = $_SESSION["id"];
                $item_name = $_POST["item"];
                $expire_date = $_POST["exp"];
                $received_date = date("Y-m-d");
                $quantity = $_POST["qty"];
                $category_id = null;
                if ( isset($_POST['category']) && !empty($_POST['category']) ) {
                    $category_id = $_POST['category'];
                }
                $statement = $mysqli->prepare('INSERT INTO item(name, expire, received, quantity, users_id, category_id) VALUES(?, ?, ?, ?, ?, ?);');
                $statement->bind_param("sssiii", $item_name, $expire_date, $received_date, $quantity, $user_id, $category_id);
                $executed = $statement->execute();
                if(! $executed) {
                    echo $mysqli->error;
                }
                $statement->close();
            }
            else {
                $error = "You are not logged in";
            }
        }
        else {
            $error = "You are not logged in";
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="style/main.css">
    <link rel="stylesheet" href="style/navbar.css">
    <link rel="stylesheet" href="style/index.css">
    <title>eatsafe</title>
    <style>
        #display-left {
            scroll-behavior: smooth;
        }
        label {
            color: white;
        }
        #one {
            position: relative;
        }
        #arrow {
            position: absolute;
            bottom: 10px;
        }
        @media (max-width: 800px) {
            #display-left, .wrapper {
                display: none;
            }
            #display-right {
                padding-top: 50px;
                width: 100%;
            }
        }
    </style>
</head>
<body class="home">
    <?php include 'nav-light.php'; ?>
    <div class="wrapper">
        <div id="display-left">
            <div class="section" id="one"> 
                <h2>get your <span style="color:#F45B69;">fridge</span> together</h2>
                <br>
                <p>the easier way to keep track of your groceries</p>
                <a href="#two" id="arrow" title="click to scroll"><img src="images/down.png"></a>
                <!-- <a href="https://www.flaticon.com/free-icons/down-arrow" title="down arrow icons">Down arrow icons created by Arkinasi - Flaticon</a> -->
            </div>
            <div class="section" id="two"> 
                <h2>how it works</h2>
                <br>
                <p><a href="login/signup.php">sign up</a> with your email and get access to a dashboard of your fridge items. After every grocery run, fill out this form on the right!</p>
            </div>
            <div class="section"> 
                <h2>why did I make this?</h2>
                <br>
                <p>as a college student, I found myself throwing out way too much food. I never realized how fast food expires! With this tool, I hope to mitigate food waste.</p>
            </div>
            <div class="section"> 
                <h2>thank you for visiting my website ♥ hope you enjoy!</h2>
            </div>
        </div>
    </div>
        
    <div id="display-right">

        <div id="form-container">
            <form action="index.php" method="POST">
                <h1 style="text-align:center; color:white;">new item</h1><br>
                <label for="item"><span style="color:red">*</span>item:</label><br>
                <input type="text" maxlength="30" id="item" name="item" placeholder="orange" required><br>


                <label for="exp"><span style="color:red">*</span>expiration date:</label><br>
                <input type="date" id="exp" name="exp" required><br>
                <label for="qty"><span style="color:red">*</span>quantity:</label><br>
                <input type="number" min=1 max=50 id="qty" name="qty" value=1 required><br>
                <label for="caetgory">category:</label><br>
                <select name="category" id="category-id">
                    <option value="" selected>-- Select One --</option>
                    <?php while ( $row = $results->fetch_assoc() ) : ?>
                        <option value="<?php echo $row['id']; ?>">
                            <?php echo $row['name']; ?>
                        </option>
                    <?php endwhile; ?>

                        </select>
                <input type="submit" value="Submit">
            </form>
            <?php
                if ( isset($error) && !empty($error) ) {
                    // <span style="color:red">*</span>
                    echo "<span style='color:pink'>" . $error . "</span>";
                }
            ?>
        </div>
    
    </div>

    <div style="clear: both"></div>

<!-- 
<footer>
  <p>Made with ♡ by Jenny<br>
  <a href="mailto:yjkim@usc.edu@example.com">yjkim@usc.edu</a></p>
</footer> -->
    <script src="javascript/toggle.js"></script>
</body>
</html>