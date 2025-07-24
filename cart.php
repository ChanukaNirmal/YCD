<?php
session_start();
include "connection.php";

$viewer_id = $_SESSION['Viewer_ID'] ?? null;
$cartItems = [];

if ($viewer_id) {
  $stmt = $conn->prepare("SELECT c.Cart_ID, c.Quantity, r.Recipe_ID, r.Recipe_title, r.thumbnail, r.Price 
                          FROM Cart c 
                          JOIN Recipe r ON c.Recipe_ID = r.Recipe_ID 
                          WHERE c.Viewer_ID = ?");
  $stmt->bind_param("i", $viewer_id);
  $stmt->execute();
  $result = $stmt->get_result();

  while ($row = $result->fetch_assoc()) {
    $cartItems[] = $row;
  }
  $stmt->close();
}
$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Wish List</title>
  <link rel="stylesheet" href="../YCD/css/cart.css" />
</head>
<body>
<header>
  <div class="logo"><img src="../YCD/images/logo3.png" alt="Logo"/></div>
  <div class="search-bar">
    <input type="text" placeholder="Search channel or food item" />
    <button class="clear"><img src="../YCD/images/close.png" alt="Clear" /></button>
    <button class="search-btn"><img src="../YCD/images/search.png" alt="Search" /></button>
  </div>
  <div class="ordercart">
    <div class="orderBtn"><a href="orders.php"><button>Orders</button></a></div>
    <div class="cart"><a href="cart.php"><img src="../YCD/images/cart.png" alt="Cart" /></a></div>
    <div class="profile"><button><img src="../YCD/images/user.png" alt="User Icon" /></button></div>
  </div>
</header>

<main class="orders-container">
  <?php if (!empty($cartItems)): ?>
    <?php foreach ($cartItems as $item): ?>
      <div class="order-item">
        <img src="<?= htmlspecialchars($item['thumbnail']) ?>" alt="Food Image" class="order-img" />
        <div class="order-details">
          <p class="title"><?= htmlspecialchars($item['Recipe_title']) ?></p>
          <p><strong>Quantity:</strong> <?= $item['Quantity'] ?></p>
          <p><strong>Price:</strong> LKR <?= number_format($item['Quantity'] * $item['Price'], 2) ?></p>
          <div class="order-buttons" style="display: flex; gap: 10px;">
  <form action="placeOrder.php" method="GET" style="margin: 0;">
    <input type="hidden" name="recipe_id" value="<?= $item['Recipe_ID'] ?>">
    <button type="submit" class="buy-btn">Buy Now</button>
  </form>
  <form action="removeFromCart.php" method="POST" style="margin: 0;">
    <input type="hidden" name="cart_id" value="<?= $item['Cart_ID'] ?>">
    <button type="submit" class="remove-btn" onclick="return confirm('Remove this item?')">Remove</button>
  </form>
</div>
        </div>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <p style="padding: 1rem;">Your cart is empty.</p>
  <?php endif; ?>
</main>
</body>
</html>


