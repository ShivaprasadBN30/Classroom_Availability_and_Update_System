<?php
session_start();
include("../config/db.php");

/* Security check */
if (!isset($_SESSION['admin_id'])) {
    die("Unauthorized Access");
}

$name = $_POST['name'];
$email = $_POST['email'];
$password = md5($_POST['password']);
$role = $_POST['role'];

$query = "INSERT INTO users (name, email, password, role)
          VALUES ('$name', '$email', '$password', '$role')";

if (mysqli_query($conn, $query)) {
    echo "User Registered Successfully <br><br>";
    echo "<a href='../admin_register.php'>Register Another User</a>";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
