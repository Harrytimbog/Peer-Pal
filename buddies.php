<?php 

    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }

    if (!isset($_SESSION['user_id'])) {
      header("Location: login.php");
    }

    include("./includes/utils/databaseConfig.php");


?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PeerPal | My Buddies</title>
  <link rel="stylesheet" href="./css/navbar.css">
  <link rel="stylesheet" href="./css/match-request.css">
  <link rel="stylesheet" href="/css/homepage.css">
  <link rel="stylesheet" href="./css/buddies-content.css">
  <link rel="stylesheet" href="./css/footer.css">
  <link rel="stylesheet" href="./css/get_buddies.css">
  
</head>
<body>
  <!-- Navbar -->
  <?php include("./components/navbar.php"); ?>
  <!-- Navbar -->
<div class="buddies">

<div class="get-buddies">

  <div style="height: 70vh;">
  <?php
   include("./components/get_buddies.php");
  ?>

  </div>
  
  </div>


  
  </div>

  <!-- FOOTER -->
   <?php 
      include("./components/footer.php");
    ?>

</body>
</html>