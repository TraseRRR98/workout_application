<?php
include '../../includes/header.php';
include '../../includes/db_connect.php';
include '../../includes/accesibles.php';

function validate_captcha($captcha_response) {
    $secret_key = "6Lc59wYqAAAAAMDD1-qpZOByaWnb8tscGGvimNVm";
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret_key&response=$captcha_response");
    $response_keys = json_decode($response, true);
    return intval($response_keys["success"]);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $captcha_response = $_POST['g-recaptcha-response'];

    if ($password !== $confirm_password) {
        die("Passwords do not match.");
    }

    if (!validate_captcha($captcha_response)) {
        die("CAPTCHA validation failed.");
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        die("Username or Email already taken.");
    }

    $stmt->close();

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $verification_code = md5(uniqid(mt_rand(), true));

    $stmt = $conn->prepare("INSERT INTO users (username, email, password, verification_code) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $email, $hashed_password, $verification_code);

    if ($stmt->execute()) {
        $verification_link = "http://yourwebsite.com/verify.php?code=$verification_code";
        mail($email, "Verify your email", "Click this link to verify your email: $verification_link");

        echo "Registration successful! Please verify your email.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
