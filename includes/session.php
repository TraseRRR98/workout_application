<?php
session_start();

if (!function_exists('check_login')) {
    function check_login() {
        if (!isset($_SESSION['userID'])) {
            header("Location: /workout_application/pages/login/login.php");
            exit;
        }
    }
}
?>