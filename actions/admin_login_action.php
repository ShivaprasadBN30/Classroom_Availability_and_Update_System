<?php
session_start();
include("../config/db.php");

$email = $_POST['email'];
$password = md5($_POST['password']);

$query = "SELECT * FROM users 
          WHERE email='$email' 
          AND password='$password' 
          AND role='Admin'";

$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 1) {
    $admin = mysqli_fetch_assoc($result);
    $_SESSION['admin_id'] = $admin['user_id'];
    header("Location: ../admin_register.php");
} else {
    echo "Invalid Admin Credentials";
}
?>
