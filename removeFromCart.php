<?php
session_start();
include "connection.php";

$viewer_id = $_SESSION['Viewer_ID'] ?? null;
$cart_id = $_POST['cart_id'] ?? null;

if ($viewer_id && $cart_id) {
    $stmt = $conn->prepare("DELETE FROM Cart WHERE Cart_ID = ? AND Viewer_ID = ?");
    $stmt->bind_param("ii", $cart_id, $viewer_id);
    $stmt->execute();
    $stmt->close();
}

$conn->close();
header("Location: cart.php");
exit;