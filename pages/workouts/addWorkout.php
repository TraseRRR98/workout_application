<?php
require_once '../../includes/session.php';
check_login();
require_once '../../includes/header.php';
require_once '../../includes/db_connect.php';
require_once '../../includes/accesibles.php';

function displayPlanExercises($planID, $userID) {
    global $conn;
    $sql = "SELECT w.ID, e.Name as Exercise_Name, w.Sets, w.Reps, w.Weight, w.Progressive_Overloading_Strategy 
            FROM workouts w 
            JOIN exercises e ON w.Exercise_ID = e.ID 
            WHERE w.Plan_ID = ? AND w.User_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $planID, $userID);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result) {
        die('Could not get data: ' . mysqli_error($conn));
    }

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['Exercise_Name'], ENT_QUOTES, 'UTF-8') . "</td>
                    <td>" . htmlspecialchars($row['Sets'], ENT_QUOTES, 'UTF-8') . "</td>
                    <td>" . htmlspecialchars($row['Reps'], ENT_QUOTES, 'UTF-8') . "</td>
                    <td>" . htmlspecialchars($row['Weight'], ENT_QUOTES, 'UTF-8') . "</td>
                    <td>" . htmlspecialchars($row['Progressive_Overloading_Strategy'], ENT_QUOTES, 'UTF-8') . "</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='5'>No exercises found for this plan</td></tr>";
    }
}

function addWorkout($planID, $exerciseID, $sets, $reps, $weight, $progressiveOverloadingStrategy, $userID) {
    global $conn;

    $stmt = $conn->prepare("INSERT INTO workouts (Plan_ID, Exercise_ID, Sets, Reps, Initial_Reps, Weight, Progressive_Overloading_Strategy, User_ID) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiidiii", $planID, $exerciseID, $sets, $reps, $reps, $weight, $progressiveOverloadingStrategy, $userID);

    if ($stmt->execute()) {
        echo "Workout added successfully.";
    } else {
        echo "Error adding workout: " . $conn->error;
    }

    $stmt->close();
}

$selectedPlanID = isset($_GET['planID']) ? get_safe('planID') : null;
$userID = $_SESSION['userID'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addWorkout'])) {
    $planID = get_safe('planID');
    $exerciseID = get_safe('exerciseID');
    $sets = get_safe('sets');
    $reps = get_safe('reps');
    $weight = get_safe('weight');
    $progressiveOverloadingStrategy = get_safe('progressiveOverloadingStrategy');
    addWorkout($planID, $exerciseID, $sets, $reps, $weight, $progressiveOverloadingStrategy, $userID);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Workout</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script>
        function handlePlanChange() {
            const planID = document.getElementById('planID').value;
            window.location.href = `addWorkout.php?planID=${planID}`;
        }
    </script>
</head>
<body>
    <br>
    <div class="container">
        <h1>Add Workout</h1>
        <form action="addWorkout.php" method="post" class="form-inline mb-3">
            <div class="form-group mb-2">
                <label for="planID" class="sr-only">Plan:</label>
                <select class="form-control w-100 mb-2 mb-md-0 mr-md-2" id="planID" name="planID" onchange="handlePlanChange()" required>
                    <option value="">Select Plan</option>
                    <?php
                    $planQuery = "SELECT ID, Name FROM plans WHERE User_ID = ?";
                    $stmt = $conn->prepare($planQuery);
                    $stmt->bind_param("i", $userID);
                    $stmt->execute();
                    $planResult = $stmt->get_result();
                    while ($planRow = $planResult->fetch_assoc()) {
                        $selected = ($planRow['ID'] == $selectedPlanID) ? 'selected' : '';
                        echo "<option value='" . htmlspecialchars($planRow['ID'], ENT_QUOTES, 'UTF-8') . "' $selected>" . htmlspecialchars($planRow['Name'], ENT_QUOTES, 'UTF-8') . "</option>";
                    }
                    ?>
                </select>
            </div>
            <?php if ($selectedPlanID): ?>
            <div class="form-group mb-2">
                <label for="exerciseID" class="sr-only">Exercise:</label>
                <select class="form-control w-100 mb-2 mb-md-0 mr-md-2" id="exerciseID" name="exerciseID" required>
                    <?php
                    $exerciseQuery = "SELECT ID, Name FROM exercises";
                    $exerciseResult = $conn->query($exerciseQuery);
                    while ($exerciseRow = $exerciseResult->fetch_assoc()) {
                        echo "<option value='" . htmlspecialchars($exerciseRow['ID'], ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($exerciseRow['Name'], ENT_QUOTES, 'UTF-8') . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group mb-2">
                <label for="sets" class="sr-only">Sets:</label>
                <input type="number" class="form-control w-100 mb-2 mb-md-0 mr-md-2" id="sets" name="sets" placeholder="Sets" required>
            </div>
            <div class="form-group mb-2">
                <label for="reps" class="sr-only">Reps:</label>
                <input type="number" class="form-control w-100 mb-2 mb-md-0 mr-md-2" id="reps" name="reps" placeholder="Reps" required>
            </div>
            <div class="form-group mb-2">
                <label for="weight" class="sr-only">Weight:</label>
                <input type="number" step="0.01" class="form-control w-100 mb-2 mb-md-0 mr-md-2" id="weight" name="weight" placeholder="Weight" required>
            </div>
            <div class="form-group mb-2">
                <label for="progressiveOverloadingStrategy" class="sr-only">Strategy:</label>
                <select class="form-control w-100 mb-2 mb-md-0 mr-md-2" id="progressiveOverloadingStrategy" name="progressiveOverloadingStrategy" required>
                    <option value="1">Percentage Increase</option>
                    <option value="2">Fixed Increase</option>
                    <option value="3">Reps Increase</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary mb-2" name="addWorkout">Add Workout</button>
            <?php endif; ?>
        </form>
        <?php if ($selectedPlanID): ?>
        <h2>Existing Exercises for Selected Plan</h2>
        <div class="table-container">
            <table id="planExercisesTable" class="table table-striped">
                <thead>
                    <tr>
                        <th>Exercise</th>
                        <th>Sets</th>
                        <th>Reps</th>
                        <th>Weight</th>
                        <th>Strategy</th>
                    </tr>
                </thead>
                <tbody id="planExercisesTableBody">
                    <?php displayPlanExercises($selectedPlanID, $userID); ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
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