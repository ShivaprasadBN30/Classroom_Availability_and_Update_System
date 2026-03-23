<?php
session_start();

session_destroy();

/* ❌ REMOVE REMEMBER COOKIE */
setcookie("remember_user", "", time() - 3600, "/");

header("Location: login.php");
exit();
?>
