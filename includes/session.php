<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function check_login() {
    if (!isset($_SESSION['userID'])) {
        header('Location: /workout_application/pages/login/login.php');
        exit;
    }
}
?>