<?php

// Check for request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check for comment and reviewed user ID from the form
    $comment = $_POST["comment"];
    $reviewed_user_id = $_POST["reviewed_user_id"];

    $comment = trim($comment);

    try {
        // import model, controller
        require_once "../utils/databaseConfig.php";

        // ERROR HANDLERS
        // Create Errors array
        $errors = [];

        if (empty($comment) || empty($reviewed_user_id)) {
            $errors["empty_input"] = "You must add a comment!";
        }

        $sql = "INSERT INTO reviews (comment, reviewer_id, reviewed_user_id) VALUES (:comment, :reviewer_id, :reviewed_user_id)";
        $stmt = $pdo->prepare($sql);

        // Bind Parameters
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt->bindParam(':reviewer_id', $_SESSION['user_id'], PDO::PARAM_INT); // Using the reviewer's ID from the session
        $stmt->bindParam(':reviewed_user_id', $reviewed_user_id, PDO::PARAM_INT); // Using the reviewed user's ID from the form

        // Execute the statement
        if ($stmt->execute()) {
            header("Location: ../../user_details.php");
            exit();
        } else {
            header("Location: " . $_SERVER['HTTP_REFERER'] ."?error=1");
            exit();
        }
    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
} else {
    // redirect to login page if the user request isn't right
    header("Location: ../../user_details.php");
    die();
}
?>
