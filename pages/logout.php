<?php
session_start();
session_destroy();
header("Location: /workout_application/index.php");
exit;
?>
