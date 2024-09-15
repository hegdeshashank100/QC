<?php
include 'components/connect.php';

if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    header('location:login.html');
    exit;
}

$select_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ?");
$select_likes->execute([$user_id]);
$total_likes = $select_likes->rowCount();

$select_comments = $conn->prepare("SELECT * FROM `comments` WHERE user_id = ?");
$select_comments->execute([$user_id]);
$total_comments = $select_comments->rowCount();

$select_bookmark = $conn->prepare("SELECT * FROM `bookmark` WHERE user_id = ?");
$select_bookmark->execute([$user_id]);
$total_bookmarked = $select_bookmark->rowCount();

$fetch_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
$fetch_profile->execute([$user_id]);
$profile = $fetch_profile->fetch(PDO::FETCH_ASSOC);

if (!$profile) {
    header('location:login.html');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Profile</title>

   <!-- Font Awesome CDN link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">

   <!-- Custom CSS file link -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<header class="header">
   <section class="flex">
      <a href="home.html" class="logo">QC</a>
      <form action="search.html" method="post" class="search-form">
         <input type="text" name="search_box" required placeholder="search courses..." maxlength="100">
         <button type="submit" class="fas fa-search"></button>
      </form>
      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user"></div>
         <div id="toggle-btn" class="fas fa-sun"></div>
      </div>
      <div class="profile" allign>
   <img src="uploaded_files/<?= isset($profile['image']) ? htmlspecialchars($profile['image']) : 'default.jpg'; ?>" alt="Profile Image">
   <h3 class="name"><?= htmlspecialchars($profile['name']); ?></h3>
   <p class="role"><?= htmlspecialchars($profile['role']); ?></p>
   <a href="profile.html" class="btn">View Profile</a>
   <div class="flex-btn">
      <a href="login.html" class="option-btn">Login</a>
      <a href="register.html" class="option-btn">Register</a>
   </div>
</div>

   </section>
</header>   

<div class="side-bar">
   <div id="close-btn">
      <i class="fas fa-times"></i>
   </div>
   <div class="profile">
      <img src="uploaded_files/<?= isset($profile['image']) ? htmlspecialchars($profile['image']) : 'default.jpg'; ?>" class="image" alt="Profile Image">
      <h3 class="name"><?= htmlspecialchars($profile['name']); ?></h3>
      <p class="role"><?= htmlspecialchars($profile['role']); ?></p>
      <a href="profile.html" class="btn">View Profile</a>
   </div>
   <nav class="navbar">
      <a href="home.html"><i class="fas fa-home"></i><span>Home</span></a>
      <a href="about.html"><i class="fas fa-question"></i><span>About</span></a>
      <a href="courses.html"><i class="fas fa-graduation-cap"></i><span>Courses</span></a>
      <a href="teachers.html"><i class="fas fa-chalkboard-user"></i><span>Teachers</span></a>
      <a href="contact.html"><i class="fas fa-headset"></i><span>Contact Us</span></a>
   </nav>
</div>

<section class="user-profile">
   <h1 class="heading">Your Profile</h1>
   <div class="info">
      <div class="user">
         <img src="uploaded_files/<?= isset($profile['image']) ? htmlspecialchars($profile['image']) : 'default.jpg'; ?>" alt="Profile Image">
         <h3><?= htmlspecialchars($profile['name']); ?></h3>
         <p><?= htmlspecialchars($profile['role']); ?></p>
         <a href="update.html" class="inline-btn">Update Profile</a>
      </div>
      <div class="box-container">
         <div class="box">
            <div class="flex">
               <i class="fas fa-bookmark"></i>
               <div>
                  <span><?= $total_bookmarked; ?></span>
                  <p>Saved Playlists</p>
               </div>
            </div>
            <a href="#" class="inline-btn">View Playlists</a>
         </div>
         <div class="box">
            <div class="flex">
               <i class="fas fa-heart"></i>
               <div>
                  <span><?= $total_likes; ?></span>
                  <p>Liked Tutorials</p>
               </div>
            </div>
            <a href="#" class="inline-btn">View Liked</a>
         </div>
         <div class="box">
            <div class="flex">
               <i class="fas fa-comment"></i>
               <div>
                  <span><?= $total_comments; ?></span>
                  <p>Video Comments</p>
               </div>
            </div>
            <a href="#" class="inline-btn">View Comments</a>
         </div>
      </div>
   </div>
</section>

<footer class="footer">
   &copy; copyright @ 2024 by <span>Quantum Coders</span> | all rights reserved!
</footer>

<!-- Custom JS file link -->
<script src="js/script.js"></script>

</body>
</html>
