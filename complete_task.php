<?php
require 'db.php';

if (isset($_GET['id'])) {
    $stmt = $conn->prepare("UPDATE tasks SET is_completed = 1 WHERE id = :id");
    $stmt->execute(['id' => $_GET['id']]);
}

header("Location: index.php");
exit;