<?php
session_start();

function is_logged_in(): bool {
    return isset($_SESSION['user_id']);
}

function require_login(): void {
    if (!is_logged_in()) {
        header('Location: ../pages/login.php');
        exit();
    }
}
