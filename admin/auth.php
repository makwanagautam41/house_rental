<?php
session_start();

function is_admin_logged_in(): bool {
    return isset($_SESSION['admin_email']) && $_SESSION['admin_email'] === 'admin@gmail.com';
}

function require_admin(string $redirect = '/house_rental/admin/login.php') {
    if (!is_admin_logged_in()) {
        header('Location: ' . $redirect);
        exit();
    }
}

function admin_logout_and_redirect(string $redirect = '/house_rental/admin/login.php') {
    unset($_SESSION['admin_email']);
    session_destroy();
    header('Location: ' . $redirect);
    exit();
}
?>