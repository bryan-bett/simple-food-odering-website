<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}
//
//
//
// use jquery to add a lot of items to the db from the basket 
//check ajax
//bookmarks from kiplangat.strath...
//
//
//
//
//

$user = htmlspecialchars($_SESSION["username"]);
echo "<div>Hello, $user!</div>";

require "functions.php";
?>

<html>
<head>
	<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
   <h1>Buy burger</h1>
   <label>Food item</label><br>
    <select name="foodname">
        <?php
        $sql = "SELECT foodname FROM food";
        $result = selectData($sql);
        arrayToDropdown($result);
        ?>
    </select><br><br>
    <label>Quantity</label><br>
    <input name="qty" type="number"><br><br>
    <input type="submit" value="Order"><br>
	<a href="logout.php">Log out</a>
	
</form>
    
	</body>
<?php
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $food = $_POST["foodname"];
    $qty = $_POST["qty"];
    
    
    $sql = "SELECT filepath, price FROM food WHERE foodname='$food' LIMIT 1";
    $dbresult = selectData($sql);
    
    foreach($dbresult[0] as $key=>$value) {
		 if(is_numeric($value)) {
            $amount = $value * $qty;
            echo "Please pay Ksh. $amount.";
        }
        for($i = 0; $i < $qty; $i++) {
            if(strpos($value, 'image/') !== false) {
            echo "<div style='display: inline'><img src='$value' height='100' width='100'></div>";
            }
        }
       
    }
}
?>