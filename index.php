<?php
include 'includes/session.php';
check_login();
?>
<?php
include 'includes/db_connect.php';
include 'includes/header.php';
?>

<div class="container">
    <div class="jumbotron mt-4">
        <h1 class="display-4">Welcome to Workout Tracker</h1>
        <p class="lead">Track your workouts and progress with ease!</p>
        <hr class="my-4">
        <p>Get started by logging your exercises and creating workout plans.</p>
    </div>
</div>

<?php
include 'includes/footer.php';
?>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>