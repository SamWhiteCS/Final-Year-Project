<?php
// Include config file
require_once "config.php";
session_start();
// Define variables and initialize with empty values
$heading = $description = $answer = $deck_id = $selected_header = $selected_id  = "";
$heading_err = $description_err = $answer_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate username
    if(empty(trim($_POST["heading"]))){
        $heading_err = "Please enter a heading.";
    } else {
        $answer = trim($_POST["answer"]);
        $selected_header = trim($_POST['select_header']);


        $selected_id = $selected_header;

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
                }
            } else {
                echo "Sorry, seems like we have encountered an error while checking your heading.";
            }
            mysqli_stmt_close($stmt);
        }
    }
    // Validates the description, if empty notify users if not description is = to the users input.
    if(empty(trim($_POST["description"]))){
        $description_err = "Please enter a description.";
    } else{
        $description = trim($_POST["description"]);
    }
    //If no error prepare and execute the statement, this will insert the new card details.
    if(empty($heading_err) && empty($description_err)){
        // Prepare an insert statement, insert headings, values = the parameter placeholders.
        $sql = "INSERT INTO card (heading, description, answer, deckid) VALUES (?, ?, ?, ?)";


        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "ssss", $param_heading, $param_description, $param_answer, $param_id);
            // Set parameters
            $param_heading = $heading;
            $param_description =$description;
            $param_answer = $answer;
            $param_id = $selected_id;
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                header("location: CreateCard.php");
            } else{
                echo "Something went wrong creating the card.";
            }
        }
        // Close statement
        mysqli_stmt_close($stmt);
    }
    // Close connection
    mysqli_close($link);
}else{
    $resultSet =     $link ->query("select heading,deckid from deck WHERE id = $_SESSION[id]");
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
<body>
<div class="wrapper">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">


        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <h1>Card Creation</h1>
            <h5>Please choose a deck</h5>
            <select type="text" name="select_header" method="post" value="please choose a deck">
                <?php
                while($rows = $resultSet->fetch_assoc())
                {
                    $deck_name = $rows['heading'];
                    $deck_id = $rows['deckid'];
                    echo "<option value ='$deck_id'>$deck_name</option>";
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
    <button class="btn btn-submit" type="submit">submit</button>
    <button class="btn btn-cancel" type="button">cancel</button>
    </form>
</div>

</div>
</body>
</html>

</script>