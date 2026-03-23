<!DOCTYPE html>
<html>
<head>
    <title>CR / Teacher Login</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<h1 class="dashboard-title">CR / Teacher Login</h1>

<form action="actions/login_action.php" method="POST">
    <label>Email</label>
    <input type="email" name="email" required>

    <label>Password</label>
    <input type="password" name="password" required>

    <label style="margin-bottom:15px;">
        <input type="checkbox" name="remember_me"> Remember Me
    </label>

    <button type="submit">Login</button>
</form>

</body>
</html>
