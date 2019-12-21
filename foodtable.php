<?php
require('functions.php');
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}
if(isset($_POST['submit'])){
    $link = connectToDatabase();
    $id = $_SESSION["id"];
   
    $item = $_POST['item'];
    
    $quantity = $_POST["quantity"];
    
    $sql = "SELECT * FROM food WHERE item = '$item'";
    $row = getData($sql);
    echo "<br>";
    //check items from food table
    //print_r($row);
    echo "<br>";
    $image = $row[0]["image"];
    $price = $row[0]["price"];
    for($i = 0; $i < $quantity; $i++){
        echo "<img src='$image' height='80' width='80'>";
    }
    $total = $quantity * $price;
    $sql = "INSERT INTO orders(user_id, amount) VALUES($id, $total)";
    if(mysqli_query($link, $sql)){
        $last_item_id = mysqli_insert_id($link);
        $sql2 = "INSERT INTO order_details(order_id, unit_amount, description, quantity) VALUES($last_item_id, $price, '$item', $quantity)";
        insertData($sql2);
        }
    }


?>
<html>
    <body>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <label for="item">Food Item: </label>
            <select name="item">
                <?php
                
                $sql = "SELECT item FROM items";
                $result = mysqli_query($sql);
                while($row = mysqli_fetch_assoc($result)){
                    echo "<option value='".$row['item']."'>".$row['item']."</option>";
                }
                ?>
            </select><br>
            
            <label for="quantity">Quantity</label>
            <input type="text" name="quantity"><br>
            <input type="submit" name="submit" value="Order">
        </form>

        <a href="logout.php">Sign Out of Your Account</a>
    </body>
</html>
