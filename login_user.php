<?php
session_start();
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Get redirect value (fallback to dashboard.php)
    $redirect = isset($_POST['redirect']) && !empty($_POST['redirect']) ? $_POST['redirect'] : "dashboard.php";

    // 🔒 Security: Prevent external redirects (only allow local files)
    $redirect = basename($redirect);

    // Prepare statement
    $stmt = $conn->prepare("SELECT userId, name, email, password FROM users WHERE email = ?");
    if (!$stmt) die("Prepare failed: " . $conn->error);

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['userId'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name'] = $user['name'];

            // ✅ Redirect to requested page
            header("Location: " . $redirect);
            exit();
        } else {
            $_SESSION['error'] = "Incorrect password.";
        }
    } else {
        $_SESSION['error'] = "User not found.";
    }

    // ❌ On error, keep redirect parameter
    header("Location: login.php" . (!empty($redirect) ? "?redirect=" . urlencode($redirect) : ""));
    exit();
} else {
    $_SESSION['error'] = "Invalid request.";
    header("Location: login.php");
    exit();
}
