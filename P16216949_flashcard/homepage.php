<?php

// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1"/>
    <title></title>

    <link rel="stylesheet" type="text/css" href="Main.css"/>
    <link id="pagestyle" rel="stylesheet" type="text/css" href="Main.css"/>


    <title>FlashCard Home</title>
    <meta http-equiv="Content-type" content="text/html;charset=UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>

</head>
<div id="Navbar" class="navbar">
    <nav>
        <a href="Homepage.php" target="_self"><img src="home.jpg" alt="Home" class="Himage" width="1" height="2"/>home</a>
        <a href="Flashcard.php" target="_self"><img src="flash.jpg" alt="Home" class="Himage" width="1" height="2"/>FlashCard</a>
        <a href="CardPage.php" target="_self"><img src="customize.jpg" alt="Home" class="Himage" width="1" height="2"/>Cards</a>
        <a href="About.php" target="_self"><img src="question.jpg" alt="Home" class="Himage" width="1" height="2"/>About</a>
        <a href="Customize.html" target="_self"><img src="customize.jpg" alt="Home" class="Himage" width="1" height="2"/>customize</a>
        <a href="Account.php" target="_self"><img src="customize.jpg" alt="Home" class="Himage" width="1" height="2"/>Account</a>
    </nav>

</div>
<!-- This is my homebar, each image should have text underneath it. Width class are done in css.  -->
<body>
<h1>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to our site.</h1>
<h1>
    Welcome to our fun flashcard game!
</h1>
</div>
</body>
</html>

</script>