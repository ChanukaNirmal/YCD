<?php
session_start();
include "connection.php";


// Fetch all recipes with creator info, randomly
$sql = "
  SELECT 
    r.Recipe_ID,              
    r.Recipe_title,
    r.Price,
    r.thumbnail,
    c.Channel_name,
    c.logo AS channel_logo
  FROM Recipe r
  INNER JOIN creatorRegister c ON r.Creator_ID = c.Creator_ID
  ORDER BY RAND()
";

$result = $conn->query($sql);
$recipes = [];

if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $recipes[] = $row;
  }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>You Cook Delivers</title>
  <link rel="stylesheet" href="../YCD/css/viewerHome.css" />
  <style>
    a.video-card {
      text-decoration: none;
      color: inherit;
      display: block;
    }
  
  .profile {
    position: relative;
    display: inline-block;
  }

  .sign-out-menu {
    display: none;
    position: absolute;
    top: 100%;
    right: 0;
    background-color: white;
    border: 1px solid #ccc;
    padding: 5px 10px;
    z-index: 100;
    border-radius: 4px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
  }

  .sign-out-menu button {
    background: none;
    border: none;
    color: #333;
    cursor: pointer;
    font-size: 14px;
  }

  .sign-out-menu button:hover {
    color: red;
  }

  </style>
</head>
<body>
  <header>
    <div class="logo">
      <img src="../YCD/images/logo3.png" alt="You Cook Logo"/>
    </div>
    <div class="search-bar">
      <input type="text" placeholder="Search channel or food item" />
      <button class="clear"><img src="../YCD/images/close.png" alt="Clear" /></button>
      <button class="search-btn"><img src="../YCD/images/search.png" alt="Search" /></button>
    </div>
    <div class="ordercart">
      <div class="orderBtn">
        <a href="../YCD/riderRequest.php"><button>Delivery Request</button></a>
      </div>
      <div class="cart">
        <a href="../YCD/cart.php"><img src="../YCD/images/cart.png" alt="cart" /></a>
      </div>
    
<div class="profile">
  <button id="userBtn">
    <img src="../YCD/images/user.png" alt="User Icon" />
  </button>
  <div id="signOutMenu" class="sign-out-menu">
    <button onclick="signOut()">Sign Out</button>
  </div>
</div>
  </header>

  <main>
    <section class="video-grid">
      <?php if (count($recipes) > 0): ?>
        <?php foreach ($recipes as $recipe): ?>
          <a href="productDetails.php?recipe_id=<?= $recipe['Recipe_ID'] ?>" class="video-card">
            <img src="<?= htmlspecialchars($recipe['thumbnail']) ?>" alt="Recipe Thumbnail" />
            <div class="video-info">
              <h4><?= htmlspecialchars($recipe['Recipe_title']) ?></h4>
              <div class="channel-info">
                <img src="<?= htmlspecialchars($recipe['channel_logo'] ?: '../YCD/images/default-profile.png') ?>" alt="Channel Logo" />
                <span><?= htmlspecialchars($recipe['Channel_name']) ?> • LKR <?= number_format($recipe['Price'], 2) ?></span>
              </div>
            </div>
          </a>
        <?php endforeach; ?>
      <?php else: ?>
        <p style="padding: 1rem;">No recipes available at the moment.</p>
      <?php endif; ?>
    </section>
  </main>

  <script src="../YCD/javascript/home.js"> </script>
  <script src="../YCD/javascript/signout.js"> </script>

</body>
</html>
