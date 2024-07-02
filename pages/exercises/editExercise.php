<?php
include '../../includes/header.php';
include '../../includes/db_connect.php';

function getExercise($ID) {
    global $conn;
    $stmt = $conn->prepare("SELECT Name, Muscle_Group FROM exercises WHERE ID = ?");
    $stmt->bind_param("i", $ID);
    $stmt->execute();
    $stmt->bind_result($name, $muscleGroup);
    $stmt->fetch();
    $stmt->close();
    return ['name' => $name, 'muscleGroup' => $muscleGroup];
}

function updateExercise($ID, $name, $muscleGroup) {
    global $conn;
    $stmt = $conn->prepare("UPDATE exercises SET Name = ?, Muscle_Group = ? WHERE ID = ?");
    $stmt->bind_param("ssi", $name, $muscleGroup, $ID);
    
    if ($stmt->execute()) {
        echo "Exercise updated successfully.";
    } else {
        echo "Error updating exercise: " . $conn->error;
    }
    
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['updateExercise'])) {
    updateExercise($_POST['ID'], $_POST['name'], $_POST['muscleGroup']);
    header('Location: exercises.php');
    exit;
}

$exercise = getExercise($_GET['ID']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Exercise</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="display-background">
    <?php include '../../includes/header.php'; ?>
    <br>
    <div class="container">
        <h1>Edit Exercise</h1>
        <form action="editExercise.php" method="post">
            <input type="hidden" name="ID" value="<?php echo $_GET['ID']; ?>">
            <div class="form-group">
                <label for="name">Exercise Name:</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $exercise['name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="muscleGroup">Muscle Group:</label>
                <input type="text" class="form-control" id="muscleGroup" name="muscleGroup" value="<?php echo $exercise['muscleGroup']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary" name="updateExercise">Update Exercise</button>
        </form>
    </div>
<footer class="footer">
    <div class="footer-content">
        <p>&copy; 2024 Workout Tracker. All rights reserved.</p>
    </div>
</footer>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
