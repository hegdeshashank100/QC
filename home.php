<?php
session_start(); // Start the session to access session variables

include 'components/connect.php'; // Ensure this file contains a working PDO connection

// Check if the user is logged in by verifying the session
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit;
}

$user_id = $_SESSION['user_id']; // Get user ID from session

// Fetch user data
$select_user = $conn->prepare("SELECT * FROM `users` WHERE id = ? LIMIT 1");
$select_user->execute([$user_id]);
$user = $select_user->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Home</title>

   <!-- Font awesome CDN link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">

   <!-- Custom CSS file link -->
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header class="header">
   <section class="flex">
      <a href="home.php" class="logo">QC</a>
      <form action="search.php" method="post" class="search-form">
         <input type="text" name="search_box" required placeholder="Search courses..." maxlength="100">
         <button type="submit" class="fas fa-search"></button>
      </form>
      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user"></div> <!-- Profile icon -->
         <div id="toggle-btn" class="fas fa-sun"></div> <!-- Dark/Light mode toggle -->
      </div>
   </section>
</header>

<!-- Chatbot Button -->
<script src="https://cdn.botpress.cloud/webchat/v1/inject.js"></script>
<script src="https://mediafiles.botpress.cloud/0d9e9b81-79d6-4860-9829-f461600ce319/webchat/config.js" defer></script>
<!-- Profile Dropdown -->
<div class="profile-dropdown">
   <?php if (isset($user)): ?>
      <div class="profile">
         <h3 class="name"><?= htmlspecialchars($user['name']); ?></h3>
         <a href="logout.php" class="btn">Logout</a>
      </div>
   <?php else: ?>
      <a href="login.php" class="btn">Login</a>
   <?php endif; ?>
</div>

<div class="side-bar">
   <div id="close-btn">
      <i class="fas fa-times"></i>
   </div>
   <div class="profile">
      <?php if (isset($user)): ?>
         <h3 class="name"><?= htmlspecialchars($user['name']); ?></h3>
         <a href="logout.php" class="btn">Logout</a>
      <?php else: ?>
         <a href="login.php" class="btn">Login</a>
      <?php endif; ?>
   </div>
   <nav class="navbar">
      <a href="home.php"><i class="fas fa-home"></i><span>Home</span></a>
      <a href="about.php"><i class="fas fa-question"></i><span>About</span></a>
      <a href="courses.php"><i class="fas fa-graduation-cap"></i><span>Courses</span></a>
      <a href="teachers.php"><i class="fas fa-chalkboard-user"></i><span>Teachers</span></a>
      <a href="contact.php"><i class="fas fa-headset"></i><span>Contact Us</span></a>
   </nav>
</div>

<section class="home-grid">
   <h1 class="heading">Quick Options</h1>
   <div class="box-container">
      <div class="box">
         <h3 class="title">Likes and Comments</h3>
         <p class="likes">Total likes : <span>25</span></p>
         <a href="#" class="inline-btn">View Likes</a>
         <p class="likes">Total comments : <span>12</span></p>
         <a href="#" class="inline-btn">View Comments</a>
         <p class="likes">Saved playlists : <span>4</span></p>
         <a href="#" class="inline-btn">View Playlists</a>
      </div>
      <!-- Other boxes -->
   </div>
</section>

<section class="courses">
   <h1 class="heading">Our Courses</h1>
   <div class="box-container">
      <!-- Course boxes -->
   </div>
   <div class="more-btn">
      <a href="courses.php" class="inline-option-btn">View All Courses</a>
   </div>
</section>

<footer class="footer">
   &copy; 2024 by <span>Quantum Coders</span> | All rights reserved!
</footer>

<!-- Custom JS file link -->
<script src="js/script.js"></script>

</body>
</html>
