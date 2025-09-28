<?php
require_once __DIR__ . '/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /house_rental/admin/login.php');
    exit();
}

$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';

if ($email === 'admin@gmail.com' && $password === '123') {
    $_SESSION['admin_email'] = 'admin@gmail.com';
    header('Location: /house_rental/admin/dashboard.php');
    exit();
}

header('Location: /house_rental/admin/login.php?error=Invalid+credentials');
exit();
?>