
<!DOCTYPE html>

<?php
ob_start(); // Prevent header issues
session_start();
include "connection.php";

$message = "";

// // Ensure creator is logged in
// if (!isset($_SESSION['creator_id'])) {
//   header("Location: signin.php");
//   exit;
// }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  
  $title = $_POST['title'];
  $ytLink = $_POST['ytLink'];
  $description = $_POST['description'];
  $prep_time = (int)$_POST['prepTime'];
  $price = (float)$_POST['price'];
  $delivery_areas = $_POST['deliveryAreas'];
  $creator_id = $_SESSION['Creator_ID'];

  // Upload helper
  function uploadImage($inputName, $folder = "uploads/") {
    if (!empty($_FILES[$inputName]['name'])) {
      $file = $_FILES[$inputName];
      $target = $folder . basename($file['name']);
      move_uploaded_file($file['tmp_name'], $target);
      return $target;
    }
    return null;
  }

  $thumbnail = uploadImage('thumbnail');
  $dish1 = uploadImage('dishImage1');
  $dish2 = uploadImage('dishImage2');
  $dish3 = uploadImage('dishImage3');

  if ($title && $ytLink && $description && $prep_time > 0 && $price > 0 && $delivery_areas) {
    $stmt = $conn->prepare("
      INSERT INTO Recipe 
        (Recipe_title, Recipe_description, Preparation_time, Price, Delivery_areas, thumbnail, Dish_image_1, Dish_image_2, Dish_image_3, ytLink, Creator_ID)
      VALUES 
        (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
      "sssdssssssi",
      $title, $description, $prep_time, $price, $delivery_areas,
      $thumbnail, $dish1, $dish2, $dish3, $ytLink, $creator_id
    );

    if ($stmt->execute()) {
      $message = "<p style='color:green;'>Recipe added successfully!</p>";
      
      sleep(2);
       header("Location: creatorHome.php");
    } else {
      $message = "<p style='color:red;'>Error: " . $stmt->error . "</p>";
    }
    $stmt->close();
  } else {
    $message = "<p style='color:red;'>Please fill out all required fields correctly.</p>";
  }
}
?>

<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add a New Recipe</title>
  <link rel="stylesheet" href="../YCD/css/addNewRecipe.css">
</head>
<body>
  <div class="container">
    <header>
      <h1>Add a New Recipe</h1>
      <img src="../YCD/images/logo.png" alt="Logo" class="logo">
    </header>



    <form id="recipeForm" method="POST" enctype="multipart/form-data" action="">
      <label>Recipe Title</label>
      <input type="text" name="title" required>

      <label>YouTube Video URL</label>
      <input type="url" name="ytLink" id="videoUrl" required placeholder="e.g., https://www.youtube.com/watch?v=abc123">

      <label>Description</label>
      <textarea name="description" required></textarea>

      <label>Preparation Time (minutes)</label>
      <input type="number" name="prepTime" required min="1">

      <label>Price (LKR)</label>
      <input type="number" name="price" required min="1" step="0.01">

      <label>Delivery Areas</label>
      <input type="text" name="deliveryAreas" required>

      <label>Custom Thumbnail</label>
      <input type="file" name="thumbnail" accept="image/*">

      <label>Dish Images</label>
      <input type="file" name="dishImage1" accept="image/*">
      <input type="file" name="dishImage2" accept="image/*">
      <input type="file" name="dishImage3" accept="image/*">

      <div class="buttons">
        <button type="button" onclick="previewRecipe()">Preview</button>
        <button type="submit">Publish</button>
      </div>
    </form>

        <?php 
    
    echo $message;
    ?>

    


    <div id="previewContainer">
      <h2>Recipe Preview</h2>
      <div id="preview"></div>
    </div>
  </div>

  <script>
    function previewRecipe() {
      const videoUrl = document.getElementById('videoUrl').value;
      const title = document.querySelector('input[name="title"]').value;
      const description = document.querySelector('textarea[name="description"]').value;
      const prepTime = document.querySelector('input[name="prepTime"]').value;
      const price = document.querySelector('input[name="price"]').value;
      const deliveryAreas = document.querySelector('input[name="deliveryAreas"]').value;
      const dishImage = document.querySelector('input[name="thumbnail"]').files[0];

      const preview = document.getElementById('preview');
      preview.innerHTML = '';

      const embedUrl = videoUrl.includes("watch?v=")
        ? videoUrl.replace("watch?v=", "embed/")
        : videoUrl;

      if (dishImage) {
        const reader = new FileReader();
        reader.onload = function (e) {
          preview.innerHTML = `
            <div class="card">
              <img src="${e.target.result}" alt="Dish Image">
              <div>
                <strong>${title}</strong><br>
                ${description}<br><br>
                Preparation Time: ${prepTime} mins<br>
                Price: LKR ${price}<br>
                Delivery Areas: ${deliveryAreas}<br><br>
                <iframe width="100%" height="200" src="${embedUrl}" frameborder="0" allowfullscreen></iframe>
              </div>
            </div>
          `;
        };
        reader.readAsDataURL(dishImage);
      }
    }
  </script>
</body>
</html>



