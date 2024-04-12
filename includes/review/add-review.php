<?php 

// Check for request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check for username and password from user request
    $comment = $_POST["comment"];
    $reviewer_id = $_POST["reviewer_id"];
    $reviewed_user_id = $_POST["reviewed_user_id"];
    $user_profile = $_POST["user-profile"];

    $comment = trim($comment);

    try {
        // import model, controller
        require_once "../utils/databaseConfig.php";

        // Check if the reviewer has already reviewed the user
        $sql_check = "SELECT COUNT(*) FROM reviews WHERE reviewer_id = ? AND reviewed_user_id = ?";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->execute([$reviewer_id, $reviewed_user_id]);
        $count = $stmt_check->fetchColumn();

    if ($count > 0) {
        // Redirect with error message if reviewer has already reviewed
        $redirect_url = "../../user_details.php?username=" . urlencode($user_profile) . "&error_message=You have already reviewed this user";
        header("Location: $redirect_url");
        exit();
    }

        // ERROR HANDLERS
        // Create Errors array
        $errors = [];

        if (empty($comment) || empty($reviewer_id) || empty($reviewed_user_id)) {
            $errors["empty_input"] = "You must add a comment!";
        }

        $sql = "INSERT INTO reviews (comment, reviewer_id, reviewed_user_id) VALUES (:comment, :reviewer_id, :reviewed_user_id)";
        $stmt = $pdo->prepare($sql);

        // Bind Parameters

        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt->bindParam(':reviewer_id', $reviewer_id, PDO::PARAM_STR);
        $stmt->bindParam(':reviewed_user_id', $reviewed_user_id, PDO::PARAM_STR);

        // Execute the statement

        if ($stmt->execute()) {
          header("Location: ../../user_details.php?username=" . urlencode($user_profile));
          exit();
        } else {
          $redirect_url = "../../user_details.php?username=" . urlencode($user_profile) . "&error_message=Something went";
          header("Location: $redirect_url");
          exit();
        }
    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
} else {
    // redirect to login page if the user request isn't right
    header("Location: ../../login.php");
    die();
}

?>
