<?php
require_once '../../includes/session.php';
check_login();
require_once '../../includes/header.php';
require_once '../../includes/db_connect.php';
require_once '../../includes/accesibles.php';
require_once '../../pages/workouts/progressive_overload.php';  // Include the progressive overloading logic

$strategies = [
    1 => 'Percentage Increase',
    2 => 'Fixed Increase',
    3 => 'Reps Increase'
];

$userID = $_SESSION['userID'];

if (isset($_GET['applyOverload'])) {
    $workoutID = get_safe('workoutID');
    echo "Applying overload to workout ID: $workoutID"; // Debugging output
    applyProgressiveOverload($workoutID);

    // Fetch the updated workout details
    $sql = "SELECT * FROM workouts WHERE ID = ? AND User_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $workoutID, $userID);
    $stmt->execute();
    $updatedWorkout = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    // Insert a new record into the workout_sessions table to keep track of the history
    $stmt = $conn->prepare("INSERT INTO workout_sessions (Workout_ID, Date, Weight, Reps, Sets, Notes, User_ID) VALUES (?, NOW(), ?, ?, ?, 'Applied overload', ?)");
    $stmt->bind_param("idiii", $workoutID, $updatedWorkout['Weight'], $updatedWorkout['Reps'], $updatedWorkout['Sets'], $userID);
    if ($stmt->execute()) {
        echo "Workout session recorded successfully.";
    } else {
        echo "Error recording workout session: " . $conn->error;
    }
    $stmt->close();
}

$selectedUnit = isset($_GET['unit']) ? get_safe('unit') : 'lbs'; // Default to lbs if no unit is selected

function displayWorkouts($userID, $planID = null, $unit = 'lbs', $strategies) {
    global $conn;
    $sql = "SELECT w.ID, p.Name as Plan_Name, e.Name as Exercise_Name, w.Sets, w.Reps, w.Weight, w.Progressive_Overloading_Strategy 
            FROM workouts w 
            JOIN plans p ON w.Plan_ID = p.ID 
            JOIN exercises e ON w.Exercise_ID = e.ID
            WHERE w.User_ID = ?";
    
    if ($planID !== null) {
        $sql .= " AND w.Plan_ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $userID, $planID);
    } else {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userID);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result) {
        die('Could not get data: ' . mysqli_error($conn));
    }

    $conversionFactor = ($unit == 'kg') ? 0.453592 : 1; // Conversion factor from lbs to kg

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $weight = $row['Weight'] * $conversionFactor;
            echo "<tr>
                    <td data-label='ID'>" . htmlspecialchars($row['ID'], ENT_QUOTES, 'UTF-8') . "</td>
                    <td data-label='Plan'>" . htmlspecialchars($row['Plan_Name'], ENT_QUOTES, 'UTF-8') . "</td>
                    <td data-label='Exercise'>" . htmlspecialchars($row['Exercise_Name'], ENT_QUOTES, 'UTF-8') . "</td>
                    <td data-label='Sets'>" . htmlspecialchars($row['Sets'], ENT_QUOTES, 'UTF-8') . "</td>
                    <td data-label='Reps'>" . htmlspecialchars($row['Reps'], ENT_QUOTES, 'UTF-8') . "</td>
                    <td data-label='Weight'>" . htmlspecialchars(number_format($weight, 2), ENT_QUOTES, 'UTF-8') . " " . htmlspecialchars($unit, ENT_QUOTES, 'UTF-8') . "</td>
                    <td data-label='Strategy'>" . htmlspecialchars($strategies[$row['Progressive_Overloading_Strategy']], ENT_QUOTES, 'UTF-8') . "</td>
                    <td data-label='Actions' class='workoutTableActions'>
                        <a href='editWorkout.php?ID=" . htmlspecialchars($row['ID'], ENT_QUOTES, 'UTF-8') . "' class='button button-edit btn btn-sm btn-primary'><i class='fas fa-edit'></i>Edit</a>
                        <a href='?deleteID=" . htmlspecialchars($row['ID'], ENT_QUOTES, 'UTF-8') . "&unit=" . htmlspecialchars($unit, ENT_QUOTES, 'UTF-8') . "' class='button button-delete btn btn-sm btn-danger' onclick='return confirm(\"Are you sure you want to delete this workout?\");'><i class='fas fa-trash-alt'></i>Delete</a>
                        <a href='?applyOverload=true&workoutID=" . htmlspecialchars($row['ID'], ENT_QUOTES, 'UTF-8') . "&unit=" . htmlspecialchars($unit, ENT_QUOTES, 'UTF-8') . "' class='button button-apply btn btn-sm btn-success'><i class='fas fa-sync-alt'></i> Apply Overload</a>
                        <a href='workout_history.php?workoutID=" . htmlspecialchars($row['ID'], ENT_QUOTES, 'UTF-8') . "' class='button button-history btn btn-sm btn-info'><i class='fas fa-history'></i> History</a>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='8'>No results found</td></tr>";
    }
}

