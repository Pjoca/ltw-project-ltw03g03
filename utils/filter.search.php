<?php
require_once(__DIR__ . '/../database/connection.db.php');

function getFilteredServices(string $query = '', string $category = '', ?float $price = null, ?int $delivery = null): array {
    $db = getDatabaseConnection();

    $sql = "
        SELECT Services.*, Categories.name AS category_name, Users.name AS provider_name
        FROM Services
        JOIN Categories ON Services.category_id = Categories.id
        JOIN Users ON Services.user_id = Users.id
        WHERE 1 = 1
    ";
    $params = [];

    if ($query !== '') {
        $sql .= " AND (Services.title LIKE :query OR Services.description LIKE :query OR Users.name LIKE :query)";
        $params[':query'] = '%' . $query . '%';
    }

    if ($category !== '') {
        $sql .= " AND Categories.name = :category";
        $params[':category'] = $category;
    }

    if ($price !== null) {
        $sql .= " AND Services.price <= :price";
        $params[':price'] = $price;
    }

    if ($delivery !== null) {
        $sql .= " AND Services.delivery_time <= :delivery";
        $params[':delivery'] = $delivery;
    }

    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}
