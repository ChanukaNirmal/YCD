<?php
session_start();
include 'connection.php';

// Get recipe ID from URL
$recipe_id = $_GET['recipe_id'] ?? null;
$recipe = null;
$sideRecipes = [];

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

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>You Cook Delivers</title>
  <link rel="stylesheet" href="../YCD/css/productDetails.css" />
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
    <div class="orderBtn"><a href="../YCD/orders.html"><button>Orders</button></a></div>
    <div class="cart"><a href="../YCD/cart.html"><img src="../YCD/images/cart.png" alt="cart" /></a></div>
  </div>
  <div class="profile"><button><img src="../YCD/images/user.png" alt="User Icon" /></button></div>
</header>

<main>
<section class="main-section">
  <div class="left-section">
    <div class="slideshow-container">
      <?php if ($recipe): ?>
        <?php if (!empty($recipe['Dish_image_1'])): ?>
          <div class="slide fade"><img src="<?= $recipe['Dish_image_1'] ?>" /></div>
        <?php endif; ?>
        <?php if (!empty($recipe['Dish_image_2'])): ?>
          <div class="slide fade"><img src="<?= $recipe['Dish_image_2'] ?>" /></div>
        <?php endif; ?>
        <?php if (!empty($recipe['Dish_image_3'])): ?>
          <div class="slide fade"><img src="<?= $recipe['Dish_image_3'] ?>" /></div>
        <?php endif; ?>
        <div class="dots">
          <?php for ($i = 1; $i <= 3; $i++): ?>
            <?php if (!empty($recipe["Dish_image_$i"])): ?>
              <span class="dot" onclick="currentSlide(<?= $i ?>)"></span>
            <?php endif; ?>
          <?php endfor; ?>
        </div>
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
          <button class="order-btn">Order Now</button>
          <button class="add-btn">Add To Cart</button>
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
  <div class="review">
    <p class="reviewer">👤 Amal Perera</p>
    <p class="stars">⭐⭐⭐⭐☆</p>
    <p class="comment">The kebabs were crispy and perfectly spiced. Will definitely order again!</p>
  </div>
  <div class="review">
    <p class="reviewer">👤 Nadeesha Silva</p>
    <p class="stars">⭐⭐⭐⭐⭐</p>
    <p class="comment">Fast delivery and delicious food. Highly recommended!</p>
  </div>

  <form class="review-form">
    <h4>Leave a Review</h4>
    <input type="text" placeholder="Your Name" required />
    <select required>
      <option value="">Rating</option>
      <option value="5">⭐⭐⭐⭐⭐</option>
      <option value="4">⭐⭐⭐⭐</option>
      <option value="3">⭐⭐⭐</option>
      <option value="2">⭐⭐</option>
      <option value="1">⭐</option>
    </select>
    <textarea placeholder="Write your review here..." required></textarea>
    <button type="submit">Submit Review</button>
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
</body>
</html>

