<?php require "../config/start.php"; ?>
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
    <?php if( isset($_SESSION["loggedin"]) && $_SESSION["loggedin"]): ?>
        <?php include 'nav.php'; ?>
        <div class="center">
        <div id="form-container">
            <form action="update_password_confirmation.php" method="POST">
                <label for="old-password"><span style="color:red">*</span>Current password:</label><br>
                <input type="text" id="old-password" name="old-password" required><br>
                <label for="new-password"><span style="color:red">*</span>New password:</label><br>
                <input type="text" id="new-password" name="new-password" required><br>
                <input type="submit" value="Submit">
            </form>
        </div>
    <?php else: ?>
        <?php echo '<meta http-equiv="refresh" content="0; URL=login.php" />'; ?>
    <?php endif; ?>
    <script src="../javascript/toggle.js"></script>
</body>
</html>