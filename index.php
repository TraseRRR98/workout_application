<?php
require_once 'includes/session.php';

// Check if the user is logged in
if (isset($_SESSION['userID'])) {
    $loggedIn = true;
} else {
    $loggedIn = false;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workout Tracker</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>

<?php require_once 'includes/navbar.php'; ?>

<div class="container">
    <div class="jumbotron mt-4">
        <h1 class="display-4">Welcome to Workout Tracker</h1>
        <p class="lead">Track your workouts and progress with ease!</p>
        <hr class="my-4">
        <p>Get started by logging your exercises and creating workout plans.</p>
        <?php if (!$loggedIn): ?>
            <a class="btn btn-primary btn-lg" href="pages/login/login.php" role="button">Login</a>
            <a class="btn btn-secondary btn-lg" href="pages/register/register.php" role="button">Register</a>
        <?php else: ?>
            <a class="btn btn-primary btn-lg" href="pages/workouts/workouts.php" role="button">Go to Workouts</a>
        <?php endif; ?>
    </div>
</div>

<?php
require_once 'includes/footer.php';
?>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>