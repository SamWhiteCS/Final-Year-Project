<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
define('DB_SERVER', '127.0.0.1');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'flashcard');
 
/* Attempt to connect to MySQL database */

$serverName = "serverName\sqlexpress, 1542"; //serverName\instanceName, portNumber (1433 by default)
$connectionInfo = array( "Database"=>"dbName", "UID"=>"userName", "PWD"=>"password");
$link = mysqli_connect( DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
//$link = sqlsrv_connect( DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>