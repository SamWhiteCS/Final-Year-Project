<?php
// Include config file
require_once "config.php";
session_start();
// Define variables and initialize with empty values
$heading = $description =  $result  = $card_Heading = $card_Description = $card_Answer = $card_id = $session_count  = $deck_count = $deck_id  = $selected_values= "";
$heading_err = $description_err =  "";
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){

}

$card_id = 0;

//What i need to do
//Store the sql statements then display them in an echo or something


// selects the question based on the chance type .
 if($_SERVER["REQUEST_METHOD"] == "POST") {

//     $selected_values = trim($_POST['select_header']);
//     $selected_values->execute();

     list($selected_values,$selected_id) = explode(" {} ",$_POST['select_header']);  // or POST

     echo $selected_values; // 6
     echo $selected_id; // 4


     $resultSet = $link->query("select heading,deckid,deckcount from deck WHERE id = $_SESSION[id]");

//     $selected_id = $selected_values[1];
    $selected_count = $selected_values;

//     $sql = "select (cardid,heading,description,answer) from card where cardchance = (?) AND deckid = (?)";
     //possibly add a date time so can order by last used
     $sql = "select * from card where cardchance = (?) AND  deckid = (?)";
     $sql2 = "select * from card where cardchance != (?) AND deckid = (?) order by cardchance ASC LIMIT 1";

//While loop, if it fails to find a result it will repeat.

     //Things to add, add a card and deck edit. Maybe deck and card get put onto a card.
     while ($card_id == 0) {



             if ($stmt = mysqli_prepare($link, $sql)) {
                 // Bind variables to the prepared statement as parameters
                 mysqli_stmt_bind_param($stmt, "ss", $param_chance, $param_id);
                 // Set parameters
//             $param_chance = 3;
                 //created a random chance number, which chancenum will be used to select a card
                 $random_Chance = array(0, 0, 1, 2, 2, 3, 3, 3);
//$Chance_Num = array_rand($random_Chance,1);
                 $chance_num = $random_Chance[array_rand($random_Chance)];
                 echo $chance_num;

                 $param_chance = $chance_num;
                 $param_id = $selected_id;
                 // Attempt to execute the prepared statement#
//         mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
//         $result3 = $link->query($sql) or die($link->error);

                 if (mysqli_stmt_execute($stmt)) {

                     $result = $stmt->get_result();
                     $row = $result->fetch_assoc();



                     if ($row != null) {


                         $card_Heading = $row['heading'];
                         $card_Description = $row['description'];
                         $card_Answer = $row['answer'];
                         $card_Chance = $row['cardchance'];
                         $card_id = $row['cardid'];

                     }



// USe the code here to store it into an array, might make it easier in the future.

                     mysqli_stmt_store_result($stmt);
                 }
             } elseif ($stmt = mysqli_prepare($link, $sql2)) {

                 mysqli_stmt_bind_param($stmt, "ss", $param_chance, $param_id);
                 $param_chance = $chance_num;
                 $param_id = $selected_id;


                 if (mysqli_stmt_execute($stmt)) {
                     /* store result */

                     $confirm_value = 1;
                     mysqli_stmt_store_result($stmt);

                 } else {
                     echo "Something failed on our end";


                     echo "Oops! Something went wrong. Please try again later.1";
                 }
             }

     }


     mysqli_stmt_close($stmt);
}
     //this is working, now need to change submitcard chance
