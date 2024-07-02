<?php
include '../../includes/header.php';
include '../../includes/db_connect.php';
include '../../includes/accesibles.php';
///////////////////////////////////////////////////////////////////////////////////////
function displayPlans() {
    global $conn;
    $sql = "SELECT ID, Name, Description, Day_of_Week FROM plans";
    $result = $conn->query($sql);

    if (!$result) {
        die('Could not get data: ' . mysqli_error($conn));
    }

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['ID']}</td>
                    <td>{$row['Name']}</td>
                    <td>{$row['Description']}</td>
                    <td>{$row['Day_of_Week']}</td>
                    <td class='planTableActions'>
                        <a href='editPlan.php?ID={$row['ID']}' class='button button-edit'><i class='fas fa-edit'></i>Edit</a>
                        <a href='?deleteID={$row['ID']}' class='button button-delete' onclick='return confirm(\"Are you sure you want to delete this plan?\");'><i class='fas fa-trash-alt'></i>Delete</a>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='5'>No results found</td></tr>";
    }
}
///////////////////////////////////////////////////////////////////////////////////////
function deletePlan($ID) {
    global $conn;

    $stmt = $conn->prepare("DELETE FROM plans WHERE ID = ?");
    $stmt->bind_param("i", $ID);

    if ($stmt->execute()) {
        echo "Plan deleted successfully.";
    } else {
        echo "Error deleting plan: " . $conn->error;
    }

    $stmt->close();
}

if (isset($_GET['deleteID'])) {
    $ID = get_safe('deleteID');
    deletePlan($ID);
}
///////////////////////////////////////////////////////////////////////////////////////
function addPlan($name, $description, $dayOfWeek) {
    global $conn;

    $stmt = $conn->prepare("INSERT INTO plans (Name, Description, Day_of_Week) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $description, $dayOfWeek);

    if ($stmt->execute()) {
        echo "Plan added successfully.";
    } else {
        echo "Error adding plan: " . $conn->error;
    }

    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addPlan'])) {
    $name = get_safe('name');
    $description = get_safe('description');
    $dayOfWeek = get_safe('dayOfWeek');
    addPlan($name, $description, $dayOfWeek);
}

if (isset($_GET['deleteID'])) {
    $ID = get_safe('deleteID');
    deletePlan($ID);
}
?>
<!------------------------------------------------------------------------------------>
<<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plans</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <br>
    <div class="container">
        <h1>Plans List</h1>
        <div class="row mb-3">
            <div class="col-12">
                <form action="plans.php" method="get" class="form-inline mb-3">
                    <div class="form-group mr-2">
                        <label for="searchBar" class="sr-only">Search Plans:</label>
                        <input type="text" id="searchBar" name="searchBar" class="form-control" placeholder="Search Plans" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>
            </div>
        </div>
        <form action="plans.php" method="post" class="form-inline mb-3">
            <div class="form-group mb-2">
                <label for="name" class="sr-only">Plan Name:</label>
                <input type="text" class="form-control w-100 mb-2 mb-md-0 mr-md-2" id="name" name="name" placeholder="Plan Name" required>
            </div>
            <div class="form-group mb-2">
                <label for="description" class="sr-only">Description:</label>
                <input type="text" class="form-control w-100 mb-2 mb-md-0 mr-md-2" id="description" name="description" placeholder="Description" required>
            </div>
            <div class="form-group mb-2">
                <label for="dayOfWeek" class="sr-only">Day of Week:</label>
                <select class="form-control w-100 mb-2 mb-md-0 mr-md-2" id="dayOfWeek" name="dayOfWeek" required>
                    <option value="Monday">Monday</option>
                    <option value="Tuesday">Tuesday</option>
                    <option value="Wednesday">Wednesday</option>
                    <option value="Thursday">Thursday</option>
                    <option value="Friday">Friday</option>
                    <option value="Saturday">Saturday</option>
                    <option value="Sunday">Sunday</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary mb-2" name="addPlan">Add Plan</button>
        </form>
        <div class="table-container">
            <table id="planTable" class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Day of Week</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="planTableBody">
                    <?php displayPlans(); ?>
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
</html
<!------------------------------------------------------------------------------------>