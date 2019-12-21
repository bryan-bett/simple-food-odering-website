<?php
require "functions.php";

$username = $password = $existing_user = $psw_length = $usertype = $phone_length = "";
$none_match = ""; 

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);// removes spaces
    $sqlStat = "SELECT id FROM users WHERE username = '$username'";
    $result = selectData($sqlStat);
    if($result != null) {
        $existing_user = "This username already exists.";
    }
    
    if(strlen(trim($_POST["psw"])) < 8) {
        $psw_length = "Password must be at least 8 characters.";
    } else {
        $password = trim($_POST["psw"]);
    }

    if(strlen(trim($_POST["phone"])) < 10) {
        $phone_length = "Please check the entered number! ";
    } else {
        $phone = trim($_POST["phone"]);
    }
    
    $confirm_pass = trim($_POST["confirm_psw"]);
    if(empty($psw_length) && ($password != $confirm_pass)){
        $none_match = "Password did not match!";
    }
    $usertype = $_POST["type"];// change this to automatically register as normal user
                               // to change user to admin, log in as admin and chaner usertype    
    if(empty($existing_user) && empty($psw_length) && empty($none_match)){
        $hashed_psw = password_hash($password, PASSWORD_DEFAULT);

        $stmt = "INSERT INTO users (username, phone, status, password) VALUES('$username', '$phone','$usertype', '$hashed_psw')";//replaced with $hashed_psw
        if(insertData($stmt) === true) {
            header("location: login.php");
        } else {
            echo "There is a problem with the servers! please try again later!";
        }
    }
}
?>

<html>
<head>
    <title>Register User</title>
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
	</style>
</head>
<body>
	<div class="header">                
                <a href="login.php">Login</a>
                <a href="order.php">Order</a>
                <a class="active"  href="register.php">Register</a>
    </div>
	<div class="form">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label>Username</label><br>
        <input type="text" name="username" >
		<br>
        <span><?php echo $existing_user; ?></span><br>
        <label>Phone Number</label><br>
        <input type="text" name="phone" placeholder="07...">
        <br>
        <span><?php echo $phone_length; ?></span><br>
        <label>Password</label><br>
        <input type="password" name="psw" >
		<br>
        <span><?php echo $psw_length; ?></span><br>
        <label>Confirm Password</label><br>
        <input type="password" name="confirm_psw" >
		<br>
        <span><?php echo $none_match;?></span><br>

        <label>User Type</label><br>
        <input type="radio" name="type" value= "user" /> Client <br>
        <input type="radio" name="type" value= "admin"/> Admin <br>

        <input type="submit" value="Register">
		<p><a href="login.php">Log in</a></p>
	</form>
	</div>
</body>
</html>