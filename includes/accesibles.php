<?php
function is_set($key) {
    return isset($_GET[$key]) || isset($_POST[$key]);
}

function get($key) {
    return isset($_POST[$key]) ? $_POST[$key] : $_GET[$key];
}

function get_safe($key) {
    global $conn;
    return mysqli_real_escape_string($conn, get($key));
}
?>
