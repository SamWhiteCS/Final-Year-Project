<?php
// Include config file
require_once "config.php";
session_start();
// Define variables and initialize with empty values
$heading = $description =  $result  = $card_Heading = $card_Description = $card_Answer = $card_id = $session_count  = $deck_count = $deck_id  = $selected_values = "";
$heading_err = $description_err =  "";
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
$card_id = 0;
// selects the question based on the chance type .
if($_SERVER["REQUEST_METHOD"] == "POST") {

    list($selected_values,$selected_id) = explode(" {} ",$_POST['select_header']); //This splits the array based on a string that will very unlikely be used.
    $resultSet = $link->query("select heading,deckid,deckcount from deck WHERE id = $_SESSION[id]"); //This gets the details for the drop down menu.

    $selected_count = $selected_values;
    $sql = "select * from card where cardchance = (?) AND  deckid = (?)";
    $sql2 = "select * from card where cardchance != (?) AND deckid = (?) order by cardchance ASC LIMIT 1";

//While loop, if it fails to find a result it will repeat.
    while ($card_id == 0) {

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_chance, $param_id);
            //created a random chance number, which chancenum will be used to select a card
            $random_Chance = array(0, 0, 1, 2, 2, 3, 3, 3);
            $chance_num = $random_Chance[array_rand($random_Chance)];
            //set the parameters. Selected ID being the id of the drop down deck and chance being algorithm above.
            $param_chance = $chance_num;
            $param_id = $selected_id;
            // Attempt to execute the prepared statement#
            if (mysqli_stmt_execute($stmt)) {

                $result = $stmt->get_result();
                $row = $result->fetch_assoc();


                if ($row != null) { //If the sql failed and no rows are found then don't run. This prevents errors popping up.


                    $card_Heading = $row['heading'];
                    $card_Description = $row['description'];
                    $card_Answer = $row['answer'];
                    $card_Chance = $row['cardchance'];
                    $card_id = $row['cardid'];

                }
                mysqli_stmt_store_result($stmt);
            }
        }
    }
    mysqli_stmt_close($stmt);
}else {
    $resultSet = $link->query("select heading,deckid,deckcount from deck WHERE id = $_SESSION[id]");
}

//if action is called it will be split up into the two buttons, if they fulfill the criteria of not going above algarithm, then sql insert statements will be applied.
if (isset($_POST['action'])) {
    // If action was posted then:
    $card_original_chance = $card_Chance;

    if ($_POST['Success']) { // If the action was success then run this code.

        $sql3 = "UPDATE deck SET deckcount = (?) where deckid = (?)";
        $stmt = mysqli_prepare($link, $sql3);
        mysqli_stmt_bind_param($stmt, "ss", $param_count, $param_id);
        $param_count = $selected_count + 1;
        $param_id;
        mysqli_stmt_execute($stmt);

        mysqli_stmt_store_result($stmt);


        mysqli_stmt_close($stmt);
        //Code above creates an sql query, binds the values then executes and stores them. Storing isn't particulary neccesary for this.
        if ($card_original_chance <= 2) {
            $card_Chance = $card_Chance + +1;
            $sql = "UPDATE card SET cardchance = (?) WHERE cardid = (?)";
            if ($stmt = mysqli_prepare($link, $sql)) {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "ss", $param_chance, $param_card_id);
                $param_chance = $card_Chance;
                $param_card_id = $card_id;
                mysqli_stmt_execute($stmt);
                //Executes binded parameters and updates the cardid. This
                mysqli_stmt_close($stmt);
                // Close statement, this is good for security.
            }
        }
    }
    elseif ($_POST['Fail']) {
        //Updates card chance, this will mean that the card will come up more commonly. Then an if statement. more or = than one to make sure it doesn't hit 0. That's not in algorithm. Same as success but opposite.
        $card_Chance = $card_Chance - 1;
        if ($card_original_chance >= 1) {

            $sql = "UPDATE card SET cardchance = (?) WHERE cardid = (?)";
            if ($stmt = mysqli_prepare($link, $sql)) {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "ss", $param_chance, $param_card_id);
                $param_chance = $card_Chance;
                $param_card_id = $card_id;
                mysqli_stmt_execute($stmt);
                // Close statement
                mysqli_stmt_close($stmt);
            }
        }
        // Close connection
        mysqli_close($link);
    }
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
<!-- This is my homebar, each image should have text underneath it. Width class are done in css. Nav has been used as it's used in HTML5  -->
<body>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <!--can add this to make multiple selects if I wanted to.https://stackoverflow.com/questions/2407284/how-to-get-multiple-selected-values-of-select-box-in-php-->
    <div class="form-group">
        <select type="text" name="select_header" method="post">
            <?php
            while($rows = $resultSet->fetch_assoc())
            {
                $deck_name = $rows['heading'];
                $deck_id = $rows['deckid'];
                $deck_count =$rows['deckcount'];
                echo "<option value ='$deck_count {} $deck_id'>$deck_name</option>";
            }


            ?>
        </select>
    </div>
    <div class="flip-container" ontouchstart="this.classList.toggle('hover');">
        <div class="flipper">
            <div class="front">
                <h1><?php echo $card_Heading ?></h1>
                <p><?php echo $card_Description ?></p>
            </div>
            <div class="back">
                <h1><?php echo $card_Answer ?></h1>
            </div>
        </div>
    </div>
    <div class="form-group">
        <input type="hidden" name="action" value="submit" />
        <input  name="Success" type="submit" class="blue" value="Correct">
        <input name="Fail" type="submit" class="red" value="Incorrect">
        <input type="reset" class="reset" value="Reset">
    </div>
</form>
</body>
</html>
</script>