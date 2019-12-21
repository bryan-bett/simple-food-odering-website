<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
   echo '<script>alert("Please Login first!")</script>';
    echo '<script>window.location="login.php"</script>';
   exit;
}


$message="";

$user = htmlspecialchars($_SESSION["username"]);


require "functions.php";
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $foodname = $_POST['foodname'];
    $price = $_POST['price'];
    $filename = $_FILES['image']['name'];
    $filetype = $_FILES['image']['type'];
	
    if($filetype == 'image/jpeg' or $filetype == 'image/png' or $filetype == 'image/jpg') {
		$filepath = "image/" . $filename;
		 $sql = "INSERT INTO food (foodname, filepath, price) VALUES('$foodname', '$filepath', '$price')";
        if (move_uploaded_file($_FILES['image']['tmp_name'],'image/' . $filename)){
			$message= "done";
			
		
        
       
        if(insertData($sql) === true) {
           $message= "done";
		   header("location: table.php");
            exit;
        }
		}
    }
   else {
	   echo "Wrong file type";
   }
}
?>

<HTML>
<head>
    <title>Admin Page</title>
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
                <a href="logout.php">logout</a>
                <a class="active" href="upload.php">Upload</a>
                <a href="cartorder.php">Order</a>
                <a href="">Help</a>
                <?php echo "<div>Hello, $user!</div>";
?>
            </div>
            <div class="form">
<h3>Upload new foods to the system</h3>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <label>Food Name</label><br>
        <input type="text" name="foodname"><br><br>
        <label>Image</label><br>
        <input type="file" name="image" accept="image/*"><br><br>
        <label>Price</label><br>
        <input type="number" name="price"><br><br>
        <input type="submit" value="Upload"><br><?php echo $message ?>
    </form>
	<a href='logout.php'>Log out    </a>  <&nbsp> <a href="table.php">Catalogue</a>
</div>
</body>
</HTML>