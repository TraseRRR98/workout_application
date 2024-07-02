<?php
require_once '../../includes/session.php';
check_login();
require_once '../../includes/header.php';
require_once '../../includes/db_connect.php';
require_once '../../includes/accesibles.php';
include 'progressive_overload.php';  // Include the progressive overloading logic

function getWorkout($ID) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM workouts WHERE ID = ?");
    $stmt->bind_param("i", $ID);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function updateWorkout($ID, $planID, $exerciseID, $sets, $reps, $weight, $progressiveOverloadingStrategy) {
    global $conn;
    $stmt = $conn->prepare("UPDATE workouts SET Plan_ID = ?, Exercise_ID = ?, Sets = ?, Reps = ?, Weight = ?, Progressive_Overloading_Strategy = ? WHERE ID = ?");
    $stmt->bind_param("iiiidii", $planID, $exerciseID, $sets, $reps, $weight, $progressiveOverloadingStrategy, $ID);
    if ($stmt->execute()) {
        echo "Workout updated successfully.";
    } else {
        echo "Error updating workout: " . $conn->error;
    }
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['updateWorkout'])) {
    $ID = get_safe('ID');
    $planID = get_safe('planID');
    $exerciseID = get_safe('exerciseID');
    $sets = get_safe('sets');
    $reps = get_safe('reps');
    $weight = get_safe('weight');
    $progressiveOverloadingStrategy = get_safe('progressiveOverloadingStrategy');
    updateWorkout($ID, $planID, $exerciseID, $sets, $reps, $weight, $progressiveOverloadingStrategy);
    header('Location: workouts.php');
    exit;
}

$workout = getWorkout($_GET['ID']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Workout</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <br>
    <div class="container">
        <h1>Edit Workout</h1>
        <form action="editWorkout.php" method="post" class="form-inline mb-3">
            <input type="hidden" name="ID" value="<?php echo htmlspecialchars($workout['ID'], ENT_QUOTES, 'UTF-8'); ?>">
            <div class="form-group mb-2">
                <label for="planID" class="sr-only">Plan:</label>
                <select class="form-control w-100 mb-2 mb-md-0 mr-md-2" id="planID" name="planID" required>
                    <?php
                    $planQuery = "SELECT ID, Name FROM plans";
                    $planResult = $conn->query($planQuery);
                    while ($planRow = $planResult->fetch_assoc()) {
                        $selected = ($planRow['ID'] == $workout['Plan_ID']) ? 'selected' : '';
                        echo "<option value='" . htmlspecialchars($planRow['ID'], ENT_QUOTES, 'UTF-8') . "' $selected>" . htmlspecialchars($planRow['Name'], ENT_QUOTES, 'UTF-8') . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group mb-2">
                <label for="exerciseID" class="sr-only">Exercise:</label>
                <select class="form-control w-100 mb-2 mb-md-0 mr-md-2" id="exerciseID" name="exerciseID" required>
                    <?php
                    $exerciseQuery = "SELECT ID, Name FROM exercises";
                    $exerciseResult = $conn->query($exerciseQuery);
                    while ($exerciseRow = $exerciseResult->fetch_assoc()) {
                        $selected = ($exerciseRow['ID'] == $workout['Exercise_ID']) ? 'selected' : '';
                        echo "<option value='" . htmlspecialchars($exerciseRow['ID'], ENT_QUOTES, 'UTF-8') . "' $selected>" . htmlspecialchars($exerciseRow['Name'], ENT_QUOTES, 'UTF-8') . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group mb-2">
                <label for="sets" class="sr-only">Sets:</label>
                <input type="number" class="form-control w-100 mb-2 mb-md-0 mr-md-2" id="sets" name="sets" placeholder="Sets" value="<?php echo htmlspecialchars($workout['Sets'], ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>
            <div class="form-group mb-2">
                <label for="reps" class="sr-only">Reps:</label>
                <input type="number" class="form-control w-100 mb-2 mb-md-0 mr-md-2" id="reps" name="reps" placeholder="Reps" value="<?php echo htmlspecialchars($workout['Reps'], ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>
            <div class="form-group mb-2">
                <label for="weight" class="sr-only">Weight:</label>
                <input type="number" step="0.01" class="form-control w-100 mb-2 mb-md-0 mr-md-2" id="weight" name="weight" placeholder="Weight" value="<?php echo htmlspecialchars($workout['Weight'], ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>
            <div class="form-group mb-2">
                <label for="progressiveOverloadingStrategy" class="sr-only">Strategy:</label>
                <select class="form-control w-100 mb-2 mb-md-0 mr-md-2" id="progressiveOverloadingStrategy" name="progressiveOverloadingStrategy" required>
                    <option value="1" <?php echo $workout['Progressive_Overloading_Strategy'] == 1 ? 'selected' : ''; ?>>Percentage Increase</option>
                    <option value="2" <?php echo $workout['Progressive_Overloading_Strategy'] == 2 ? 'selected' : ''; ?>>Fixed Increase</option>
                    <option value="3" <?php echo $workout['Progressive_Overloading_Strategy'] == 3 ? 'selected' : ''; ?>>Reps Increase</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary mb-2" name="updateWorkout">Update Workout</button>
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
