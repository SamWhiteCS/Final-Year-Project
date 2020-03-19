<?php

// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
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
<!--https://www.w3schools.com/howto/tryit.asp?filename=tryhow_css_column_cards-->
<h2 class="cardp">Responsive Column Cards</h2>
<div class="row">
    <a href="CreateDeck.php" target="_self" class="cardp">
        <div class="column">
            <div class="card">
                <h3>Create Deck</h3>
                <p>Create a new deck</p>
            </div>
        </div>
    </a>
    <a href="Editdeck.php" target="_self" class="cardp">
        <div class="column">
            <div class="card">
                <h3>Edit Deck</h3>
                <p>Edit an existing deck</p>
            </div>
    </a>
</div>


<a href="CreateCard.php" target="_self" class="cardp">
    <div class="column">
        <div class="card">
            <h3>Create Card</h3>
            <p>Create a new card</p>
        </div>
</a>
</div>
<a href="Editcard.php" target="_self" class="cardp">
    <div class="column">
        <div class="card">
            <h3>Edit card</h3>
            <p>Edit an existing card</p>
        </div>
</a>
</div>
</div>

</div>
</body>
</html>

</script>