<?php
session_start();

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
  header("location: order.php");
  
  exit;
}

require "functions.php";
//check if user is already logged in and redirect them to their specific page

$username = $password = $username_error = $password_error = "";
$hashed_pwd = "";
$usertype = "";

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $username=stripcslashes($username);
    $password=stripcslashes($password);//remove slashes in password and username entry
	
    if(empty(trim($_POST["username"]))){
        $username_error = "Please enter username.";
        //check if username entry is empty
    } else{
        $username = trim($_POST["username"]);
    }
    
    if(empty(trim($_POST["password"]))){
        $password_error = "Please enter your password.";
        //check if password entry is empty
    } else{
        $password = trim($_POST["password"]);
    }
    
    if(empty($username_error) && empty($password_error)) {
        $sql = "SELECT id, username, password, status  FROM users WHERE username = '$username'";
       
        $result = selectData($sql);
        
        /////////////////////////////////////////////////////////////////////////////////////
        foreach($result[0] as $key=>$value) {
            if($key === 'id') $id = $value;
            if($key === 'username') $user = $value;
            if($key === 'password') $hashed_pwd = $value;
            if($key === 'status') $usertype = $value;
        }
		 $hashed_pwd = password_hash($password, PASSWORD_DEFAULT);
        // password hashing
        if(password_verify($password, $hashed_pwd)) {
		
            session_start();
            $_SESSION["loggedin"] = true;
            $_SESSION["id"] = $id;
            $_SESSION["username"] = $username;
      
            //IF STATEMENT FOR USER
            /*
            if($username == 'admin') { //change this with second login page for admin
                header("location: upload.php");
            } else {
                
                echo '<script>alert("You are not an admin! redirecting you to user login")</script>';
                echo '<script>window.location="login.php"</script>';
                exit;
				
            }
            */
            if($usertype == 'admin') { //change this with second login page for admin
                header("location: upload.php");
            } elseif($usertype == 'user') {
                echo '<script>alert("You are not an admin! redirecting you to user order page")</script>';
                echo '<script>window.location="cartorder.php"</script>';
            }
            else{
                echo '<script>alert("Error with user type")</script>';
                echo '<script>window.location="login.php"</script>';
                exit;	
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
    <title>Admin</title>
	<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		<link rel="stylesheet" href="foodadmin.css" type="text/css">
	<style>
	body {
   background-image:url("image/imgregister.jpg") ;
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
	<h2>Admin Login</h2>
			<br>
        <label>Username</label>
        <input type="text" name="username" value="<?php echo $username;?>"><br>
        <span class="help-block"><?php echo $username_error;?></span><br>
        <label>Password</label>
        <input type="password" name="password"><br>
        <span class="help-block"><?php echo $password_error;?></span><br>
        <input type="submit"a href="upload.php" value="Login" id="logibtn"><br>
		
		
    </form>
    
    <p>Don't have an account?<br><a href="register.php">Sign up</a> now.</p><br>
    <p>Not Admin? <a href="login.php">Log  in</a></p>
	</div>
	</div>
</body></html>