<?php
declare(strict_types = 1);
session_start();

require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../templates/services.tpl.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

draw_header('My Services');
draw_my_services_page();
draw_footer();
