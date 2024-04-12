<?php 

// imports

include "./actions/auth_check.php";
include "./includes/utils/databaseConfig.php";

try {
    // Fetch user details based on username from the URL
    if (isset($_GET['username'])) {
        $username = $_GET['username'];

        // Find user with username from the database
        $sql_statement = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $sql_statement->execute([$username]);
        $user = $sql_statement->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            die("User not Found");
        }

        // Retrieve reviews for the user
        $sql_reviews = "SELECT r.*, u.username AS reviewer_username FROM reviews r
                        JOIN users u ON r.reviewer_id = u.id
                        WHERE reviewed_user_id = ?";
        $stmt_reviews = $pdo->prepare($sql_reviews);
        $stmt_reviews->execute([$user['id']]);
        $reviews = $stmt_reviews->fetchAll(PDO::FETCH_ASSOC);
    } else {
        header("Location: 404.php");
        exit(); // Terminate script after redirect
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit(); // Terminate script if database connection fails
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/navbar.css">
    <link rel="stylesheet" href="./css/footer.css">
    <link rel="stylesheet" href="css/profilepage.css">
    <title><?php echo $user['username'];  ?>'s Profile Page</title>
    <script>
        // JavaScript function to display error message as a pop-up
        function displayErrorMessage(message) {
            alert(message);
        }
    </script>
</head>
<body>
    <!-- HEADER / NAVBAR -->    
    <?php include "./components/navbar.php"; ?>
    <div class="container">
        <section class="profile">
            <div class="profile-picture">
                <!-- <img src="<?php echo $user['photo']; ?>" alt="Profile Picture"> -->
                <img src="./uploads/<?php echo $user['photo']; ?>" alt="Profile Picture">
            </div>
            <div class="profile-info">
                <p><strong>Username:</strong> <?php echo $user['username']; ?></p>
                <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
                <p><strong>First Name:</strong> <?php echo $user['first_name']; ?></p>
                <p><strong>Last Name:</strong> <?php echo $user['last_name']; ?></p>
                <p><strong>Nationality:</strong> <?php echo $user['nationality']; ?></p>
                <p><strong>Date of Birth:</strong> <?php echo $user['date_of_birth']; ?></p>
                <p><strong>Gender:</strong> <?php echo $user['gender']; ?></p>
                <p><strong>Degree Level:</strong> <?php echo $user['program_type']; ?></p>
            </div>
            <div class="profile-actions">
                <a href="/" class="button" id="back-btn">Back to home</a>
            </div>

            <!-- Add Review form -->
            <div class="add-review-form">
                <h3>Add Review</h3>
                <form action="./includes/review/add-review.php" method="POST" onsubmit="return validateForm()">
                    <textarea name="comment" id="comment" cols="30" rows="10"></textarea>
                    <input type="hidden" name="user-profile" value="<?php echo $_GET['username']; ?>">
                    <input type="hidden" name="reviewer_id" value="<?php echo $_SESSION['user_id']; ?>">
                    <input type="hidden" name="reviewed_user_id" value="<?php echo $user['id']; ?>">
                    <button type="submit" class="Add Review">Add Review</button>
                </form>
            </div>

            <!-- Reviews -->
            <h3>Reviews</h3>
            <div class="reviews">
                <?php 
                    // Display reviews for the user
                    foreach ($reviews as $review) {
                        echo "<p><strong>Reviewer:</strong> {$review['reviewer_username']}</p>";
                        echo "<p><strong>Comment:</strong> {$review['comment']}</p>";
                        // Add more review details as needed
                    }

                    // Display error message as a pop-up if present
                    if (!empty($_GET['error_message'])) {
                        echo "<script>displayErrorMessage('{$_GET['error_message']}');</script>";
                    }
                ?>
            </div>
        </section>
    </div>
    <!-- FOOTER -->
    <?php include "./components/footer.php"; ?>
    <script>
        function validateForm() {
            // Check if the comment field is empty
            var comment = document.getElementById('comment').value;
            if (comment.trim() === '') {
                alert('Comment field is required.');
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
