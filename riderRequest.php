<?php
session_start();
include "connection.php";

$rider_id = $_SESSION['Rider_ID'] ?? null;

if (!$rider_id) {
    echo "<p style='padding:1rem; color:red;'>Please log in as a rider to view your delivery requests.</p>";
    exit;
}

$requests = [];

$sql = "
  SELECT 
    r.Ride_ID,
    rec.Recipe_title AS item,
    cr.Location AS pickup,
    cr.Phone_number AS pickup_phone,
    vr.Address AS dropoff,
    vr.Phone_number AS dropoff_phone
  FROM ride r
  JOIN `Order` o ON r.Order_ID = o.Order_ID
  JOIN Recipe rec ON o.Recipe_ID = rec.Recipe_ID
  JOIN creatorRegister cr ON rec.Creator_ID = cr.Creator_ID
  JOIN viewerRegister vr ON o.Viewer_ID = vr.Viewer_ID
  WHERE r.Rider_ID = ?
  ORDER BY r.Ride_ID DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $rider_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $requests[] = $row;
}
$stmt->close();
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>New Delivery Requests</title>
  <link rel="stylesheet" href="../YCD/css/riderRequest.css" />
</head>
<body>
  <header>
    <h1>New Delivery Requests</h1>
    <img src="../YCD/images/logo.png" alt="Logo" class="logo" />
  </header>
  <hr />
  <div class="requests-container">
    <?php if (count($requests) > 0): ?>
      <?php foreach ($requests as $request): ?>
        <div class="request-card">
          <div class="request-info">
            <p><strong>Food Item</strong>: <?= htmlspecialchars($request['item']) ?></p>
            <p><strong>Pickup Location</strong>: <?= htmlspecialchars($request['pickup']) ?></p>
            <p><strong>Pickup Contact Number</strong>: <?= htmlspecialchars($request['pickup_phone']) ?></p>
            <p><strong>Drop-off Location</strong>: <?= htmlspecialchars($request['dropoff']) ?></p>
            <p><strong>Drop-off Contact Number</strong>: <?= htmlspecialchars($request['dropoff_phone']) ?></p>
            <p><strong>Estimated Delivery Fee</strong>: LKR 280.00</p>
          </div>
          <div class="buttons">
            <button class="accept-btn" 
                    onclick="openMap('<?= urlencode($request['pickup']) ?>', '<?= urlencode($request['dropoff']) ?>')">
              Accept
            </button>
            <button class="reject-btn" onclick="rejectCard(this)">Reject</button>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p style="padding: 1rem;">No new delivery requests.</p>
    <?php endif; ?>
  </div>

  <script>
    function openMap(pickup, dropoff) {
      const url = `https://www.google.com/maps/dir/?api=1&origin=${pickup}&destination=${dropoff}`;
      window.open(url, '_blank');
    }

    function rejectCard(btn) {
      if (confirm("Are you sure you want to reject this delivery?")) {
        btn.closest('.request-card').remove();
      }
    }
  </script>
</body>
</html>
