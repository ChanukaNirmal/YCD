<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['channel_name'] = $_POST['channel_name'];
    $_SESSION['channel_link'] = $_POST['channel_link'];
    $_SESSION['subs'] = $_POST['subs'];
    $_SESSION['recipes'] = $_POST['recipes'];
    header("Location: cookInfo.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Creator Details</title>
    <link rel="stylesheet" href="../YCD/css/creatorInfo.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Recipe Creator Details</h1>
            <div class="logo"><img src="../YCD/images/logo.png" alt="Logo"></div>
        </header>
        <form method="POST" action="creatorInfo.php">
            <div class="form-group"><label>YouTube Channel Name</label><input type="text" name="channel_name" required></div>
            <div class="form-group"><label>Channel Link</label><input type="text" name="channel_link" required></div>
            <div class="form-group"><label>Subscribers</label><input type="number" name="subs" required></div>
            <div class="form-group"><label>Recipes</label><input type="number" name="recipes" required></div>
            <div class="button-group">
                <button type="submit" class="next-btn">Next</button>
                <button type="reset" class="reset-btn">Reset</button>
            </div>
        </form>
        <p class="sign-in">Already have an account? <a href="#">Sign in</a></p>
    </div>
</body>
</html>
