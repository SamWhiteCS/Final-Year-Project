<?php
require_once "config.php";
session_start();
$heading = $description =  "";
$heading_err = $description_err =  "";
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php"); //If not logged in be redirected to login.
    exit;
}

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate username, if heading is empty it won't be used, trims heading to remove anything unwanted.
    if(empty(trim($_POST["heading"]))){

        $heading_err = "Please enter a heading."; //If heading is empty notify users.
    }
    else{
        // Prepare a select statement, this double checks that there are no decks with the same heading.
        $sql = "SELECT deckid FROM deck WHERE heading = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_heading);
            // Set parameters, for this case it's heading, trimmed.
            $param_heading = trim($_POST["heading"]);

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) == 1){
                    $heading_err = "This heading is already taken.";
                } else{
                    $heading = trim($_POST["heading"]);
                }
            } else{
                echo "Sorry, seems like we have encountered an error while checking your heading.";
            }
        }
        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Validate description.
    if(empty(trim($_POST["description"]))){ //If description is empty notify user. If it succeeds description is equal to the post trimmed.
        $description_err = "Please enter a description.";
    } else{
        $description = trim($_POST["description"]);
    }
    // Check input errors before inserting in database
    if(empty($heading_err) && empty($description_err)){
        // Prepare an insert statement, here the deck will be create with an insert.
        $sql = "INSERT INTO deck (heading, description, id) VALUES (?, ?, ?)";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_heading, $param_description, $param_id);
            // Set parameters
            $param_heading = $heading;
            $param_description =$description;
            $param_id  =$_SESSION[id];
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: CreateDeck.php");
            } else{
                echo "Something went wrong creating the deck.";
            }
        }
        // Close statement
        mysqli_stmt_close($stmt);
    }
    // Close connection
    mysqli_close($link);
}


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

</form>
<!--https://codepen.io/lewisvrobinson/pen/EyZwjR-->
<div class="wrapper">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <h1>Deck Creation</h1>
        <h5>Please enter you text details</h5>
        <hr class="sep"/>
        <div class="heading <?php echo (!empty($heading_err)) ? 'has-error' : ''; ?>">
            <input type="text" name="heading" required="required" class="form-control" value="<?php echo $heading; ?>"/><span class="highlight"></span><span class="bar"></span><span class="help-block"><?php echo $heading_err; ?></span>
            <label>Heading</label>
        </div>
        <div class="heading <?php echo (!empty($description_err)) ? 'has-error' : ''; ?>">
            <input type="text" name="description" required="required" class="form-control" value="<?php echo $description; ?>"/><span class="highlight"></span><span class="bar"></span><span class="help-block"><?php echo $heading_err; ?></span>
            <label>Description</label
        </div>
</div>
<div class="btn-box">
    <button class="btn btn-submit" type="submit">submit</button>
    <button class="btn btn-cancel" type="button">cancel</button>
</div>
</form>
</div>
</body>
</html>

</script>




