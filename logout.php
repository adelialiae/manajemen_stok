<?php 

session_start();
session_destroy();
header("Location: index.php");

//delete cookie
setcookie('username', '', time() - 3600);
setcookie('key', '', time() - 3600);


?>