<?php
require 'db.php';

if (isset($_GET['id'])) {
    $stmt = $conn->prepare("DELETE FROM tasks WHERE id = :id");
    $stmt->execute(['id' => $_GET['id']]);
}

header("Location: index.php");
exit;