<?php 
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
	echo '<script>alert("Please Login first!")</script>';
    echo '<script>window.location="login.php"</script>';
   exit;
    
}
$user = htmlspecialchars($_SESSION["username"]);

require "functions.php";
$connect = connectToDatabase();
if(isset($_POST["add_to_cart"]))
{
	if(isset($_SESSION["food_cart"]))
	{
		$item_array_id = array_column($_SESSION["food_cart"], "item_id");
		if(!in_array($_GET["id"], $item_array_id))
		{
			$count = count($_SESSION["food_cart"]);
			$item_array = array(
				'item_id'			=>	$_GET["id"],
				'item_name'			=>	$_POST["hidden_name"],
				'item_price'		=>	$_POST["hidden_price"],
				'item_quantity'		=>	$_POST["quantity"]
			);
			$_SESSION["food_cart"][$count] = $item_array;
		}
		else
		{
			echo '<script>alert("Item Already Added")</script>';
		}
	}
	else
	{
		$item_array = array(
			'item_id'			=>	$_GET["id"],
			'item_name'			=>	$_POST["hidden_name"],
			'item_price'		=>	$_POST["hidden_price"],
			'item_quantity'		=>	$_POST["quantity"]
		);
		$_SESSION["food_cart"][0] = $item_array;
	}
}

if(isset($_GET["action"]))
{
	if($_GET["action"] == "delete")
	{
		foreach($_SESSION["food_cart"] as $keys => $values)
		{
			if($values["item_id"] == $_GET["id"])
			{
				unset($_SESSION["food_cart"][$keys]);
				echo '<script>alert("Item Removed")</script>';
				echo '<script>window.location="cartorder.php"</script>';
			}
		}
	}
}



?>
<!DOCTYPE html>
<html>
	<head>
		<title>Order</title>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
		<link rel="stylesheet" href="foodadmin.css" type="text/css">

	<style>
	
		body {
		background-image:url("image/imgadmin.jpg") ;
		background-color: white;
		background-repeat: no-repeat;
		background-position: center;
		background-size: cover;
		}
	
		img:hover {
		opacity: 0.5;
		}
	</style>
	
	</head>
	<body>
		
		<div class="header">
                <a href="logout.php">Logout</a>
                <a class="active" href="cartorder.php">Order</a>
                <a href="">Help</a>
                 <?php echo "<div>Hello, $user!</div>";
?>
        </div>
		<div class="container">
			<br>
			
			<h3 align="center"> Choose  Food </h3><br>
			<br><br>
			<?php
				$sql = "SELECT * FROM food ORDER BY id";
				$result = mysqli_query($connect, $sql);
				if(mysqli_num_rows($result) > 0)
				{
					while($row = mysqli_fetch_array($result))
					{
				?>
			<div class="col-md-3">
				<form method="post" action="cartorder.php?action=add&id=<?php echo $row["id"]; ?>">
					<div class="foodorder" style="border:1px solid #333;background-color:white;border-radius:5px; padding:16px;" align="center">
						
						<img src="<?php echo $row["filepath"]; ?>" class="img-responsive" /><br />
						
						<br>

						<h4 class="text-info"><?php echo $row["foodname"]; ?></h4>

						<h4 class="text-danger">Ksh <?php echo $row["price"]; ?></h4>

						<input type="text" name="quantity" value="1" class="form-control">

						<input type="hidden" name="hidden_name" value="<?php echo $row["foodname"]; ?>">

						<input type="hidden" name="hidden_price" value="<?php echo $row["price"]; ?>">

						<input type="submit" name="add_to_cart" style="margin-top:10px;" class="btn btn-success" value="Add to Cart">
						<br>
						
					</div>
				</form>
			</div>
		
		
			<?php
					}
				}
			?>
			<div style="clear:both"></div>
			<br>
			<div class="ordertable">
			<h3>Order Details</h3>
			<div class="table-responsive">
				<table class="table table-bordered">
					<tr>
						<th width="40%" style="background-color:white;">Item Name</th>
						<th width="10%" style="background-color:white;">Quantity</th>
						<th width="20%" style="background-color:white;">Price</th>
						<th width="15%" style="background-color:white;">Total</th>
						<th width="5%" style="background-color:white;">Action</th>
					</tr>
					<?php
					if(!empty($_SESSION["food_cart"]))
					{
						$total = 0;
						foreach($_SESSION["food_cart"] as $keys => $values)
						{
					?>
					<tr>
						<td style="background-color:white;"><?php echo $values["item_name"]; ?></td>
						<td style="background-color:white;"><?php echo $values["item_quantity"]; ?></td>
						<td style="background-color:white;">$ <?php echo $values["item_price"]; ?></td>
						<td style="background-color:white;">$ <?php echo number_format($values["item_quantity"] * $values["item_price"], 2);?></td>
						<td style="background-color:white;"><a href="cartorder.php?action=delete&id=<?php echo $values["item_id"]; ?>"><span class="text-danger">Remove</span></a></td>
					</tr>
					<?php
							$total = $total + ($values["item_quantity"] * $values["item_price"]);
						}
					?>
					<tr>
						<td colspan="3" align="right" style="background-color:white;">Total</td>
						<td align="right" style="background-color:white;">$ <?php echo number_format($total, 2); ?></td>
						<td ></td>
					</tr>
					<?php
					}
					
					?>
						
				</table>
			</div>
		</div>
		
			<p style="background-color:white;border-radius: 4px;">Done ordering? <a href="cartorder.php?action=checkout&id=<?php echo $values["item_id"]; ?>"><input type="submit" name="add_to_cart" style="margin-top:10px;" class="btn btn-success" value="check out"></a></p>
		
		</div>
	
	<br>
	</body>
</html>
<?php

$status = array("Complete", "delivered", "Pending");
$randindex = array_rand($status);
if(isset($_GET["action"]))
{

	if($_GET["action"] == "checkout")
	{
		foreach($_SESSION["food_cart"] as $keys => $values)
		{	$user = htmlspecialchars($_SESSION["username"]);
			$today = date("Y-m-d H:i:s");
			$sql = "INSERT INTO orders (user_name, date_odered, amount, status) VALUES('$user', '$today', '$total', '$status[$randindex]')";
			$insert = insertData($sql);
				echo '<script>alert("Thank you for odering with us! We are currently calling you about your order")</script>';
				 echo '<script>window.location="logout.php"</script>';
		}
	}
}
?>