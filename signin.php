<?php
session_start();
$loginError = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'connection.php';

    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Function to check credentials in a table
    function checkCredentials($conn, $table, $emailField, $passwordField, $email, $password) {
        $stmt = $conn->prepare("SELECT $passwordField FROM $table WHERE $emailField = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($hashedPassword);
            $stmt->fetch();
            if (password_verify($password, $hashedPassword)) {
                return true;
            }
        }
        return false;
    }

    // Check in each table
    if (checkCredentials($conn, 'creatorRegister', 'Email', 'Password', $email, $password)) {
        header("Location: creatorHome.html");
        exit;
    } elseif (checkCredentials($conn, 'viewerRegister', 'Email', 'Password', $email, $password)) {
        header("Location: viewerHome.html");
        exit;
    } elseif (checkCredentials($conn, 'riderRegister', 'Email', 'Password', $email, $password)) {
        header("Location: riderHome.html");
        exit;
    } else {
        $loginError = "Invalid email or password.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sign In</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="/css/signin.css" />
</head>
<body>
  <div class="container-fluid min-vh-100 bg-dark text-white d-flex flex-column">
    
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center p-4 border-bottom border-secondary">
      <h3 class="mb-0">Sign In</h3>
      <img src="../YCD/images/logo.png" alt="Logo" class="logo-img" height="50px" />
    </div>

    <!-- Login Form Section -->
    <div class="formBorder">
      <div class="flex-grow-1 d-flex justify-content-center align-items-center">
        <div class="login-card p-5">
          <h4 class="text-center fw-bold mb-4">Sign in</h4>

          <?php if (!empty($loginError)) : ?>
            <div class="alert alert-danger text-center" role="alert">
              <?= $loginError ?>
            </div>
          <?php endif; ?>

          <form method="POST" action="signin.php">
            <input type="email" name="email" class="form-control form-input mb-3" placeholder="Email" required />
            <input type="password" name="password" class="form-control form-input mb-2" placeholder="Password" required />
            <div class="text-end mb-4">
              <a href="#" class="small text-secondary">Forgot your password?</a>
            </div>

            <button type="submit" class="btn btn-light w-100 mb-4 fw-bold">Log in</button>

            <div class="d-flex align-items-center justify-content-between text-secondary my-3">
              <hr class="flex-grow-1" />
              <span class="mx-2 small">or continue with</span>
              <hr class="flex-grow-1" />
            </div>

            <button type="button" class="btn btn-outline-light w-100 google-btn d-flex align-items-center justify-content-center">
              <img src="../YCD/images/google.png" alt="Google" class="google-icon me-2" width="25px" />
              Log in
            </button>
          </form>

          <p class="text-center text-secondary mt-4 mb-0 small">
            Don’t have an account? <a href="viewerRegister.php" class="text-white fw-semibold">Sign up</a>
          </p>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
