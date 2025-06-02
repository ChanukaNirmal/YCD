<?php
session_start();
include "connection.php";

// Redirect if not logged in
if (!isset($_SESSION['Creator_ID'])) {
  header("Location: Csignin.php");
  exit;
}

$creator_id = $_SESSION['Creator_ID'];
$recipes = [];

// Fetch recipe list
$query = "SELECT Recipe_title, Price, thumbnail FROM Recipe WHERE Creator_ID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $creator_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
  $recipes[] = $row;
}
$stmt->close();

// Fetch creator info with logo
$creatorInfo = [];
$creatorQuery = "SELECT Channel_name, Channel_link, Number_of_subscribers, logo FROM creatorRegister WHERE Creator_ID = ?";
$creatorStmt = $conn->prepare($creatorQuery);
$creatorStmt->bind_param("i", $creator_id);
$creatorStmt->execute();
$creatorResult = $creatorStmt->get_result();

if ($creatorRow = $creatorResult->fetch_assoc()) {
  $creatorInfo = $creatorRow;
}

$creatorStmt->close();
$conn->close();

// Set logo fallback if empty
$channelLogo = (!empty($creatorInfo['logo']) && file_exists($creatorInfo['logo']))
  ? $creatorInfo['logo']
  : '../YCD/images/default-profile.png';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Creator Home</title>
  <link rel="stylesheet" href="../YCD/css/creatorHome.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
  <header class="header">
    <div class="logo">
      <img src="../YCD/images/logo3.png" alt="You Cook Logo"/>
    </div>
    <div class="search-bar">
      <input type="text" placeholder="Search channel or food item" />
      <button class="clear">
        <img src="../YCD/images/close.png" alt="Clear" />
      </button>
      <button class="search-btn">
        <img src="../YCD/images/search.png" alt="Search" />
      </button>
    </div>
    <div class="actions">
      <a href="../YCD/addNewRecipe.php"><button class="create"><img src="../YCD/images/plus.png"> Create</button></a>
      <button class="profile"><img src="../YCD/images/user.png" alt="User Icon" /></button>
    </div>
  </header>

  <main>
    <section class="channel-info">
      <img src="<?= htmlspecialchars($channelLogo) ?>" alt="Channel Logo" class="channel-logo">
      <div class="channel-details">
        <h1><?= htmlspecialchars($creatorInfo['Channel_name'] ?? 'Your Channel') ?></h1>
        <p><?= htmlspecialchars($creatorInfo['Number_of_subscribers'] ?? 0) ?> subscribers</p>
        <p><?= count($recipes) ?> Recipes</p>
      </div>
      <div class="channel-buttons">
        <a href="../YCD/recipeRequest.php"><button class="orders"><img src="../YCD/images/orders.png"> Orders</button></a>
        <a href="../YCD/addNewRecipe.php"><button class="add-recipes"><img src="../YCD/images/addRec.png"> Add Recipes</button></a>
      </div>
    </section>

    <section class="my-recipes">
      <h2>My Recipes</h2>
      <div class="recipe-grid">
        <?php if (count($recipes) > 0): ?>
          <?php foreach ($recipes as $recipe): ?>
            <div class="recipe-card">
              <div class="thumbnail-wrapper">
                <img class="thumbnail" src="<?= htmlspecialchars($recipe['thumbnail']) ?>" alt="Recipe Thumbnail">
                <input type="checkbox" class="recipe-checkbox" />
              </div>
              <div class="card-info">
                <img class="channel-icon" src="<?= htmlspecialchars($channelLogo) ?>" alt="Channel Icon">
                <div>
                  <p class="title"><?= htmlspecialchars($recipe['Recipe_title']) ?></p>
                  <p class="meta">LKR <?= number_format($recipe['Price'], 2) ?></p>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p style="padding: 1rem;">You have not added any recipes yet.</p>
        <?php endif; ?>
      </div>
    </section>
  </main>
</body>
</html>

