<?php
session_start(); // Start the session to access session variables

include 'components/connect.php'; // Ensure this file contains a working PDO connection

// Check if the user is logged in by verifying the session
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Fetch user data
    $select_user = $conn->prepare("SELECT * FROM `users` WHERE id = ? LIMIT 1");
    $select_user->execute([$user_id]);
    $user = $select_user->fetch(PDO::FETCH_ASSOC);
} else {
    $user = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>About Us</title>

   <!-- Font Awesome CDN link -->
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

<!-- Profile Dropdown -->
<div class="profile-dropdown">
   <?php if ($user): ?>
      <div class="profile">
         <img src="uploaded_files/<?= isset($user['image']) ? htmlspecialchars($user['image']) : 'default.jpg'; ?>" alt="Profile Image">
         <h3 class="name"><?= htmlspecialchars($user['name']); ?></h3>
         <a href="profile.php" class="btn">Profile</a>
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
      <?php if ($user): ?>
         <img src="uploaded_files/<?= isset($user['image']) ? htmlspecialchars($user['image']) : 'default.jpg'; ?>" alt="Profile Image">
         <h3 class="name"><?= htmlspecialchars($user['name']); ?></h3>
         <a href="profile.php" class="btn">Profile</a>
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

<section class="about">
   <div class="row">
      <div class="image">
         <img src="images/about-img.svg" alt="">
      </div>
      <div class="content">
         <h3>Why Choose Us?</h3>
         <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ut dolorum quasi illo? Distinctio expedita commodi, nemo a quam error repellendus sint, fugiat quis numquam eum eveniet sequi aspernatur quaerat tenetur.</p>
         <a href="courses.php" class="inline-btn">Our Courses</a>
      </div>
   </div>
   <div class="box-container">
      <!-- Stats Boxes -->
      <div class="box">
         <i class="fas fa-graduation-cap"></i>
         <div>
            <h3>+10k</h3>
            <p>Online Courses</p>
         </div>
      </div>
      <div class="box">
         <i class="fas fa-user-graduate"></i>
         <div>
            <h3>+40k</h3>
            <p>Brilliant Students</p>
         </div>
      </div>
      <div class="box">
         <i class="fas fa-chalkboard-user"></i>
         <div>
            <h3>+2k</h3>
            <p>Expert Tutors</p>
         </div>
      </div>
      <div class="box">
         <i class="fas fa-briefcase"></i>
         <div>
            <h3>100%</h3>
            <p>Job Placement</p>
         </div>
      </div>
   </div>
</section>

<section class="reviews">
   <h1 class="heading">Student's Reviews</h1>
   <div class="box-container">
      <!-- Reviews Boxes -->
      <div class="box">
         <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Necessitatibus, suscipit a. Quibusdam, dignissimos consectetur. Sed ullam iusto eveniet qui aut quibusdam vero voluptate libero facilis fuga. Eligendi eaque molestiae modi?</p>
         <div class="student">
            <img src="images/pic-2.jpg" alt="">
            <div>
               <h3>John Deo</h3>
               <div class="stars">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star-half-alt"></i>
               </div>
            </div>
         </div>
      </div>
      <!-- Additional reviews -->
   </div>
</section>

<footer class="footer">
   &copy; 2024 by <span>Quantum Coders</span> | All rights reserved!
</footer>

<!-- Custom JS file link -->
<script src="js/script.js"></script>

</body>
</html>
