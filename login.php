<?php
session_start();
require "functions.php";

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
  header("location: cartorder.php");
  exit;
}
$username = $password = $username_error = $password_error = "";
$usertype = "";


if($_SERVER["REQUEST_METHOD"] == "POST") {
	//prevent mysql injection
    $username=stripcslashes($username);
    $password=stripcslashes($password);
	
    
    if(empty(trim($_POST["username"]))){
        $username_error = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    if(empty(trim($_POST["password"]))){
        $password_error = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    


    if(empty($username_error) && empty($password_error)) {
        $sql = "SELECT id, username, password, status FROM users WHERE username = '$username'";
       
        $result = selectData($sql);
        
        
        foreach($result[0] as $key=>$value) {
            if($key === 'id') $id = $value;
            if($key === 'username') $user = $value;
            if($key === 'password') $hashed_pwd = $value;
            if($key === 'status') $usertype = $value;
        }
        
        if(password_verify($password, $hashed_pwd)) {
	
		
            session_start();
            $_SESSION["loggedin"] = true;
            $_SESSION["id"] = $id;
            $_SESSION["username"] = $username;
      
            
            if($usertype == 'admin') {
                echo '<script>alert("Redirecting to admin login ")</script>';
                header("location: upload.php");
            } elseif($usertype == 'user') {
                header("location: cartorder.php");
            }
            else{
                echo '<script>alert("Error with user type")</script>';
                echo '<script>window.location="login.php"</script>';
            }
			
		}
        else{
			$password_error = "Invalid password.";
			

        }
        
    }
}
?>

<html>
<head>
    <title>Login</title>
	<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="foodadmin.css" type="text/css">
	<style>
	body {
   background-image:url("image/imgadmin.jpg") ;
   background-color: white;
	background-repeat: no-repeat;
	background-position: center;
	background-size: cover;
	}
	</style>
</head>

<body>
	<div class="wrapper center-block">  
			<div class="header">
                <a class="active" href="login.php">Login</a>
                <a href="cartorder.php">Order</a>
                <a href="">Help</a>
            </div>
			<br>
			<div class="form">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
	<h2>Login</h2>
			<br>
        <label>Username</label><br>
        <input type="text" name="username" value="<?php echo $username;?>"><br>
        <span class="help-block"><?php echo $username_error;?></span><br>
        <label>Password</label><br>
        <input type="password" name="password"><br>
        <span class="help-block"><?php echo $password_error;?></span><br>
        <input type="submit"a href="upload.php" value="Login"><br>
		
		
    </form>
    
    <p>Don't have an account?<br><a href="register.php">Sign up</a> now.</p>
	<div class="admin"><p><a href="adminlogin.php">Admin?</a></p></div>
	</div>
	</div>
	
	
</body></html>