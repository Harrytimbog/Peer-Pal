<?php 

// imports

include "./actions/auth_check.php";
include "./includes/utils/databaseConfig.php";

try {
    // Fetch user details based on username from the url
    if (isset($_GET['username'])) {
        $username = $_GET['username'];

        // Find user with username from the database
        $sql_statement = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $sql_statement->execute([$username]);
        $user = $sql_statement->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            die("User not Found");
        }
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
    <title>Profile Page</title>
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
                <p><strong>Phone Number:</strong> <?php echo $user['phone_number']; ?></p>
                <p><strong>First Name:</strong> <?php echo $user['first_name']; ?></p>
                <p><strong>Last Name:</strong> <?php echo $user['last_name']; ?></p>
                <p><strong>Nationality:</strong> <?php echo $user['nationality']; ?></p>
                <p><strong>Date of Birth:</strong> <?php echo $user['date_of_birth']; ?></p>
                <p><strong>Gender:</strong> <?php echo $user['gender']; ?></p>
                <p><strong>Degree Level:</strong> <?php echo $user['program_type']; ?></p>
            </div>
            <div class="profile-actions">
                <a href="/" class="button" id="back-btn">Back to home</a>
                <p>
                <strong></strong> 
        <a href="https://wa.me/<?php echo $user['phone_number']; ?>">
            <img src="https://cdn-icons-png.flaticon.com/128/733/733585.png" alt="WhatsApp Icon" style="vertical-align: middle; margin-right: 5px;">

          </a>
          </p>
          <p>
          <strong></strong> 
        <a href="mailto:<?php echo $user['email']; ?>">
            <img src="https://cdn-icons-png.flaticon.com/128/888/888853.png" alt="Email Icon" style="vertical-align: middle; margin-right: 5px;">

        </a>
        </a>
    </p>      
            </div>
            </div>

            
        </section>
    </div>
    <!-- FOOTER -->
    <?php include "./components/footer.php"; ?>
</body>
</html>
