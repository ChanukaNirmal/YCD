<?php
session_start();
include "connection.php";

$viewer_id = $_SESSION['Viewer_ID'] ?? null;

// Handle cancellation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_order_id'])) {
  $order_id = $_POST['cancel_order_id'];
  $stmt = $conn->prepare("DELETE FROM `Order` WHERE Order_ID = ? AND Viewer_ID = ?");
  $stmt->bind_param("ii", $order_id, $viewer_id);
  $stmt->execute();
  $stmt->close();
  header("Location: orders.php"); // refresh after deletion
  exit;
}

// Fetch orders
$orders = [];
if ($viewer_id) {
  $sql = "SELECT o.Order_ID, o.Quantity, o.state, r.Recipe_title, r.thumbnail, r.Price 
          FROM `Order` o 
          JOIN Recipe r ON o.Recipe_ID = r.Recipe_ID 
          WHERE o.Viewer_ID = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $viewer_id);
  $stmt->execute();
  $result = $stmt->get_result();
  while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
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
  <title>My Orders</title>
  <link rel="stylesheet" href="../YCD/css/orders.css" />
</head>
<body>
<header>
  <div class="logo"><img src="../YCD/images/logo3.png" alt="You Cook Logo"/></div>
  <div class="search-bar">
    <input type="text" placeholder="Search channel or food item" />
    <button class="clear"><img src="../YCD/images/close.png" alt="Clear" /></button>
    <button class="search-btn"><img src="../YCD/images/search.png" alt="Search" /></button>
  </div>
  <div class="ordercart">
    <div class="orderBtn"><a href="orders.php"><button>Orders</button></a></div>
    <div class="cart"><a href="../YCD/cart.php"><img src="../YCD/images/cart.png" alt="cart" /></a></div>
    <div class="profile"><button><img src="../YCD/images/user.png" alt="User Icon" /></button></div>
</header>

<main class="orders-section">
  <?php if (count($orders) > 0): ?>
    <?php foreach ($orders as $order): ?>
      <?php
        $statusClass = 'pending';
        $statusText = 'Pending';
        $statusMessage = 'Creator will respond soon.';

        if ($order['state'] === 'rejected') {
          $statusClass = 'rejected';
          $statusText = 'Rejected';
          $statusMessage = 'Sorry, the creator rejected your order.';
        } elseif ($order['state'] === 'accepted') {
          $statusClass = 'preparing';
          $statusText = 'Preparing';
          $statusMessage = 'Your recipe is accepted. Creator is preparing your dish.';
        } elseif ($order['state'] === 'dispatched') {
          $statusClass = 'dispatched';
          $statusText = 'Dispatched';
          $statusMessage = 'Creator handed over your order to delivery service. It’s on the way.';
        }
      ?>
      <div class="order-card" data-status="<?= $statusClass ?>">
        <img src="<?= htmlspecialchars($order['thumbnail']) ?>" alt="Thumbnail">
        <div class="details">
          <p><strong><?= htmlspecialchars($order['Recipe_title']) ?></strong></p>
          <p>Quantity: <?= $order['Quantity'] ?></p>
          <p>Price: LKR <?= number_format($order['Price'] * $order['Quantity'] + 280, 2) ?></p>
          <div class="status">
            <span class="badge <?= $statusClass ?>"><?= $statusText ?></span>
            <p style="font-size: 0.9rem; color: gray;"><?= $statusMessage ?></p>

            <?php if ($order['state'] === null): ?>
              <form method="POST" class="cancel-form" onsubmit="return confirm('Are you sure you want to cancel this order?');">
                <input type="hidden" name="cancel_order_id" value="<?= $order['Order_ID'] ?>">
                <button type="submit" class="cancel-btn">Cancel</button>
              </form>
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <p style="padding:1rem;">You have no orders yet.</p>
  <?php endif; ?>
</main>

</body>
</html>
