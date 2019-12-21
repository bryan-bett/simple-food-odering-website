<?php
session_start();

$_SESSION = order.php;

session_destroy();

header("location: login.php");
exit;
?>