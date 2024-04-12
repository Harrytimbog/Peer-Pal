<?php 

require_once realpath(__DIR__ . "/../../vendor/autoload.php");


use Dotenv\Dotenv;

// Load the .env file from the root folder
$dotenv = Dotenv::createImmutable(dirname(__DIR__ . "/../../.env"));
$dotenv->load();

// Access environmental variables
$db_servername = $_ENV['DB_SERVERNAME'];
$db_username = $_ENV['DB_USERNAME'];
$db_password = $_ENV['DB_PASSWORD']; 
$db_name = $_ENV['DB_NAME'];

try {
  $pdo = new PDO("mysql:host=$db_servername;dbname=$db_name", $db_username, $db_password);

  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // // Create Database

  // $sql_db = "CREATE DATABASE IF NOT EXISTS peer-pal";
  // $pdo->exec($sql_db);

  // // Execute Database

  // $pdo->exec("USE peer-pal");

  // Create database tables if they don't exist
  $sql = "CREATE TABLE IF NOT EXISTS users (
      id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
      username VARCHAR(30) NOT NULL,
      email VARCHAR(100) NOT NULL,
      password VARCHAR(255) NOT NULL,
      first_name VARCHAR(255),
      last_name VARCHAR(255),
      date_of_birth DATE,
      nationality VARCHAR(255),
      gender VARCHAR(255),
      faculty VARCHAR(255),
      study_mode VARCHAR(255),
      phone_number VARCHAR(255),
      photo VARCHAR(255),
      is_admin BOOLEAN DEFAULT FALSE,
      program_type VARCHAR(255),
      student_type VARCHAR(255),
      about_me TEXT,
      verify_token VARCHAR(255),
      UNIQUE (username),
      UNIQUE (email),
      created_at DATETIME DEFAULT CURRENT_TIMESTAMP
  )";

  $sql2 = "CREATE TABLE IF NOT EXISTS buddies (
      id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
      user1_id INT(11) UNSIGNED NOT NULL,
      user2_id INT(11) UNSIGNED NOT NULL,
      status VARCHAR(20) DEFAULT 'pending',
      FOREIGN KEY (user1_id) REFERENCES users(id) ON DELETE CASCADE,
      FOREIGN KEY (user2_id) REFERENCES users(id) ON DELETE CASCADE,
      UNIQUE (user1_id, user2_id)
  )";

  $sql3 = "CREATE TABLE IF NOT EXISTS match_requests (
      id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
      requester_id INT(11) UNSIGNED NOT NULL,
      requested_id INT(11) UNSIGNED NOT NULL,
      status VARCHAR(20) DEFAULT 'pending',
      created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
      FOREIGN KEY (requester_id) REFERENCES users(id) ON DELETE CASCADE,
      FOREIGN KEY (requested_id) REFERENCES users(id) ON DELETE CASCADE,
      UNIQUE (requester_id, requested_id)
  )";


  $sql4 = "CREATE TABLE IF NOT EXISTS reviews (
      id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
      reviewer_id INT(11) UNSIGNED NOT NULL,
      reviewed_user_id INT(11) UNSIGNED NOT NULL, -- Added column for the user being reviewed
      comment TEXT,
      FOREIGN KEY (reviewer_id) REFERENCES users(id) ON DELETE CASCADE,
      FOREIGN KEY (reviewed_user_id) REFERENCES users(id) ON DELETE CASCADE, -- Added foreign key constraint
      UNIQUE (reviewer_id)
  )";

  $pdo->exec($sql);
  $pdo->exec($sql2);
  $pdo->exec($sql3);
  $pdo->exec($sql4);
  
// echo "Connected Successfully";
  // echo "User table created successfully";
} catch (PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
  die("Connection failed: " . $e->getMessage());
}
