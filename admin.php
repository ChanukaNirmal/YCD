<?php
include 'connection.php';

// Fetch all creators
$creators = $conn->query("SELECT Creator_ID, Channel_name, Phone_number FROM creatorRegister")->fetch_all(MYSQLI_ASSOC);

// Fetch all viewers
$viewers = $conn->query("SELECT Viewer_ID, Name, Phone_number FROM viewerRegister")->fetch_all(MYSQLI_ASSOC);

// Fetch all riders
$riders = $conn->query("SELECT Rider_ID, Name, Phone_number FROM riderRegister")->fetch_all(MYSQLI_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="../YCD/css/admin.css" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
</head>
<body>

<nav>
  <div class="logo"><img src="../YCD/images/logo3.png" alt="Logo" /></div>
  <div class="search-bar">
    <form method="GET"><input type="text" name="search" placeholder="Search..." /><button type="submit"><i class="fas fa-search"></i></button></form>
  </div>
</nav>

<!-- Creators -->
<div class="creator-details">
  <h1>Creators</h1>
  <table>
    <thead>
      <tr><th>ID</th><th>Channel Name</th><th>Phone Number</th><th>Action</th></tr>
    </thead>
    <tbody>
      <?php foreach ($creators as $creator): ?>
        <tr>
          <td><?= $creator['Creator_ID'] ?></td>
          <td><?= htmlspecialchars($creator['Channel_name']) ?></td>
          <td><?= htmlspecialchars($creator['Phone_number']) ?></td>
          <td>
          
            <a href="deleteUser.php?type=creator&id=<?= $creator['Creator_ID'] ?>
            " onclick="return confirm('Delete this creator?')"><i class="fas fa-trash-alt" style="color: red;"></i></a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<!-- Viewers -->
<div class="viewer-details">
  <h1>Viewers</h1>
  <table>
    <thead>
      <tr><th>ID</th><th>Name</th><th>Phone Number</th><th>Action</th></tr>
    </thead>
    <tbody>
      <?php foreach ($viewers as $viewer): ?>
        <tr>
          <td><?= $viewer['Viewer_ID'] ?></td>
          <td><?= htmlspecialchars($viewer['Name']) ?></td>
          <td><?= htmlspecialchars($viewer['Phone_number']) ?></td>
          <td>
            
            <a href="deleteUser.php?type=viewer&id=<?= $viewer['Viewer_ID'] ?>" onclick="return confirm('Delete this viewer?')"><i class="fas fa-trash-alt"style="color: red;"></i></a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<!-- Riders -->
<div class="rider-details">
  <h1>Riders</h1>
  <table>
    <thead>
      <tr><th>ID</th><th>Name</th><th>Phone Number</th><th>Action</th></tr>
    </thead>
    <tbody>
      <?php foreach ($riders as $rider): ?>
        <tr>
          <td><?= $rider['Rider_ID'] ?></td>
          <td><?= htmlspecialchars($rider['Name']) ?></td>
          <td><?= htmlspecialchars($rider['Phone_number']) ?></td>
          <td>
           
            <a href="deleteUser.php?type=rider&id=<?= $rider['Rider_ID'] ?>" onclick="return confirm('Delete this rider?')"><i class="fas fa-trash-alt"style="color: red;"></i></a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

</body>
</html>
