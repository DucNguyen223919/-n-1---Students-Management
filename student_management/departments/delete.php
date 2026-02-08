<?php
require "../config/db.php";

if (isset($_GET['id'])) {
    $conn->prepare("DELETE FROM departments WHERE id = ?")
         ->execute([$_GET['id']]);
}

header("Location: index.php");
exit;
