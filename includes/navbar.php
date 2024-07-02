<nav class="navbar navbar-expand-lg navbar-light bg-primary">
    <a class="navbar-brand text-white" href="/workout_application/index.php">Workout Tracker</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link text-white" href="/workout_application/index.php">Home</a>
            </li>
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
                <li class="nav-item">
                    <a class="nav-link text-white" href="/workout_application/pages/logout.php">Logout</a>
                </li>
            <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link text-white" href="/workout_application/pages/login/login.php">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="/workout_application/pages/register/register.php">Register</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>