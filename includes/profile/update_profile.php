<?php

// Check if a session is not already active
include("../utils/start_session.php");

// Check if the user is logged in
if ($_SERVER["REQUEST_METHOD"] === "POST") {

  try {
    // GET form data
    include("../utils/databaseConfig.php");
    include("../utils/auth_check.php");
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    # $date_of_birth = $_POST["date_of_birth"];

    // Validate date format
    $date_of_birth = $_POST["date_of_birth"];
    $date = DateTime::createFromFormat('Y-m-d', $date_of_birth);
    if (!$date || $date->format('Y-m-d') !== $date_of_birth) {
        throw new Exception("Invalid date format for date_of_birth.");
    }

    $nationality = $_POST["nationality"];
    $gender = $_POST["gender"];
    $faculty = $_POST["faculty"];
    $study_mode = $_POST["study_mode"];
    // $photo = $_POST["photo"]; // Remove this line
    $phone_number = $_POST["phone_number"];
    $program_type = $_POST["program_type"];
    $about_me = $_POST["about_me"];
    $student_type = $_POST["student_type"];

    // Handle file type
    $fileName = $_FILES['photo']['name']; // Correct usage of $_FILES instead of $_POST
    $fileTmpName = $_FILES['photo']['tmp_name'];
    $fileSize = $_FILES['photo']['size'];
    $fileErr = $_FILES['photo']['error'];
    $fileType = $_FILES['photo']['type'];

    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    $allowedFormat = array('jpg', 'jpeg', 'png', 'avif');

    // check if type of file submitted is of a format we allowed
    if (in_array($fileActualExt, $allowedFormat)) {
      if ($fileErr === 0) {
        if ($fileSize < 1000000) {
          $fileNameNew = uniqid('', true) . "." . $fileActualExt;
          $fileDestination = '../../uploads/' . $fileNameNew;
          // move the file to the desired location
          move_uploaded_file($fileTmpName, $fileDestination);
          echo "uploaded";
          $photo = $fileNameNew; // Assign the new filename to $photo
        } else {
          echo "<p>The file is too large!</p>";
        }
      } else {
        echo "<p>There was an error uploading your file!</p>";
      }
    } else {
      echo "<p>You cannot upload files of this type!</p>";
    }

    // Check if "user" session variable is set
    if (!isset($_SESSION['user_id'])) {
      throw new Exception("User session not set.");
    }

    // Write the SQL Script to update to database

    $sql = "UPDATE users SET first_name = ?, last_name = ?, date_of_birth = ?, nationality = ?, gender = ?, faculty = ?, study_mode = ?,  phone_number = ?, photo = ?, program_type = ?, about_me = ?, student_type = ? WHERE id = ?";

    $result = $pdo->prepare($sql);
    $result->execute([$first_name, $last_name, $date_of_birth, $nationality, $gender, $faculty, $study_mode,  $phone_number, $photo, $program_type, $about_me, $student_type, $_SESSION['user_id']]);

    header("Location: ../../../../profile.php");
    exit; // Ensure no further code execution after redirection
  } catch (PDOException $e) {
    // Handle database errors
    echo "Error: " . $e->getMessage();
  }
}

?>
