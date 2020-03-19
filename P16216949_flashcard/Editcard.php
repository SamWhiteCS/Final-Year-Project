<?php
// Include config file
require_once "config.php";
session_start();
// Define variables and initialize with empty values
$heading = $description = $answer = $deck_id = $selected_header = $selected_id = $selected_cid = "";
$heading_err = $description_err = $answer_err = "";
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}
//Remove post possibly, use only action but still retrieve stuff from post. Possibly a while loop storing the card information in an array?
if (isset($_POST['action'])) {


    if ($_POST['DeckChosen']) {
        //If the deck has been chosen go through and fetch the results from card.
        $selected_header = trim($_POST['select_header']);
        $selected_id = $selected_header;
        $sql = "select * from card where deckid = (?)";
        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt, "s", $param_id);
        $param_id = $selected_id;
        mysqli_stmt_execute($stmt);
        //Executes the query and stores the values in the parameters below.
        $CardResult = $stmt->get_result();
        $row = $CardResult->fetch_assoc();


        $card_Heading = $row['heading'];
        $card_Description = $row['description'];
        $card_Answer = $row['answer'];
        $card_Chance = $row['cardchance'];
        $card_id = $row['cardid'];

        mysqli_stmt_store_result($stmt);
        mysqli_stmt_close($stmt);

    } elseif ($_POST['CardChosen']) { //If card and deck has been chosen this will run, basically a submit button.

        $selected_card = trim($_POST['select_card']);
        $selected_cid = $selected_card;
        $header = trim($_POST["description"]);


        if (empty(trim($_POST["heading"]))) {
            $heading_err = "Please enter a heading.";
        } else {
            // Prepare a select statement
            $sql = "SELECT cardid FROM card WHERE heading = ?";

            if ($stmt = mysqli_prepare($link, $sql)) {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "s", $param_heading);

                // Set parameters
                $param_heading = trim($_POST["heading"]);

                // Attempt to execute the prepared statement
                if (mysqli_stmt_execute($stmt)) {
                    /* store result */
                    mysqli_stmt_store_result($stmt);

                    if (mysqli_stmt_num_rows($stmt) == 1) {
                        $heading_err = "This heading is already taken.";
                    } else {
                        $heading = trim($_POST["heading"]);
                        $answer = trim($_POST["answer"]);
                    }
                } else {
                    echo "Oops! Something went wrong. Please try again later.1";
                }
            }
            // Close statement
            mysqli_stmt_close($stmt);
        }
        if (empty(trim($_POST["description"]))) {
            $description_err = "Please enter a description.";
        } else {
            $description = trim($_POST["description"]);
        }
        $sql = "UPDATE card SET heading = (?), description = (?), answer = (?) where cardid = (?) ";

        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssss", $param_heading, $param_description, $param_answer, $param_id);

            // Set parameters
            $param_heading = $heading;
            $param_description = $description;
            // $param_id = $selected_id;
            $param_id = $selected_cid;
            $param_answer = $answer;
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Redirect to login page

            } else {
                echo "Something went wrong. Please try again later2.";
            }
        }
        // Close statement
        mysqli_stmt_close($stmt);
    }
}
// Close connection
//This post is used when the submit deck button is used. If no post is found then it will find the deck and run the dropwdown menu for that. When card has been submitted it will populate the card dropdown
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $CardResult = $link ->query("select heading,description,answer,cardid from card where deckid = $selected_id");
}else{
    $resultSet =     $link ->query("select heading,deckid from deck WHERE id = $_SESSION[id]");
}
mysqli_close($link);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// This errors everyt so often so check it out!
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
<div class="wrapper">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

        <div class="wrapper">
                <h1>Card Editor</h1>
                <h5>Please choose a deck</h5>
                <select type="text" name="select_header" method="post" value="please choose a deck">
                    <?php
                    if ($_POST['DeckChosen']) {

                    }else{
                        while($rows = $resultSet->fetch_assoc())
                        {
                            $deck_name = $rows['heading'];
                            $deck_id = $rows['deckid'];
                            echo "<option value ='$deck_id'>$deck_name</option>";
                        }
                    }
                    //

                    ?>
                </select>
                <div class="form-group">
                    <input type="hidden" name="action" value="submit" />
                    <input  name="DeckChosen" type="submit" class="btn" value="Submit Deck">
                </div>

                <h5>Please choose a card</h5>
                <select type="text" name="select_card" method="post" value="please choose a card">
                    <?php


                    while($rows = $CardResult->fetch_assoc())
                    {
                        $card_name = $rows['heading'];
                        $card_id = $rows['cardid'];
                        $card_description = $rows['description'];
                        $card_answer = $rows['answer'];
                        echo "<option value ='$card_id'>$card_name</option>";
                    }


                    ?>
                </select>


                <h5>Please enter you card details</h5>

                <div class="heading <?php echo (!empty($heading_err)) ? 'has-error' : ''; ?>">
                    <input type="text" name="heading" required="required" class="form-control" value="<?php echo $heading; ?>"/><span class="highlight"></span><span class="bar"></span><span class="help-block"><?php echo $heading_err; ?></span>
                    <label>Heading</label>
                </div>
                <div class="heading <?php echo (!empty($description_err)) ? 'has-error' : ''; ?>">
                    <input type="text" name="description" required="required" class="form-control" value="<?php echo $description; ?>"/><span class="highlight"></span><span class="bar"></span><span class="help-block"><?php echo $description_err; ?></span>
                    <label>Description</label>
                </div>

                <div class="heading <?php echo (!empty($answer_err)) ? 'has-error' : ''; ?>">
                    <input type="text" name="answer" required="required" class="form-control" value="<?php echo $answer; ?>"/><span class="highlight"></span><span class="bar"></span><span class="help-block"><?php echo $answer_err; ?></span>
                    <label>Answer</label
                </div>

        </div>
        <div class="btn-box">
            <input type="hidden" name="action" value="submit" />
            <input name="CardChosen" class="btn btn-submit" type="submit">submit</input>
            <input class="btn btn-cancel" type="button">cancel</input>
        </div>
    </form>
</div>
</div>
</body>
</html>

</script>