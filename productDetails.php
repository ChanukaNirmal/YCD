<?php
session_start();
include 'connection.php';

// Get recipe ID from URL
$recipe_id = $_GET['recipe_id'] ?? null;
$recipe = null;
$sideRecipes = [];

$reviews = [];
if ($recipe_id) {
    $revStmt = $conn->prepare("SELECT ReviewerName, Rating, Comment FROM Review WHERE Recipe_ID = ? ORDER BY Created_at DESC");
    $revStmt->bind_param("i", $recipe_id);
    $revStmt->execute();
    $revResult = $revStmt->get_result();
    while ($row = $revResult->fetch_assoc()) {
        $reviews[] = $row;
    }
    $revStmt->close();
}


if ($recipe_id) {
    // Main recipe + creator info
    $stmt = $conn->prepare("
        SELECT r.*, c.Channel_name, c.logo 
        FROM Recipe r
        JOIN creatorRegister c ON r.Creator_ID = c.Creator_ID
        WHERE r.Recipe_ID = ?
    ");
    $stmt->bind_param("i", $recipe_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $recipe = $result->fetch_assoc();
    $stmt->close();

    // Random 3 other recipes for side videos
    $sideQuery = "
        SELECT r.Recipe_ID, r.Recipe_title, r.Price, r.thumbnail, c.Channel_name 
        FROM Recipe r 
        JOIN creatorRegister c ON r.Creator_ID = c.Creator_ID 
        WHERE r.Recipe_ID != ? 
        ORDER BY RAND() LIMIT 3
    ";
    $sideStmt = $conn->prepare($sideQuery);
    $sideStmt->bind_param("i", $recipe_id);
    $sideStmt->execute();
    $sideResult = $sideStmt->get_result();
    while ($row = $sideResult->fetch_assoc()) {
        $sideRecipes[] = $row;
    }
    $sideStmt->close();
}

// Handle Review Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    $viewer_id = $_SESSION['Viewer_ID'] ?? null;
    $reviewer_name = $_POST['reviewer_name'] ?? '';
    $rating = $_POST['rating'] ?? '';
    $comment = $_POST['comment'] ?? '';

    if ($recipe_id && $reviewer_name && $rating && $comment) {
        $stmt = $conn->prepare("INSERT INTO Review (Viewer_ID, Recipe_ID, ReviewerName, Rating, Comment) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iisis", $viewer_id, $recipe_id, $reviewer_name, $rating, $comment);
        $stmt->execute();
        $stmt->close();
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
  <link rel="stylesheet" href="../YCD/css/productDetails.css" />
  <style>
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

<!-- Header -->
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
    <div class="orderBtn"><a href="../YCD/orders.php"><button>Orders</button></a></div>
    <div class="cart"><a href="../YCD/cart.php"><img src="../YCD/images/cart.png" alt="cart" /></a></div>
  
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
<section class="main-section">
  <div class="left-section">
    <div class="slideshow-container">
  <?php if ($recipe): ?>
    <?php $images = []; ?>
    <?php for ($i = 1; $i <= 3; $i++): ?>
      <?php if (!empty($recipe["Dish_image_$i"])): ?>
        <?php $images[] = $recipe["Dish_image_$i"]; ?>
        <div class="slide fade">
          <img src="<?= htmlspecialchars($recipe["Dish_image_$i"]) ?>" />
        </div>
      <?php endif; ?>
    <?php endfor; ?>

    <!-- Dots -->
    <div class="dots">
      <?php foreach ($images as $index => $_): ?>
        <span class="dot" onclick="currentSlide(<?= $index + 1 ?>)"></span>
      <?php endforeach; ?>
    </div>

    <!-- Thumbnails with link to YouTube -->
    <?php if (!empty($recipe['ytLink'])): ?>
      <div class="thumbnail-bar">
        <?php foreach ($images as $image): ?>
          <a href="<?= htmlspecialchars($recipe['ytLink']) ?>" target="_blank">
            <img class="thumbnail" src="<?= htmlspecialchars($image) ?>" alt="Dish thumbnail" />
          </a>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  <?php endif; ?>
</div>


    <div class="video-details">
      <?php if ($recipe): ?>
        <div class="channel-logo"><img src="<?= $recipe['logo'] ?>" alt="Channel Logo" /></div>
        <div class="video-info">
          <h2><?= htmlspecialchars($recipe['Recipe_title']) ?></h2>
          <p><?= htmlspecialchars($recipe['Channel_name']) ?></p>
          <p>Preparation Time: <?= $recipe['Preparation_time'] ?> mins</p>
          <p>Price: <?= number_format($recipe['Price'], 2) ?> LKR</p>
          
          <p>Delivery Areas: <?= htmlspecialchars($recipe['Delivery_areas']) ?></p>
        </div>
        <div class="buttons">

        <a href="placeOrder.php?recipe_id=<?= $recipe['Recipe_ID'] ?>">
  <button class="order-btn">Order Now</button> </a>
   <form method="POST" action="addToCart.php">
        <input type="hidden" name="recipe_id" value="<?= $recipe_id ?>">
        <input type="hidden" name="quantity" id="cartQuantity" value="1">
        <button class="add-btn">Add To Cart</button></a>
      </form>
          
        </div>
      <?php else: ?>
        <p style="padding:1rem; color:red;">Recipe not found.</p>
      <?php endif; ?>
    </div>
  </div>

  <!-- Side Videos -->
  <div class="side-videos">
    <?php foreach ($sideRecipes as $side): ?>
      <div class="video-card" onclick="location.href='productDetails.php?recipe_id=<?= $side['Recipe_ID'] ?>'">
        <img src="<?= htmlspecialchars($side['thumbnail']) ?>" />
        <p class="title"><?= htmlspecialchars($side['Recipe_title']) ?></p>
        <p class="channel"><?= htmlspecialchars($side['Channel_name']) ?> - LKR <?= number_format($side['Price'], 2) ?></p>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- Review Section -->
<div class="review-section">
  <h3>Customer Reviews</h3>

  <?php if (!empty($reviews)): ?>
    <?php foreach ($reviews as $review): ?>
      <div class="review">
        <p class="reviewer">👤 <?= htmlspecialchars($review['ReviewerName']) ?></p>
        <p class="stars"><?= str_repeat('⭐', intval($review['Rating'])) ?></p>
        <p class="comment"><?= nl2br(htmlspecialchars($review['Comment'])) ?></p>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <p>No reviews yet for this recipe.</p>
  <?php endif; ?>

  <form class="review-form" method="POST">
    <h4>Leave a Review</h4>
    <input type="text" name="reviewer_name" placeholder="Your Name" required />
    <select name="rating" required>
      <option value="">Rating</option>
      <option value="5">⭐⭐⭐⭐⭐</option>
      <option value="4">⭐⭐⭐⭐</option>
      <option value="3">⭐⭐⭐</option>
      <option value="2">⭐⭐</option>
      <option value="1">⭐</option>
    </select>
    <textarea name="comment" placeholder="Write your review here..." required></textarea>
    <button type="submit" name="submit_review">Submit Review</button>
  </form>
</div>
</main>

<script>
let slideIndex = 1;
showSlide(slideIndex);
function showSlide(n) {
  let slides = document.getElementsByClassName("slide");
  let dots = document.getElementsByClassName("dot");
  if (n > slides.length) slideIndex = 1;
  if (n < 1) slideIndex = slides.length;
  for (let i = 0; i < slides.length; i++) slides[i].style.display = "none";
  for (let i = 0; i < dots.length; i++) dots[i].classList.remove("active");
  slides[slideIndex - 1].style.display = "block";
  if (dots[slideIndex - 1]) dots[slideIndex - 1].classList.add("active");
}
function currentSlide(n) {
  slideIndex = n;
  showSlide(slideIndex);
}

</script>
 <script src="../YCD/javascript/signout.js"> </script>
</body>
</html>

