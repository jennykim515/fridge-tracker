<?php
    require "../config/start.php";
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
    <?php include 'nav.php'; ?>
    <div class="center">
    <?php if(!isset($_SESSION["loggedin"])): ?>
        <div id="form-container">
            <form action="signup_confirmation.php" method="POST">
            <!-- <h2>Register</h2><br> -->
                <label for="fname"><span style="color:red">*</span>First Name:</label><br>
                <input type="text" id="fname" name="fname" placeholder="Tommy" required><br>
                <label for="lname"><span style="color:red">*</span>Last Name:</label><br>
                <input type="text" id="lname" name="lname" placeholder="Trojan" required><br>
                <label for="email"><span style="color:red">*</span>Email:</label><br>
                <input type="email" id="email" name="email" required><br>
                <label for="pass"><span style="color:red">*</span>Password:</label><br>
                <input type="text" id="pass" name="pass" required><br>
                <input type="submit" value="Sign Up">
                <a href="login.php"><br><br>already have an account yet? SIGN IN</a>
            </form>
        </div>
    <?php else :?>
        Please log out before trying to register a new account
    <?php endif; ?>
    </div>
    <script src="../javascript/toggle.js"></script>
</body>
</html>