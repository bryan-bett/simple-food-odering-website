<?php

session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    echo '<script>alert("Please Login first!")</script>';
    echo '<script>window.location="login.php"</script>';
   exit;
    
}
$user = htmlspecialchars($_SESSION["username"]);
require "functions.php";

?>

<html>
<head>
	<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">	
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
	<link rel="stylesheet" href="foodadmin.css" type="text/css">
    <style>
        body {
   background-image:url("image/imgregister.jpg") ;
   background-color: white;
    background-repeat: no-repeat;
    background-position: center;
    background-size: cover;
    margin:0px;
    }
    img:hover {
        opacity: 0.9;
        }
    </style>
</head>
<body>
    <h3>We feed them</h3>
    <div class="table-responsive">
<table class="table table-bordered" style="font-weight: bold;
  color: #000000;
  background-color:white;opacity: 0.8;">
    <tr>
    <th style=""></th>
        <th width="25%" style="">Food Item</th>
        <th width="30%" style="">Image</th>
        <th width="10%" style="">Action</th>
         <th width="10%" style="">Price</th>
    </tr>
    


    <?php
    
    $id="";

    $conn = connectToDatabase();
    $sql1 = "SELECT foodname, filepath, price FROM food";
    $dbresult1 = selectData($sql1);
    //arrayToTable($dbresult);
    for($i = 0; $i < count($dbresult1); $i++) {
        echo "<tr>";?>
        <html>
        <td style="font-weight: bold;
  color: #000000; opacity:0.8 color:white;"> <?php echo $j=$i+1; ?> </td>
        </html>
        <?php
        foreach($dbresult1[$i] as $key=>$value) {//
            //Checking if value is filepath
            if(strpos($value, 'image') !== false) {
                echo "<td><img src='$value' height='100' width='100'></td>";
                ?>
                <html>
                "<td style="background-color:white;"><a href="table.php?action=remove&i=<?php echo $i; ?>"><input type="submit" name="remove" style="margin-top:10px;" class="btn btn-danger" value="Remove"></a></td>"
                </html>
                <?php

            } 
            else {
                echo "<td>$value</td>";
            }
        }
        echo "</tr>";
    }
    
    $foodname="";

    //$sql2 = "SELECT foodname FROM food WHERE id = $id";
    //$foodname = selectData($sql2);

    
    //$remove="";
    if(isset($_POST["action"])){
       
        if($_GET["action"] == "remove")
        {
           $sql2 = "DELETE FROM food WHERE id = '$i'";
            $dbresult2 = deleteData($sql2);
            echo '<script>alert("Item  Deleted")</script>';
        }
    }
    $conn->close();
    ?>    
</table> 

<table class="table table-bordered" style="font-weight: bold;
  color: #000000;
  background-color:white;opacity: 0.8;">
    <h3>Purchases</h3>
    <tr>

        <th width="25%" style="">User</th>
        <th width="30%" style="">Date</th>
        <th width="10%" style="">Amount</th>
         <th width="10%" style="">Status</th>
    </tr>


<?php
    $id="";
    $conn = connectToDatabase();
    $sql3 = "SELECT user_name, date_odered, amount, status FROM orders";
    $dbresult3 = selectData($sql3);
   
    for($i = 0; $i < count($dbresult3); $i++) {
        echo "<tr>";
        foreach($dbresult3[$i] as $key=>$value) {
                echo "<td>$value</td>";
        }
        echo "</tr>";
    }
    ?>
    </table>


<p>Back to <a href="upload.php">Upload?</a></p>
<br><br><br><br>
</div>

</body>
</html>