<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in and get the username
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
?>

<nav class="navbar navbar-expand-lg navbar-light bg-primary">
    <a class="navbar-brand text-white" href="/workout_application/index.php">Workout Tracker</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link text-white" href="/workout_application/index.php">Home</a>
            </li>
            <?php if (!isset($_SESSION['userID'])): ?>
                <li class="nav-item">
                    <a class="nav-link text-white" href="/workout_application/pages/login/login.php">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="/workout_application/pages/register/register.php">Register</a>
                </li>
            <?php endif; ?>
            <?php if (isset($_SESSION['userID'])): ?>
                <li class="nav-item">
                    <a class="nav-link text-white" href="/workout_application/pages/exercises/exercises.php">Exercises</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="/workout_application/pages/plans/plans.php">Plans</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="/workout_application/pages/workouts/workouts.php">Workouts</a>
                </li>
            <?php endif; ?>
        </ul>
        <span class="navbar-text text-white">
            Hello, <?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>
        </span>
        <?php if (isset($_SESSION['userID'])): ?>
            <a href="/workout_application/pages/logout.php" class="btn btn-outline-light ml-3">Logout</a>
        <?php endif; ?>
    </div>
</nav>