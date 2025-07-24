<?php
include "connection.php";

$type = $_GET['type'] ?? '';
$id = $_GET['id'] ?? '';

$tableMap = [
  'creator' => 'creatorRegister',
  'viewer' => 'viewerRegister',
  'rider' => 'riderRegister'
];

if (isset($tableMap[$type]) && is_numeric($id)) {
  $table = $tableMap[$type];
  $stmt = $conn->prepare("DELETE FROM $table WHERE {$type}_ID = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $stmt->close();
}

$conn->close();
header("Location: admin.php");
exit;
