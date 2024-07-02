<?php
session_start();
session_destroy();
header("Location: /workout_application/pages/login/login.php");
exit;
?>
