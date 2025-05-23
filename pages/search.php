<?php
declare(strict_types = 1);
session_start();

require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../templates/services.tpl.php');
require_once(__DIR__ . '/../utils/filter.search.php');
require_once(__DIR__ . '/../utils/categories.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$selectedQuery = $_GET['query'] ?? '';
$selectedCategory = $_GET['category'] ?? '';
$selectedPrice = isset($_GET['price']) ? floatval($_GET['price']) : null;
$selectedDelivery = isset($_GET['delivery']) ? intval($_GET['delivery']) : null;

$categories = getAllCategories();
$services = getFilteredServices($selectedQuery, $selectedCategory, $selectedPrice, $selectedDelivery);

draw_header('Search');
draw_search($selectedQuery, $selectedCategory, $selectedPrice, $selectedDelivery, $categories, $services);
draw_footer();
?>
