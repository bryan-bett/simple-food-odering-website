<?php

function connectToDatabase() {
	$servername="localhost";
	$username="root";
	$password="";
	$dbname="burger_proto";
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if(!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}
	return $conn;
}

function insertData($statement) {
	$link = connectToDatabase();
	
	if($link->query($statement) === TRUE) {
		echo "Data inserted successfully!";
        mysqli_close($link);
		return true;
	} else {
        echo "Error: " . $statement . "<br>" . $link->error;
        mysqli_close($link);
		return false;
	}
}
 
 function deleteData($statement) {
	$link = connectToDatabase();
	
	if($link->query($statement) === TRUE) {
		echo "Data Deleted successfully!";
        mysqli_close($link);
		return true;
	} else {
        echo "Error: " . $statement . "<br>" . $link->error;
        mysqli_close($link);
		return false;
	}
}

function selectData($statement) {
	$link = connectToDatabase();
	$dbresult = array();
	
	$result = $link->query($statement) or die($link->error);
	
	if($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$dbresult[] = $row;
		}
	}
	//print reults from db
	//print_r($dbresult);
    mysqli_close($link);
	return $dbresult;
}

function arrayToTable($array) {
    for($i = 0; $i < count($array); $i++) {
        echo "<tr>";
        foreach($array[$i] as $key=>$value) {
            //Checking if value is filepath
            if(strpos($value, 'image') !== false) {
                echo "<td ><img src='$value' height='100' width='100'></td>";
            } 
			else {
				echo "<td>$value</td>";
            }
        }
        echo "</tr>";
    }
}

function arrayToDropdown($array) {
    for($i = 0; $i < count($array); $i++) {
        foreach($array[$i] as $key=>$value) {
            echo "<option value='$value'>$value</option>";
        }
    }
}

?>
	
	