<?php
session_start();
include "connection.php";

$order_id = $_GET['order_id'] ?? null;
$location_filter = $_GET['location'] ?? 'Colombo';

// Get pickup and drop-off locations
$pickup = $dropoff = "";
if ($order_id) {
    $stmt = $conn->prepare("
        SELECT 
            c.Location AS pickup, 
            v.Address AS dropoff 
        FROM `Order` o
        JOIN Recipe r ON o.Recipe_ID = r.Recipe_ID
        JOIN creatorRegister c ON r.Creator_ID = c.Creator_ID
        JOIN viewerRegister v ON o.Viewer_ID = v.Viewer_ID
        WHERE o.Order_ID = ?
    ");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $stmt->bind_result($pickup, $dropoff);
    $stmt->fetch();
    $stmt->close();
}

// Handle rider assignment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['assign_rider'])) {
    $rider_id = $_POST['rider_id'];
    $order_id = $_POST['order_id'];
    $pickup = $_POST['pickup'];
    $dropoff = $_POST['dropoff'];

    $insert = $conn->prepare("INSERT INTO ride (Order_ID, Rider_ID, Pickup_Location, Dropoff_Location) VALUES (?, ?, ?, ?)");
    $insert->bind_param("iiss", $order_id, $rider_id, $pickup, $dropoff);
    $insert->execute();
    $insert->close();

    echo "<script>alert('Rider assigned successfully!'); window.location.href='findRider.php?order_id=$order_id';</script>";
    exit;
}

// Fetch available riders
$riders = [];
$sql = "SELECT * FROM riderregister WHERE Service_area = ? OR ? = 'All Locations'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $location_filter, $location_filter);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $riders[] = $row;
}
$stmt->close();
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Find a Delivery Rider</title>
  <link rel="stylesheet" href="../YCD/css/findRider.css" />
</head>
<body>
 <div class="container">
  <header>
    <div class="title-section">
      <h1>Find a Delivery Rider</h1>
      <p>Select a rider to deliver your food orders.</p>
    </div>
    <div class="order-id">Order ID : <?= htmlspecialchars($order_id) ?></div>
    <div class="logo"><img src="../YCD/images/logo.png" alt="Logo"></div>
  </header>

  <hr />

  <!-- Filter Section -->
  <form method="GET" class="filter-section">
    <label for="location">Filter by Location:</label>
    <select id="location" name="location" onchange="this.form.submit()">
      <option <?= $location_filter === 'All Locations' ? 'selected' : '' ?>>All Locations</option>
      <option <?= $location_filter === 'Colombo' ? 'selected' : '' ?>>Colombo</option>
      <option <?= $location_filter === 'Gampaha' ? 'selected' : '' ?>>Gampaha</option>
      <option <?= $location_filter === 'Kaluthara' ? 'selected' : '' ?>>Kaluthara</option>
      <option <?= $location_filter === 'Kegalle' ? 'selected' : '' ?>>Kegalle</option>
    </select>
    <input type="hidden" name="order_id" value="<?= $order_id ?>">
  </form>

  <!-- Riders Table -->
  <table>
    <thead>
      <tr>
        <th>Rider ID</th>
        <th>Rider Name</th>
        <th>Service Area</th>
        <th>Phone</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($riders as $rider): ?>
      <tr>
        <td><?= 'R' . str_pad($rider['Rider_ID'], 4, '0', STR_PAD_LEFT) ?></td>
        <td><?= htmlspecialchars($rider['Name']) ?></td>
        <td><?= htmlspecialchars($rider['Service_area']) ?></td>
        <td><?= htmlspecialchars($rider['Phone_number']) ?></td>
        <td>
          <form method="POST">
            <input type="hidden" name="order_id" value="<?= $order_id ?>">
            <input type="hidden" name="rider_id" value="<?= $rider['Rider_ID'] ?>">
            <input type="hidden" name="pickup" value="<?= htmlspecialchars($pickup) ?>">
            <input type="hidden" name="dropoff" value="<?= htmlspecialchars($dropoff) ?>">
            <button type="submit" name="assign_rider" class="connect-btn">Share Details</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>

</body>
</html>
