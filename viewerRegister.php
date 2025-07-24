<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign Up</title>
  <link rel="stylesheet" href="../YCD/css/viewerRegister.css" />
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
    <form action="" method="POST">
      <input type="text" name="name" placeholder="Name" required />
      <input type="text" name="address" placeholder="Address" required />
      <input type="email" name="email" placeholder="Email" required />
      <input type="tel" name="phone" placeholder="Phone Number" required />
      <input type="password" name="password" placeholder="Password" required />
      <button type="submit" class="signup-btn">Sign up</button>
    </form>

    <?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'connection.php'; // Connect to database

    $name = $_POST['name'] ?? '';
    $address = $_POST['address'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $password = $_POST['password'] ?? '';
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists
    $check_sql = "SELECT Email FROM viewerregister WHERE Email = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        echo "<p style='color:red;'>This email is already registered. Please use a different email.</p>";
    } else {
        // Insert new record
        $insert_sql = "INSERT INTO viewerregister (Name, Address, Email, Phone_number, Password) VALUES (?, ?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);

        if ($insert_stmt) {
            $insert_stmt->bind_param("sssss", $name, $address, $email, $phone, $hashed_password);
            if ($insert_stmt->execute()) {
                echo "<p style='color:green;'>Registration successful!</p>";
          
                // Redirect to login page or another page if needed
                header("Location: ../YCD/Vsignin.php");
            } else {
                echo "<p style='color:red;'>Error: " . $insert_stmt->error . "</p>";
            }
            $insert_stmt->close();
        } else {
            echo "<p style='color:red;'>Error in preparing insert statement: " . $conn->error . "</p>";
        }
    }

    $check_stmt->close();
    $conn->close();
}
?>


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
      Have an account? <a href="../YCD/signin.html">Sign in</a>
    </p>
  </div>
</div>
</body>
</html>
