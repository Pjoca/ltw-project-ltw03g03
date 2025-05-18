<?php
declare(strict_types = 1);
session_start();

require_once(__DIR__ . '/../templates/common.tpl.php');

draw_header('Create Service');
draw_create_service();
draw_footer();