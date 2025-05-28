<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign Up</title>
  <link rel="stylesheet" href="../YCD/css/deliveryRegister.css" />
</head>
<body>
    <div class="container">
        <header>
      <h1>Register</h1>
      <div class="logo">
        <img src="../YCD/images/logo.png" alt="Logo">
      </div>
    </header>
  <div class="form-container">
    <h2>Sign up</h2>
    <form method ="POST" action = "">
      <input type="text"name ="name" placeholder="Company or Rider Name" />
      <input type="text"name ="Area" placeholder="Service Area" />
      <input type="email"name ="email" placeholder="Email" />
      <input type="tel"name ="phone" placeholder="Phone Number" />
      <input type="password"name ="password" placeholder="Password" />
      <button type="submit" class="signup-btn">Sign up</button>
    </form>

    <div class="divider">
      <hr />
      <span>or continue with</span>
      <hr />
    </div>

    <button class="google-btn">
      <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google icon" />
      Log in
    </button>

    <p class="signin-text">
      have an account? <a href="./">Sign in</a>
    </p>
  </div>
  </div>
</body>
</html>
<?php
session_start();
$registerMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include "connection.php"; // ✅ Fix the typo here

    $name = $_POST['name'] ?? '';
    $service_area = $_POST['Area'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $password = $_POST['password'] ?? '';
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists
    $check_sql = "SELECT Email FROM riderregister WHERE Email = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        $registerMessage = "<p style='color:red;'>This email is already registered.</p>";
    } else {
        $insert_sql = "INSERT INTO riderregister (Name, Service_area, Email, Phone_number, Password) VALUES (?, ?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        if ($insert_stmt) {
            $insert_stmt->bind_param("sssss", $name, $service_area, $email, $phone, $hashed_password);
            if ($insert_stmt->execute()) {
                header("Location: ../YCD/Rsignin.php");
                exit;
            } else {
                $registerMessage = "<p style='color:red;'>Error: " . $insert_stmt->error . "</p>";
            }
            $insert_stmt->close();
        } else {
            $registerMessage = "<p style='color:red;'>Insert error: " . $conn->error . "</p>";
        }
    }

    $check_stmt->close();
    $conn->close();
}
?>
