<?php
include '../../includes/header.php';
include '../../includes/db_connect.php';
include '../../includes/accesibles.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = get_safe('username');
    $email = get_safe('email');
    $password = get_safe('password');
    $confirm_password = get_safe('confirm_password');

    if ($password !== $confirm_password) {
        echo "Passwords do not match.";
        exit;
    }

    // Check if the username already exists
    $stmt = $conn->prepare("SELECT ID FROM users WHERE Username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        echo "Username already taken. Please choose a different username.";
        $stmt->close();
        exit;
    }
    $stmt->close();

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO users (Username, Email, Password, Join_Date) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    if ($stmt->execute()) {
        echo "Registration successful.";
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
