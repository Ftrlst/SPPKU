<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/login.css">
</head>
<body>
    <!-- <div class="login-container">
        <h2>Login</h2>
        <form method="POST" action="../controllers/AuthController.php">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div> -->
    <div class="login-container">
        <div class="login-box">
            <h2>LOGIN</h2>
            <form action="../controllers/AuthController.php" method="POST">
                <label>Username</label>
                <input type="text" name="username" required>
                <label>Password</label>
                <input type="password" name="password" required>
                <button type="submit">Login</button>
            </form>
        </div>
        <div class="login-image">
            <img src="../assets/img/hiasan.png" alt="Login Image">
        </div>
    </div>
</body>
</html>
