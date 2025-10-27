<?php
session_start();
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = trim($_POST['fullname']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];

    if ($password !== $confirmPassword) {
        $_SESSION['error'] = "Passwords do not match.";
        header("Location: register.php");
        exit();
    }

    // Check if email exists
    $stmt = $conn->prepare("SELECT userId FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $_SESSION['error'] = "Email already exists.";
        header("Location: register.php");
        exit();
    }
    $stmt->close();

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Handle avatar upload
    $avatarPath = null;
    if (!empty($_FILES['avatar']['name'])) {
        $uploadDir = "uploads/user_avatars/";
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $fileTmp = $_FILES['avatar']['tmp_name'];
        $fileName = uniqid() . "_" . $_FILES['avatar']['name'];
        $targetFile = $uploadDir . $fileName;

        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        if (in_array($_FILES['avatar']['type'], $allowedTypes) && move_uploaded_file($fileTmp, $targetFile)) {
            $avatarPath = $targetFile;
        }
    }

    // Insert user
    $insert = $conn->prepare("INSERT INTO users (name, email, password, phone, avatar) VALUES (?, ?, ?, ?, ?)");
    $insert->bind_param("sssss", $fullname, $email, $hashedPassword, $phone, $avatarPath);

    if ($insert->execute()) {
        // ✅ Send welcome email using Node.js SMTP service
        $apiUrl = "https://smtp-service-server.vercel.app/api/email/send";
        $apiKey = "b27ed2452e19defad91535d864ad0630735afd42f6b5c068819e648b21b441c5"; // your key

        $postData = [
            "to" => $email,
            "subject" => "🎉 Welcome to HomeHaven!",
            "html" => "<h2>Hello, $fullname!</h2><p>Welcome to <b>HomeHaven</b>! We're thrilled to have you with us.</p>"
        ];

        $ch = curl_init($apiUrl);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "x-api-key: $apiKey"
            ],
            CURLOPT_POSTFIELDS => json_encode($postData),
            CURLOPT_RETURNTRANSFER => true
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $emailData = json_decode($response, true);

        if ($httpCode === 201 || $httpCode === 200) {
            $_SESSION['success'] = "Registration successful! Welcome email queued.";
            $_SESSION['emailId'] = $emailData['id'] ?? null;
        } else {
            $_SESSION['error'] = "User registered, but failed to queue welcome email.";
        }

        header("Location: register.php"); 
        exit();
    } else {
        $_SESSION['error'] = "Something went wrong: " . $insert->error;
        header("Location: register.php");
        exit();
    }
}
?>