function deleteWorkout($ID) {
    global $conn;

    // Delete related workout sessions
    $stmt = $conn->prepare("DELETE FROM workout_sessions WHERE Workout_ID = ?");
    $stmt->bind_param("i", $ID);
    if (!$stmt->execute()) {
        echo "Error deleting workout sessions: " . $conn->error;
        return;
    }
    $stmt->close();

    // Delete workout
    $stmt = $conn->prepare("DELETE FROM workouts WHERE ID = ? AND User_ID = ?");
    $stmt->bind_param("ii", $ID, $_SESSION['userID']);

    if ($stmt->execute()) {
        echo "Workout deleted successfully.";
    } else {
        echo "Error deleting workout: " . $conn->error;
    }

    $stmt->close();
}

if (isset($_GET['deleteID'])) {
    $ID = get_safe('deleteID');
    deleteWorkout($ID);
}

$selectedPlanID = isset($_GET['planID']) ? get_safe('planID') : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workouts</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="/workout_application/css/styles.css">
    <script>
        function handleUnitChange() {
            const unit = document.getElementById('unit').value;
            const planID = document.getElementById('planID').value;
            window.location.href = `workouts.php?unit=${unit}&planID=${planID}`;
        }
        function handlePlanChange() {
            const planID = document.getElementById('planID').value;
            const unit = document.getElementById('unit').value;
            window.location.href = `workouts.php?planID=${planID}&unit=${unit}`;
        }
    </script>
</head>
<body>
    <br>
    <div class="container">
        <h1>Workouts</h1>
        <div class="row mb-3">
            <div class="col-12">
                <a href="addWorkout.php" class="btn btn-primary">Add New Workout</a>
            </div>
        </div>
        <form action="workouts.php" method="get" class="form-inline mb-3">
            <div class="form-group mr-2">
                <label for="planID" class="sr-only">Select Plan:</label>
                <select class="form-control" id="planID" name="planID" onchange="handlePlanChange()">
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
                    $stmt->close();
                    ?>
                </select>
            </div>
            <div class="form-group mr-2">
                <label for="unit" class="sr-only">Select Unit:</label>
                <select class="form-control" id="unit" name="unit" onchange="handleUnitChange()">
                    <option value="lbs" <?php echo $selectedUnit == 'lbs' ? 'selected' : ''; ?>>lbs</option>
                    <option value="kg" <?php echo $selectedUnit == 'kg' ? 'selected' : ''; ?>>kg</option>
                </select>
            </div>
        </form>
        <div class="table-container">
            <table id="workoutTable" class="table table-striped table-responsive">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Plan</th>
                        <th>Exercise</th>
                        <th>Sets</th>
                        <th>Reps</th>
                        <th>Weight</th>
                        <th>Strategy</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="workoutTableBody">
                    <?php displayWorkouts($userID, $selectedPlanID, $selectedUnit, $strategies); ?>
                </tbody>
            </table>
        </div>
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
