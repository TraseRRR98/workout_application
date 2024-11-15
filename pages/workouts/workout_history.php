<?php
require_once '../../includes/session.php';
check_login();
require_once '../../includes/header.php';
require_once '../../includes/db_connect.php';
require_once '../../includes/accesibles.php';

$userID = $_SESSION['userID'];

function getWorkoutDetails($workoutID) {
    global $conn;
    $sql = "SELECT p.Name as Plan_Name, e.Name as Exercise_Name
            FROM workouts w
            JOIN plans p ON w.Plan_ID = p.ID
            JOIN exercises e ON w.Exercise_ID = e.ID
            WHERE w.ID = ? AND w.User_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $workoutID, $userID);
    $stmt->execute();
    $stmt->bind_result($planName, $exerciseName);
    $stmt->fetch();
    $stmt->close();
    return ['planName' => $planName, 'exerciseName' => $exerciseName];
}

function displayWorkoutHistory($workoutID, $userID) {
    global $conn;
    $sql = "SELECT ws.ID, ws.Date, ws.Weight, ws.Reps, ws.Sets, ws.Notes, p.Name as Plan_Name, e.Name as Exercise_Name 
            FROM workout_sessions ws
            JOIN workouts w ON ws.Workout_ID = w.ID
            JOIN plans p ON w.Plan_ID = p.ID
            JOIN exercises e ON w.Exercise_ID = e.ID
            WHERE ws.Workout_ID = ? AND w.User_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $workoutID, $userID);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result) {
        die('Could not get data: ' . mysqli_error($conn));
    }

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td data-label='Plan'>" . htmlspecialchars($row['Plan_Name'], ENT_QUOTES, 'UTF-8') . "</td>
                    <td data-label='Exercise'>" . htmlspecialchars($row['Exercise_Name'], ENT_QUOTES, 'UTF-8') . "</td>
                    <td data-label='Date'>" . htmlspecialchars($row['Date'], ENT_QUOTES, 'UTF-8') . "</td>
                    <td data-label='Weight'>" . htmlspecialchars($row['Weight'], ENT_QUOTES, 'UTF-8') . "</td>
                    <td data-label='Reps'>" . htmlspecialchars($row['Reps'], ENT_QUOTES, 'UTF-8') . "</td>
                    <td data-label='Sets'>" . htmlspecialchars($row['Sets'], ENT_QUOTES, 'UTF-8') . "</td>
                    <td data-label='Notes'>" . htmlspecialchars($row['Notes'], ENT_QUOTES, 'UTF-8') . "</td>
                    <td data-label='Actions' class='sessionTableActions'>
                        <a href='?deleteHistoryID=" . htmlspecialchars($row['ID'], ENT_QUOTES, 'UTF-8') . "&workoutID=" . htmlspecialchars($workoutID, ENT_QUOTES, 'UTF-8') . "' class='button button-delete btn btn-sm btn-danger' onclick='return confirm(\"Are you sure you want to delete this session?\");'><i class='fas fa-trash-alt'></i> Delete</a>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='8'>No results found</td></tr>";
    }
}

function deleteHistoryEntry($historyID, $userID) {
    global $conn;

    $stmt = $conn->prepare("DELETE FROM workout_sessions WHERE ID = ? AND User_ID = ?");
    $stmt->bind_param("ii", $historyID, $userID);

    if ($stmt->execute()) {
        echo "History entry deleted successfully.";
    } else {
        echo "Error deleting history entry: " . $conn->error;
    }

    $stmt->close();
}

$workoutID = isset($_GET['workoutID']) ? get_safe('workoutID') : null;
$historyID = isset($_GET['deleteHistoryID']) ? get_safe('deleteHistoryID') : null;

if ($historyID) {
    deleteHistoryEntry($historyID, $userID);
}

$workoutDetails = $workoutID ? getWorkoutDetails($workoutID) : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workout History</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="/workout_application/css/styles.css">
</head>
<body>
    <br>
    <div class="container">
        <h1>Workout History</h1>
        <?php if ($workoutDetails): ?>
            <h2>Plan: <?php echo htmlspecialchars($workoutDetails['planName'], ENT_QUOTES, 'UTF-8'); ?></h2>
            <h3>Exercise: <?php echo htmlspecialchars($workoutDetails['exerciseName'], ENT_QUOTES, 'UTF-8'); ?></h3>
        <?php else: ?>
            <p>Invalid workout ID</p>
        <?php endif; ?>
        <div class="table-container">
            <table id="workoutHistoryTable" class="table table-striped">
                <thead>
                    <tr>
                        <th>Plan</th>
                        <th>Exercise</th>
                        <th>Date</th>
                        <th>Weight</th>
                        <th>Reps</th>
                        <th>Sets</th>
                        <th>Notes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="workoutHistoryTableBody">
                    <?php
                    if ($workoutID) {
                        displayWorkoutHistory($workoutID, $userID);
                    } else {
                        echo "<tr><td colspan='8'>Invalid workout ID</td></tr>";
                    }
                    ?>
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
