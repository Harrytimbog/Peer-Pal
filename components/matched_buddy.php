<?php
// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    // Include the database connection
    include("./includes/utils/databaseConfig.php");

    try {
        // Prepare SQL statement to select potential buddies excluding the current user
        $sql = "SELECT * FROM users WHERE student_type = 'existing student' AND id != :user_id";
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':user_id', $_SESSION['user_id']);

        // Execute the statement
        $statement->execute();

        // Check if there are any potential buddies
        if ($statement->rowCount() > 0) {
            // Fetch all potential buddies
            $potential_buddies = $statement->fetchAll(PDO::FETCH_ASSOC);

            // Select a random potential buddy
            $selected_buddy = $potential_buddies[array_rand($potential_buddies)];

            // Check if the match request already exists
            $existing_request_sql = "SELECT * FROM match_requests WHERE requester_id = :requester_id AND requested_id = :requested_id";
            $existing_request_statement = $pdo->prepare($existing_request_sql);
            $existing_request_statement->bindParam(':requester_id', $_SESSION['user_id']);
            $existing_request_statement->bindParam(':requested_id', $selected_buddy['id']);
            $existing_request_statement->execute();

            // If the match request doesn't already exist, insert it
            if ($existing_request_statement->rowCount() == 0) {
                // Create a match request between the current user and the selected buddy
                $sql_insert = "INSERT INTO match_requests (requester_id, requested_id) VALUES (:requester_id, :requested_id)";
                $stmt_insert = $pdo->prepare($sql_insert);
                $stmt_insert->bindParam(':requester_id', $_SESSION['user_id']);
                $stmt_insert->bindParam(':requested_id', $selected_buddy['id']);
                $stmt_insert->execute();

                // Display a success message
                echo '<div style="height: 100vh;">';
                echo "<h1 class='matched-user-header'>Successfully matched with {$selected_buddy['username']}!</h1>";
                echo "</div>";
            } else {
                // Display a message indicating that the match request already exists
                echo '<div style="height: 100vh;">';
                echo "<h1 class='matched-user-header'>Match request with {$selected_buddy['username']} already exists.</h1>";
                echo "</div>";
            }
        } else {
            // Display a message when no potential buddies are found
            echo '<div style="height: 100vh;">';
            echo "<h1 class='matched-user-header'>Didn't find a buddy yet...</h1>";
            echo "</div>";
        }
    } catch (PDOException $e) {
        // Handle PDO exceptions
        echo "<h1 class='matched-user-header'>Server Error</h1>";
        echo "<h3>Something went wrong</h3> " . $e->getMessage();
    }
}
