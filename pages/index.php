<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../templates/common.tpl.php');
  require_once(__DIR__ . '/../utils/session.php');


if (!is_logged_in()) {
    header('Location: ../pages/login.php');
    exit();
}


draw_header("Welcome");
draw_footer();

?>