<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['name'] = $_POST['name'];
    $_SESSION['address'] = $_POST['address'];
    $_SESSION['email'] = $_POST['email'];
    $_SESSION['phone'] = $_POST['phone'];
    $_SESSION['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
    header("Location: creatorInfo.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Basic Information</title>
    <link rel="stylesheet" href="../YCD/css/basicInfo.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Basic Information</h1>
            <div class="logo">
                <img src="../YCD/images/logo.png" alt="Logo">
            </div>
        </header>
        <form method="POST" action="basicInfo.php">
            <div class="form-group"><label>Name</label><input type="text" name="name" required></div>
            <div class="form-group"><label>Address</label><input type="text" name="address" required></div>
            <div class="form-group"><label>Email</label><input type="email" name="email" required></div>
            <div class="form-group"><label>Phone</label><input type="tel" name="phone" required></div>
            <div class="form-group"><label>Password</label><input type="password" name="password" required></div>
            <div class="button-group">
                <button type="submit" class="next-btn">Next</button>
                <button type="reset" class="reset-btn">Reset</button>
            </div>
        </form>
        <p class="sign-in">Already have an account? <a href="#">Sign in</a></p>
    </div>
</body>
</html>
