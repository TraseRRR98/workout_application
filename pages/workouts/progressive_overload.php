<?php
include '../../includes/db_connect.php';

function getLatestSession($workoutID) {
    global $conn;
    $sql = "SELECT * FROM workout_sessions WHERE Workout_ID = ? ORDER BY Date DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $workoutID);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function applyProgressiveOverload($workoutID) {
    global $conn;

    $latestSession = getLatestSession($workoutID);
    if (!$latestSession) {
        // Fetch initial workout details if no sessions are found
        $sql = "SELECT * FROM workouts WHERE ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $workoutID);
        $stmt->execute();
        $workout = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        $newWeight = $workout['Weight'];
        $newReps = $workout['Reps'];
        $newSets = $workout['Sets'];
        $strategy = $workout['Progressive_Overloading_Strategy'];
    } else {
        $strategy = $latestSession['Progressive_Overloading_Strategy'];
        $newWeight = $latestSession['Weight'];
        $newReps = $latestSession['Reps'];
        $newSets = $latestSession['Sets'];
    }

    switch ($strategy) {
        case 1: // Percentage Increase
            $newWeight *= 1.05; // Increase weight by 5%
            break;
        case 2: // Fixed Increase
            $newWeight += 5; // Increase weight by 5 lbs
            break;
        case 3: // Reps Increase
            $newReps += 1; // Increase reps by 1
            if ($newReps > 12) { // Reset reps and increase weight if reps exceed 12
                $newReps = 8;
                $newWeight += 5;
            }
            break;
    }

    echo "Updating workout ID: $workoutID with Weight: $newWeight, Reps: $newReps, Sets: $newSets"; // Debugging output

    // Update the workout with new weight, reps, and sets
    $stmt = $conn->prepare("UPDATE workouts SET Weight = ?, Reps = ?, Sets = ? WHERE ID = ?");
    $stmt->bind_param("diii", $newWeight, $newReps, $newSets, $workoutID);
    if ($stmt->execute()) {
        echo "Workout updated successfully.";
    } else {
        echo "Error updating workout: " . $conn->error;
    }
    $stmt->close();
}
?>
