<?php
// Start the session
// require_once "../includes/utils/start_session.php";
// Include the database connection

try {
    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        throw new Exception("User not logged in.");
    }


    // Retrieve accepted buddies for the current user
    $user_id = $_SESSION['user_id'];

    $fetch_buddies_sql = "
        SELECT u.username, u.email
        FROM buddies b
        JOIN users u ON b.user1_id = u.id
        WHERE (b.user2_id = :user_id)
        AND b.status = 'accepted'";
    $fetch_buddies_stmt = $pdo->prepare($fetch_buddies_sql);
    $fetch_buddies_stmt->bindParam(':user_id', $user_id);
    $fetch_buddies_stmt->execute();
    $buddies = $fetch_buddies_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Display the list of accepted buddies
    if (!empty($buddies)) {
        echo "<div class='buddies-content'>";
        echo "<h2 style='text-align: center'>Accepted Buddies</h2>";
        foreach ($buddies as $buddy) {
            echo "<ul>";
        echo "<li><a href='user_details.php?username={$buddy['username']}'>{$buddy['username']}</a><br>";
        
        echo "</ul>";
    }

    } else {
        echo "<div class='buddies-content'>";
        echo "<p class='buddies-content-text'>You currently have no buddies right now</p>";
        echo "<small class='buddies-content'>You should get matched to someone</small>";
        echo "</div>";
    }
} catch (PDOException $e) {
    echo "<h3>Something went wrong while fetching buddies</h3> " . $e->getMessage();
} catch (Exception $e) {
    echo "<h3>Error:</h3> " . $e->getMessage();
}
