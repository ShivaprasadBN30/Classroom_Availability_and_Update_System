<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register User</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<h2>Register CR / Teacher</h2>

<form action="actions/register_action.php" method="POST">
    <label>Name:</label><br>
    <input type="text" name="name" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>

    <label>Role:</label><br>
    <select name="role" required>
        <option value="CR">CR</option>
        <option value="Teacher">Teacher</option>
    </select><br><br>

    <button type="submit">Register User</button>
</form>

</body>
</html>
