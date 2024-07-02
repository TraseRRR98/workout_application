<?php
session_start();
include '../../includes/db_connect.php';
include '../../includes/accesibles.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = get_safe('username');
    $password = get_safe('password');

    // Prepare and bind
    $stmt = $conn->prepare("SELECT ID, Password FROM users WHERE Username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($userID, $hashed_password);
        $stmt->fetch();
        
        // Verify the password
        if (password_verify($password, $hashed_password)) {
            $_SESSION['userID'] = $userID;
            $_SESSION['username'] = $username;
            header("Location: ../../index.php");
            exit;
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "Invalid username.";
    }
    $stmt->close();
    $conn->close();
}
?>