// If +value is more than 0 then decrease the card chance, if not then leave be or increase.
// after this use an if statement? SQL injection that will insert the card chance, after this a refresh should be happening.
//if action is called it will be split up into the two buttons, if they fulfill the criteria of not going above algarithm, then sql insert statements will be applied.
if (isset($_POST['action'])) {
    $card_original_chance = $card_Chance;

    if ($_POST['Success']) {
    //UPDATE THIS< AS IT GETS THE CARD COUNT THROUGH LOGIN IT WON'T UPDATE!!     Add card count to the deck and the card. Then possibly add them up?  Maybe just add them to the deck then get an sql query to just retrieve them.
        // update this so the card count increase every time
        //This should also be connected to deck, maybe have a count for each deck possibly?
        //Could have a kind of stat page? where everything is laid out for them to see
//        $sql3 = "UPDATE accounts SET cardcount = (?) where id =(?)";
        $sql3 = "UPDATE deck SET deckcount = (?) where deckid = (?)";
        $stmt = mysqli_prepare($link, $sql3);
        mysqli_stmt_bind_param($stmt, "ss", $param_count, $param_id);
        $param_count = $selected_count + 1;
        $param_id;
//        $session_count = $_SESSION[cardcount];
//        $param_count = 1 + $session_count;
//        $param_id = $_SESSION[id];

        mysqli_stmt_execute($stmt);

     //   mysqli_stmt_store_result($stmt);


        mysqli_stmt_close($stmt);
//
//        $sql2 = "UPDATE deck SET deckcount = (?) where deckid =(?)";
//        $stmt = mysqli_prepare($link, $sql2);
//        mysqli_stmt_bind_param($stmt, "ss", $param_deck_count, $param_deck_id);
//        $param_deck_count = 1 + $deck_count;
//        $param_deck_id = $deck_id;
//
//        mysqli_stmt_execute($stmt);
//
//        mysqli_stmt_store_result($stmt);
//
//
//        mysqli_stmt_close($stmt);

//Add an else into here, as there are numbers none of them touch.
        if ($card_original_chance <= 2) {

            $card_Chance = $card_Chance + +1;
//        $result3 = $link->query($sql) or die($link->error);
//        echo $result3;
//    $sql = "select * from card where cardchance = (?) AND  deckid = (?)";
            $sql = "UPDATE card SET cardchance = (?) WHERE cardid = (?)";
            if ($stmt = mysqli_prepare($link, $sql)) {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "ss", $param_chance, $param_card_id);
                $param_chance = $card_Chance;
                $param_card_id = $card_id;
//         $result3 = $link->query($sql) or die($link->error);
                mysqli_stmt_execute($stmt);
                /* store result */
                mysqli_stmt_store_result($stmt);
                mysqli_stmt_close($stmt);

                // Close statement


            }


        }
    }
    elseif ($_POST['Fail']) {

            $card_Chance = $card_Chance - 1;
//        $result3 = $link->query($sql) or die($link->error);
//        echo $result3;
//    $sql = "select * from card where cardchance = (?) AND  deckid = (?)";
            if ($card_original_chance >= 1) {


                $sql = "UPDATE card SET cardchance = (?) WHERE cardid = (?)";
                if ($stmt = mysqli_prepare($link, $sql)) {
                    // Bind variables to the prepared statement as parameters
                    mysqli_stmt_bind_param($stmt, "ss", $param_chance, $param_card_id);
                    $param_chance = $card_Chance;
                    $param_card_id = $card_id;
//         $result3 = $link->query($sql) or die($link->error);
                    mysqli_stmt_execute($stmt);
                    /* store result */
                    mysqli_stmt_store_result($stmt);

                    // Close statement
                    mysqli_stmt_close($stmt);
                }
            }
            // Close connection


            // Close connection
            mysqli_close($link);


        }

}
// this result needs to run whether the submit button has been pressed or not.
     if ($_SERVER["REQUEST_METHOD"] == "POST") {

     } else {
         $resultSet = $link->query("select heading,deckid,deckcount from deck WHERE id = $_SESSION[id]");
     }


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
    <a href="Homepage.php" target="_self"><img src="home.jpg" alt="Home" class="Himage" width="1" height="2"/>home</a>
    <a href="Flashcard.php" target="_self"><img src="flash.jpg" alt="Home" class="Himage" width="1" height="2"/>FlashCard</a>
    <a href="CardPage.php" target="_self"><img src="customize.jpg" alt="Home" class="Himage" width="1" height="2"/>Cards</a>
    <a href="About.php" target="_self"><img src="question.jpg" alt="Home" class="Himage" width="1" height="2"/>About</a>
    <a href="Customize.html" target="_self"><img src="customize.jpg" alt="Home" class="Himage" width="1" height="2"/>customize</a>
    <a href="Account.php" target="_self"><img src="customize.jpg" alt="Home" class="Himage" width="1" height="2"/>Account</a>
</div>
<!-- This is my homebar, each image should have text underneath it. Width class are done in css.  -->
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

<!--<div class="flip-container" ontouchstart="this.classList.toggle('hover');">-->
<!--    <div class="flipper">-->
<!--        <div class="front">-->
<!--            <h1>--><?php //echo $card_Heading ?><!--</h1>-->
<!--            <p>--><?php //echo $card_Description ?><!--</p>-->
<!--        </div>-->
<!--        <div class="back">-->
<!--            <h1>--><?php //echo $card_Answer ?><!--</h1>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->

</body>


</html>
</script>