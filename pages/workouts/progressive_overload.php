<?php
require_once '../../includes/session.php';
check_login();
require_once '../../includes/db_connect.php';
require_once '../../includes/accesibles.php';

function applyProgressiveOverload($workoutID) {
    global $conn;

    // Fetch initial workout details from the workouts table
    $sql = "SELECT * FROM workouts WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $workoutID);
    $stmt->execute();
    $workout = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    $newWeight = $workout['Weight'];
    $newReps = $workout['Reps'];
    $newSets = $workout['Sets'];
    $initialReps = $workout['Initial_Reps'];
    $strategy = $workout['Progressive_Overloading_Strategy'];
    $userID = $workout['User_ID'];

    switch ($strategy) {
        case 1: // Percentage Increase
            $newWeight *= 1.05; // Increase weight by 5%
            break;
        case 2: // Fixed Increase
            $newWeight += 5; // Increase weight by 5 lbs
            break;
        case 3: // Reps Increase
            $newReps += 1; // Increase reps by 1
            if ($newReps > 12) { // Reset reps to initial value and increase weight if reps exceed 12
                $newReps = $initialReps;
                $newWeight += 5;
            }
            break;
    }

    echo "Updating workout ID: $workoutID with Weight: $newWeight, Reps: $newReps, Sets: $newSets"; // Debugging output

    // Update the workout with new weight, reps, and sets
    $stmt = $conn->prepare("UPDATE workouts SET Weight = ?, Reps = ?, Sets = ? WHERE ID = ? AND User_ID = ?");
    $stmt->bind_param("diiii", $newWeight, $newReps, $newSets, $workoutID, $userID);
    if ($stmt->execute()) {
        echo "Workout updated successfully.";
    } else {
        echo "Error updating workout: " . $conn->error;
    }
    $stmt->close();
}
?>
