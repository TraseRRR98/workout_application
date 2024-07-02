<?php
include '../../includes/header.php';
include '../../includes/db_connect.php';
include '../../includes/accesibles.php';
///////////////////////////////////////////////////////////////////////////////////////
function displayExercises() {
    global $conn;
    $sql = "SELECT ID, Name, Muscle_Group FROM exercises";
    $result = $conn->query($sql);

    if(!$result){
        die('Could not get data: ' . mysqli_error($conn));
    }

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {    
            echo "<tr>
                    <td>" . htmlspecialchars($row['ID'], ENT_QUOTES, 'UTF-8') . "</td>
                    <td>" . htmlspecialchars($row['Name'], ENT_QUOTES, 'UTF-8') . "</td>
                    <td>" . htmlspecialchars($row['Muscle_Group'], ENT_QUOTES, 'UTF-8') . "</td>
                    <td class='exerciseTableActions'>
                        <a href='editExercise.php?ID=" . htmlspecialchars($row['ID'], ENT_QUOTES, 'UTF-8') . "' class='button button-edit'><i class='fas fa-edit'></i>Edit</a>
                        <a href='?deleteID=" . htmlspecialchars($row['ID'], ENT_QUOTES, 'UTF-8') . "' class='button button-delete' onclick='return confirm(\"Are you sure you want to delete this exercise?\");'><i class='fas fa-trash-alt'></i>Delete</a>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='4'>No results found</td></tr>";
    }
}
///////////////////////////////////////////////////////////////////////////////////////
function deleteExercise($ID) {
    global $conn;

    $stmt = $conn->prepare("DELETE FROM exercises WHERE ID = ?");
    $stmt->bind_param("i", $ID);
    
    if ($stmt->execute()) {
        echo "Exercise deleted successfully.";
    } else {
        echo "Error deleting exercise: " . $conn->error;
    }
    
    $stmt->close();
}
///////////////////////////////////////////////////////////////////////////////////////
function addExercise($name, $muscleGroup) {
    global $conn;

    $stmt = $conn->prepare("INSERT INTO exercises (Name, Muscle_Group) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $muscleGroup);
    
    if ($stmt->execute()) {
        echo "Exercise added successfully.";
    } else {
        echo "Error adding exercise: " . $conn->error;
    }
    
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addExercise'])) {
    $name = get_safe('name');
    $muscleGroup = get_safe('muscleGroup');
    addExercise($name, $muscleGroup);
}

if (isset($_GET['deleteID'])) {
    $ID = get_safe('deleteID');
    deleteExercise($ID);
}
?>

<!------------------------------------------------------------------------------------>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercises</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <br>
    <div class="container">
        <h1>Exercises List</h1>
        <div class="row">
            <div class="col-12 col-md-6 mb-3">
                <form action="exercises.php" method="get" class="form-inline">
                    <label for="searchBar" class="sr-only">Search Exercises:</label>
                    <input type="text" id="searchBar" name="searchBar" class="form-control w-100 mb-2 mb-md-0" placeholder="Search Exercises" required>
                </form>
            </div>
            <div class="col-12 col-md-6 mb-3">
                <form action="exercises.php" method="post" class="form-inline">
                    <div class="form-group mb-2">
                        <label for="name" class="sr-only">Exercise Name:</label>
                        <input type="text" class="form-control w-100 mb-2 mb-md-0 mr-md-2" id="name" name="name" placeholder="Exercise Name" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="muscleGroup" class="sr-only">Muscle Group:</label>
                        <input type="text" class="form-control w-100 mb-2 mb-md-0 mr-md-2" id="muscleGroup" name="muscleGroup" placeholder="Muscle Group" required>
                    </div>
                    <button type="submit" class="btn btn-primary mb-2" name="addExercise">Add Exercise</button>
                </form>
            </div>
        </div>
        <br>
        <div class="table-container">
            <table id="exerciseTable" class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Muscle Group</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="exerciseTableBody">
                    <?php displayExercises(); ?>
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
    <script src="../../js/searchBarFunctionality.js"></script>
</body>
</html>
<!------------------------------------------------------------------------------------>