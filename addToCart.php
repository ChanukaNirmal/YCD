<?php
session_start();
include "connection.php";

$viewer_id = $_SESSION['Viewer_ID'] ?? null;
$recipe_id = $_POST['recipe_id'] ?? null;
$quantity = $_POST['quantity'] ?? 1;

if ($viewer_id && $recipe_id) {
    // Check if this recipe already in cart
    $stmt = $conn->prepare("SELECT Cart_ID FROM Cart WHERE Viewer_ID = ? AND Recipe_ID = ?");
    $stmt->bind_param("ii", $viewer_id, $recipe_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        // Not in cart, insert new
        $stmt->close();
        $insert = $conn->prepare("INSERT INTO Cart (Viewer_ID, Recipe_ID, Quantity) VALUES (?, ?, ?)");
        $insert->bind_param("iii", $viewer_id, $recipe_id, $quantity);
        $insert->execute();
        $insert->close();
    } else {
        // Already in cart: update quantity (optional)
        $stmt->close();
        $update = $conn->prepare("UPDATE Cart SET Quantity = Quantity + ? WHERE Viewer_ID = ? AND Recipe_ID = ?");
        $update->bind_param("iii", $quantity, $viewer_id, $recipe_id);
        $update->execute();
        $update->close();
    }
}

$conn->close();
header("Location: cart.php");
exit;