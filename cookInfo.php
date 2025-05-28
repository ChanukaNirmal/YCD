<?php
session_start();
include 'connection.php'; // Your DB connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_SESSION['name'];
    $email = $_SESSION['email'];
    $phone = $_SESSION['phone'];
    $password = $_SESSION['password'];
    $channel_name = $_SESSION['channel_name'];
    $channel_link = $_SESSION['channel_link'];
    $subs = $_SESSION['subs'];
    $recipes = $_SESSION['recipes'];
    $location = $_POST['location']; // collected in this step

    $sql = "INSERT INTO creatorRegister (Name, Email, Phone_number, Password, Channel_name, Channel_link, Number_of_subscribers, Number_of_recipes, Location)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssiss", $name, $email, $phone, $password, $channel_name, $channel_link, $subs, $recipes, $location);

    if ($stmt->execute()) {
        session_destroy();
        header("Location: Csignin.php");
        exit;
    } else {
        $error = "Registration failed: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Business & Cooking Info</title>
  <link rel="stylesheet" href="../YCD/css/cookInfo.css" />
</head>
<body>
  <div class="container">
    <header>
      <h1>Business & Cooking Information</h1>
      <div class="logo"><img src="../YCD/images/logo.png" alt="Logo"></div>
    </header>

    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="POST" action="cookInfo.php">
      <div class="form-group">
        <label for="location">Location</label>
        <input type="text" name="location" required placeholder="Where they cook from" />
      </div>

      <div class="terms">
        <strong>Terms & Agreement</strong>
        <div class="checkbox-group">
          <label><input type="checkbox" id="terms"> Agree to Terms</label><br>
          <label><input type="checkbox" id="safety"> Agree to Safety</label>
        </div>
      </div>

      <div class="buttons">
        <button type="submit" class="signup" id="signupBtn" disabled>Sign up</button>
        <button type="reset" class="reset">Reset</button>
      </div>

      <p class="sign-in">Already have an account? <a href="#">Sign in</a></p>
    </form>
  </div>

  <script>
    const terms = document.getElementById('terms');
    const safety = document.getElementById('safety');
    const btn = document.getElementById('signupBtn');
    function toggle() {
      btn.disabled = !(terms.checked && safety.checked);
    }
    terms.addEventListener('change', toggle);
    safety.addEventListener('change', toggle);
  </script>
</body>
</html>
