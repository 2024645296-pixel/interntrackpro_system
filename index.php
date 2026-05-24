<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>InternTrack Pro</title>

<link rel="stylesheet" href="assets/css/login.css">

</head>

<body>

<div class="login-container">

    <div class="logo">
        Intern<span>Track</span>
    </div>

    <p class="subtitle">
        Internship Management System
    </p>

    <?php if(isset($_GET['error'])){ ?>

        <div class="error-msg">
            Invalid Email or Password
        </div>

    <?php } ?>

    <form action="auth/login.php" method="POST">

        <div class="input-group">

            <label>Email</label>

            <input 
                type="email"
                name="email"
                placeholder="Enter your email"
                required
            >

        </div>

        <div class="input-group">

            <label>Password</label>

            <input 
                type="password"
                name="password"
                placeholder="Enter your password"
                required
            >

        </div>

        <button type="submit" class="login-btn">
            Login
        </button>

    </form>

    <p class="footer-text">
        © 2026 InternTrack Pro
    </p>

</div>

</body>
</html>