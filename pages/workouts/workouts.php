<?php
include '../../includes/header.php';
include '../../includes/db_connect.php';
include '../../includes/accesibles.php';
///////////////////////////////////////////////////////////////////////////////////////
function displayWorkouts() {
    global $conn;
    $sql = "SELECT w.ID, p.Name as Plan_Name, e.Name as Exercise_Name, w.Sets, w.Reps, w.Weight, w.Progressive_Overloading_Strategy 
            FROM workouts w 
            JOIN plans p ON w.Plan_ID = p.ID 
            JOIN exercises e ON w.Exercise_ID = e.ID";
    $result = $conn->query($sql);

    if (!$result) {
        die('Could not get data: ' . mysqli_error($conn));
    }

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['ID'], ENT_QUOTES, 'UTF-8') . "</td>
                    <td>" . htmlspecialchars($row['Plan_Name'], ENT_QUOTES, 'UTF-8') . "</td>
                    <td>" . htmlspecialchars($row['Exercise_Name'], ENT_QUOTES, 'UTF-8') . "</td>
                    <td>" . htmlspecialchars($row['Sets'], ENT_QUOTES, 'UTF-8') . "</td>
                    <td>" . htmlspecialchars($row['Reps'], ENT_QUOTES, 'UTF-8') . "</td>
                    <td>" . htmlspecialchars($row['Weight'], ENT_QUOTES, 'UTF-8') . "</td>
                    <td>" . htmlspecialchars($row['Progressive_Overloading_Strategy'], ENT_QUOTES, 'UTF-8') . "</td>
                    <td class='workoutTableActions'>
                        <a href='editWorkout.php?ID=" . htmlspecialchars($row['ID'], ENT_QUOTES, 'UTF-8') . "' class='button button-edit'><i class='fas fa-edit'></i>Edit</a>
                        <a href='?deleteID=" . htmlspecialchars($row['ID'], ENT_QUOTES, 'UTF-8') . "' class='button button-delete' onclick='return confirm(\"Are you sure you want to delete this workout?\");'><i class='fas fa-trash-alt'></i>Delete</a>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='8'>No results found</td></tr>";
    }
}

function deleteWorkout($ID) {
    global $conn;

    $stmt = $conn->prepare("DELETE FROM workouts WHERE ID = ?");
    $stmt->bind_param("i", $ID);

    if ($stmt->execute()) {
        echo "Workout deleted successfully.";
    } else {
        echo "Error deleting workout: " . $conn->error;
    }

    $stmt->close();
}

function addWorkout($planID, $exerciseID, $sets, $reps, $weight, $progressiveOverloadingStrategy) {
    global $conn;

    $stmt = $conn->prepare("INSERT INTO workouts (Plan_ID, Exercise_ID, Sets, Reps, Weight, Progressive_Overloading_Strategy) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiidi", $planID, $exerciseID, $sets, $reps, $weight, $progressiveOverloadingStrategy);

    if ($stmt->execute()) {
        echo "Workout added successfully.";
    } else {
        echo "Error adding workout: " . $conn->error;
    }

    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addWorkout'])) {
    $planID = get_safe('planID');
    $exerciseID = get_safe('exerciseID');
    $sets = get_safe('sets');
    $reps = get_safe('reps');
    $weight = get_safe('weight');
    $progressiveOverloadingStrategy = get_safe('progressiveOverloadingStrategy');
    addWorkout($planID, $exerciseID, $sets, $reps, $weight, $progressiveOverloadingStrategy);
}

if (isset($_GET['deleteID'])) {
    $ID = get_safe('deleteID');
    deleteWorkout($ID);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workouts</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <?php include '../../includes/header.php'; ?>
    <br>
    <div class="container">
        <h1>Workouts List</h1>
        <div class="row">
            <div class="col-12 col-md-6 mb-3">
                <form action="workouts.php" method="get" class="form-inline">
                    <label for="searchBar" class="sr-only">Search Workouts:</label>
                    <input type="text" id="searchBar" name="searchBar" class="form-control w-100 mb-2 mb-md-0" placeholder="Search Workouts" required>
                </form>
            </div>
            <div class="col-12 col-md-6 mb-3">
                <form action="workouts.php" method="post" class="form-inline">
                    <div class="form-group mb-2">
                        <label for="planID" class="sr-only">Plan:</label>
                        <select class="form-control w-100 mb-2 mb-md-0 mr-md-2" id="planID" name="planID" required>
                            <?php
                            $planQuery = "SELECT ID, Name FROM plans";
                            $planResult = $conn->query($planQuery);
                            while ($planRow = $planResult->fetch_assoc()) {
                                echo "<option value='" . htmlspecialchars($planRow['ID'], ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($planRow['Name'], ENT_QUOTES, 'UTF-8') . "</option>";
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
                </form>
            </div>
        </div>
        <br>
        <div class="table-container">
            <table id="workoutTable" class="table table-striped">
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
                    <?php displayWorkouts(); ?>
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