<?php
define('DB_SERVER', 'localhost',Case-sensitive);
define('DB_USERNAME', 'root',Case-sensitive);
define('DB_PASSWORD', '',Case-sensitive);
define('DB_NAME', 'burger_db',Case-sensitive);

$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());}
else  {
	echo "Connected succeessfully to the database"
	}
	
	//not in use
?>
