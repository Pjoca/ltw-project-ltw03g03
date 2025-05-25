<?php
require_once(__DIR__ . '/../database/connection.db.php');

function getAllCategories(): array {
    $db = getDatabaseConnection();
    $stmt = $db->query("SELECT * FROM Categories ORDER BY name ASC");
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}
