<?php
session_start();
include("../config/db.php");

$email = $_POST['email'];
$password = md5($_POST['password']);

$query = "SELECT * FROM users 
          WHERE email='$email' 
          AND password='$password' 
          AND (role='CR' OR role='Teacher')";

$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 1) {

    $user = mysqli_fetch_assoc($result);

    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['name'] = $user['name'];

    /* 🔐 REMEMBER ME COOKIE */
    if (isset($_POST['remember_me'])) {
        setcookie(
            "remember_user",
            $user['user_id'],
            time() + (86400 * 7), // 7 days
            "/"
        );
    }

    header("Location: ../dashboard.php");
    exit();

} else {
    echo "Invalid Login Credentials";
}
?>
