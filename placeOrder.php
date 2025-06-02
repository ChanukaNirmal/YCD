<?php
session_start();
include "connection.php";

// Handle order submission
$successMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $viewer_id = $_SESSION['Viewer_ID'] ?? null;
    $recipe_id = $_POST['recipe_id'];
    $quantity = $_POST['quantity'];

    if ($viewer_id && $recipe_id && $quantity) {
        $stmt = $conn->prepare("SELECT Address FROM viewerRegister WHERE Viewer_ID = ?");
        $stmt->bind_param("i", $viewer_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $viewerData = $result->fetch_assoc();
        $address = $viewerData['Address'] ?? '';

        $insert = $conn->prepare("INSERT INTO `Order` (Address, Quantity, Recipe_ID, Viewer_ID) VALUES (?, ?, ?, ?)");
        $insert->bind_param("siii", $address, $quantity, $recipe_id, $viewer_id);
        if ($insert->execute()) {
            $successMessage = "✅ Your order was placed successfully. Check your orders in 'My Orders'.";
        } else {
            $successMessage = "❌ Something went wrong. Please try again.";
        }
        $insert->close();
    }
}

// Load recipe and viewer info
$recipe_id = $_GET['recipe_id'] ?? ($_POST['recipe_id'] ?? null);
$viewer_id = $_SESSION['Viewer_ID'] ?? null;
$recipe = $viewer = null;

if ($recipe_id) {
    $stmt = $conn->prepare("SELECT * FROM Recipe r JOIN creatorRegister c ON r.Creator_ID = c.Creator_ID WHERE r.Recipe_ID = ?");
    $stmt->bind_param("i", $recipe_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $recipe = $result->fetch_assoc();
    $stmt->close();
}

if ($viewer_id) {
    $stmt = $conn->prepare("SELECT Name, Address FROM viewerRegister WHERE Viewer_ID = ?");
    $stmt->bind_param("i", $viewer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $viewer = $result->fetch_assoc();
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Place Order</title>
  <link rel="stylesheet" href="../YCD/css/placeOrder.css" />
</head>
<body>

<?php if (!empty($successMessage)) : ?>
  <div style="background: #d4edda; padding: 1rem; margin: 1rem; border: 1px solid #c3e6cb; color: #155724;">
    <?= $successMessage ?>
  </div>
<?php endif; ?>

<header>
  <div class="logo"><img src="../YCD/images/logo3.png" alt="You Cook Logo"/></div>
  <div class="search-bar">
    <input type="text" placeholder="Search channel or food item" />
    <button class="clear"><img src="../YCD/images/close.png" alt="Clear" /></button>
    <button class="search-btn"><img src="../YCD/images/search.png" alt="Search" /></button>
  </div>
  <div class="ordercart">
    <div class="orderBtn"><a href="orders.php"><button>Orders</button></a></div>
    <div class="cart"><a href="cart.php"><img src="../YCD/images/cart.png" alt="cart" /></a></div>
    <div class="profile"><button><img src="../YCD/images/user.png" alt="User Icon" /></button></div>
  </div>
</header>

<main>
  <section class="main-section">
    <div class="left-section">
      <div class="slideshow-container">
        <?php for ($i = 1; $i <= 3; $i++): ?>
          <?php if (!empty($recipe["Dish_image_$i"])): ?>
            <div class="slide fade"><img src="<?= htmlspecialchars($recipe["Dish_image_$i"]) ?>" /></div>
          <?php endif; ?>
        <?php endfor; ?>

        <div class="dots">
          <?php for ($i = 1; $i <= 3; $i++): ?>
            <?php if (!empty($recipe["Dish_image_$i"])): ?>
              <span class="dot" onclick="currentSlide(<?= $i ?>)"></span>
            <?php endif; ?>
          <?php endfor; ?>
        </div>
      </div>

      <div class="video-details">
        <div class="channel-logo">
          <img src="<?= htmlspecialchars($recipe['logo']) ?>" alt="Channel Logo" />
        </div>
        <div class="video-info">
          <h2><?= htmlspecialchars($recipe['Recipe_title']) ?></h2>
          <p><?= htmlspecialchars($recipe['Channel_name']) ?></p>
          <p>Preparation Time: <?= $recipe['Preparation_time'] ?> mins</p>
          <p>Price: <span id="itemPrice"><?= $recipe['Price'] ?></span> LKR</p>
          <p>Delivery Areas: <?= htmlspecialchars($recipe['Delivery_areas']) ?></p>
        </div>
      </div>
    </div>

    <div class="right-section">
      <!-- 🛒 Place Order Form -->
      <form class="order-form" method="POST" onsubmit="return confirmOrder()" id="orderForm">
        <input type="hidden" name="recipe_id" value="<?= $recipe_id ?>">
        <input type="hidden" name="place_order" value="1">

        <label>Customer Name</label>
        <input type="text" value="<?= htmlspecialchars($viewer['Name'] ?? '') ?>" disabled />

        <label>Delivery Address</label>
        <input type="text" value="<?= htmlspecialchars($viewer['Address'] ?? '') ?>" disabled />

        <label>Quantity</label>
        <select id="quantity" name="quantity" onchange="calculateTotal()">
          <option value="1" selected>1</option>
          <option value="2">2</option>
          <option value="3">3</option>
          <option value="4">4</option>
        </select>

        <label>Payment Method</label>
        <input type="text" value="Cash on Delivery" disabled />

        <div class="price-summary">
          <p>Delivery Fee <span>LRK 280.00</span></p>
          <p>Food item Fee <span id="foodItemFee">LRK <?= number_format($recipe['Price'], 2) ?></span></p>
          <p><strong>Total</strong> <strong id="totalFee">LRK <?= number_format($recipe['Price'] + 280, 2) ?></strong></p>
        </div>

        <div class="form-buttons" style="display: flex; justify-content: space-between; align-items: center; gap: 10px;">
          <div>
            <button type="button" class="my-orders-btn" onclick="window.location.href='orders.php'">My Orders</button>
          </div>
          <div style="display: flex; gap: 10px;">
            <button type="submit" class="place-order-btn">Place Order</button>
          </div>
        </div>
      </form>

      <!-- 🛒 Add to Cart Form (separate) -->
      <form method="POST" action="addToCart.php" style="margin-top: 10px; text-align: right;">
        <input type="hidden" name="recipe_id" value="<?= $recipe_id ?>">
        <input type="hidden" name="quantity" id="cartQuantity" value="1">
        <button type="submit" class="place-order-btn" style="background: #444;">Add to Cart</button>
      </form>
    </div>
  </section>
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

  function calculateTotal() {
    const price = parseFloat(document.getElementById("itemPrice").textContent);
    const qty = parseInt(document.getElementById("quantity").value);
    const deliveryFee = 280;
    const itemFee = price * qty;
    document.getElementById("foodItemFee").textContent = "LRK " + itemFee.toFixed(2);
    document.getElementById("totalFee").textContent = "LRK " + (itemFee + deliveryFee).toFixed(2);
    document.getElementById("cartQuantity").value = qty;
  }

  function confirmOrder() {
    return confirm("Are you sure you want to confirm this order?");
  }

  calculateTotal();
</script>

</body>
</html>
