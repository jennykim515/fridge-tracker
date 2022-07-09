<div id="navbar">
    <a href="../index.php"><h1 id="logo">eatsafe</h1></a>
    <a id="hamburger-click" href="#"><img id="hamburger" src="../images/hamburger.png" alt="hamburger"/></a>
    <div id="nav-box" class="hidden">
        <a href="../index.php">HOME</a>
        <?php if($loggedin) :?>
            <a href="update_acct.php">PROFILE</a>
            <a href="../myfridge.php">MY FRIDGE</a>
        <?php else: ?>
            <a href="login.php">LOGIN</a>
        <?php endif ;?>
    </div>
</div>