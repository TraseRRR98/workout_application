<?php
include '../../includes/session.php';
check_login();
?>
<?php
include '../../includes/header.php';
include '../../includes/db_connect.php';
include '../../includes/accesibles.php';
///////////////////////////////////////////////////////////////////////////////////////
function getPlan($ID) {
    global $conn;
    $stmt = $conn->prepare("SELECT Name, Description, Day_of_Week FROM plans WHERE ID = ?");
    $stmt->bind_param("i", $ID);
    $stmt->execute();
    $stmt->bind_result($name, $description, $dayOfWeek);
    $stmt->fetch();
    $stmt->close();
    return ['name' => $name, 'description' => $description, 'dayOfWeek' => $dayOfWeek];
}
///////////////////////////////////////////////////////////////////////////////////////
function updatePlan($ID, $name, $description, $dayOfWeek) {
    global $conn;
    $stmt = $conn->prepare("UPDATE plans SET Name = ?, Description = ?, Day_of_Week = ? WHERE ID = ?");
    $stmt->bind_param("sssi", $name, $description, $dayOfWeek, $ID);

    if ($stmt->execute()) {
        echo "Plan updated successfully.";
    } else {
        echo "Error updating plan: " . $conn->error;
    }

    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['updatePlan'])) {
    $ID = get_safe('ID');
    $name = get_safe('name');
    $description = get_safe('description');
    $dayOfWeek = get_safe('dayOfWeek');
    updatePlan($ID, $name, $description, $dayOfWeek);
    header('Location: plans.php');
    exit;
}

$plan = getPlan(get_safe('ID'));
?>
<!------------------------------------------------------------------------------------>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Plan</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <br>
    <div class="container">
        <h1>Edit Plan</h1>
        <form action="editPlan.php" method="post">
            <input type="hidden" name="ID" value="<?php echo $_GET['ID']; ?>">
            <div class="form-group">
                <label for="name">Plan Name:</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $plan['name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <input type="text" class="form-control" id="description" name="description" value="<?php echo $plan['description']; ?>" required>
            </div>
            <div class="form-group">
                <label for="dayOfWeek">Day of Week:</label>
                <select class="form-control" id="dayOfWeek" name="dayOfWeek" required>
                    <option value="Monday" <?php echo $plan['dayOfWeek'] == 'Monday' ? 'selected' : ''; ?>>Monday</option>
                    <option value="Tuesday" <?php echo $plan['dayOfWeek'] == 'Tuesday' ? 'selected' : ''; ?>>Tuesday</option>
                    <option value="Wednesday" <?php echo $plan['dayOfWeek'] == 'Wednesday' ? 'selected' : ''; ?>>Wednesday</option>
                    <option value="Thursday" <?php echo $plan['dayOfWeek'] == 'Thursday' ? 'selected' : ''; ?>>Thursday</option>
                    <option value="Friday" <?php echo $plan['dayOfWeek'] == 'Friday' ? 'selected' : ''; ?>>Friday</option>
                    <option value="Saturday" <?php echo $plan['dayOfWeek'] == 'Saturday' ? 'selected' : ''; ?>>Saturday</option>
                    <option value="Sunday" <?php echo $plan['dayOfWeek'] == 'Sunday' ? 'selected' : ''; ?>>Sunday</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" name="updatePlan">Update Plan</button>
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
<!------------------------------------------------------------------------------------>