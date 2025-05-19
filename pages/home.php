<?php
declare(strict_types = 1);
session_start();

require_once(__DIR__ . '/../templates/common.tpl.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

draw_header('Home Page');
draw_home();  
draw_footer();
?>
