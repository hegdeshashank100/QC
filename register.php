<?php
// Include the database connection file
include 'db_connect.php'; // Assumes you have a db_connect.php file for database connection
session_start();

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Invalid CSRF token");
    }

    // Capture and sanitize the form data
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = $_POST['pass'];
    $confirm_password = $_POST['c_pass'];

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    }

    // Validate passwords match
    if ($password !== $confirm_password) {
        die("Passwords do not match!");
    }

    // Ensure password length is at least 8 characters
    if (strlen($password) < 8) {
        die("Password must be at least 8 characters long.");
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Handle file upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $file_tmp = $_FILES['profile_picture']['tmp_name'];
        $file_name = basename($_FILES['profile_picture']['name']);
        $file_size = $_FILES['profile_picture']['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif'];

        // Check if the file type is allowed
        if (in_array($file_ext, $allowed_exts)) {
            // Check if file size is acceptable (max 5MB)
            if ($file_size <= 5 * 1024 * 1024) {
                // Generate a unique name for the file to avoid conflicts
                $new_file_name = uniqid() . '.' . $file_ext;
                $file_dest = 'uploads/' . $new_file_name;

                // Move the uploaded file to the desired directory
                if (move_uploaded_file($file_tmp, $file_dest)) {
                    // Insert data into the database
                    $stmt = $conn->prepare("INSERT INTO users (name, email, password, profile_picture) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("ssss", $name, $email, $hashed_password, $new_file_name);

                    // Execute the query and check for success
                    if ($stmt->execute()) {
                        echo "Registration successful!";
                        // Optionally redirect after registration
                        header("Location: login.php");
                        exit;
                    } else {
                        echo "Error: " . $stmt->error;
                    }

                    // Close the statement
                    $stmt->close();
                } else {
                    echo "File upload failed.";
                }
            } else {
                echo "File is too large. Maximum allowed size is 5MB.";
            }
        } else {
            echo "Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.";
        }
    } else {
        echo "Profile picture is required.";
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Register</title>

   <!-- Font Awesome CDN Link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">

   <!-- Custom CSS File Link -->
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header class="header">
   <section class="flex">
      <a href="home.html" class="logo">QC</a>
      <form action="search.html" method="post" class="search-form">
         <input type="text" name="search_box" required placeholder="Search courses..." maxlength="100">
         <button type="submit" class="fas fa-search"></button>
      </form>
      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user"></div> <!-- Profile icon -->
         <div id="toggle-btn" class="fas fa-sun"></div> <!-- Dark/Light mode toggle -->
      </div>
      
      <!-- Profile Dropdown -->
      <div class="profile-dropdown">
         <a href="login.php" class="btn">Login</a>
      </div>
   </section>
</header>

<div class="side-bar">
   <div id="close-btn">
      <i class="fas fa-times"></i>
   </div>
   <nav class="navbar">
      <a href="home.php"><i class="fas fa-home"></i><span>Home</span></a>
      <a href="about.php"><i class="fas fa-question"></i><span>About</span></a>
      <a href="courses.php"><i class="fas fa-graduation-cap"></i><span>Courses</span></a>
      <a href="teachers.php"><i class="fas fa-chalkboard-user"></i><span>Teachers</span></a>
      <a href="contact.php"><i class="fas fa-headset"></i><span>Contact Us</span></a>
   </nav>
</div>

<section class="form-container">
   <div class="form-box">
      <form action="register.php" method="post" enctype="multipart/form-data">
         <h3>Register Now</h3>
         <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
         <p>Your Name <span>*</span></p>
         <input type="text" name="name" placeholder="Enter your name" required maxlength="50" class="box">
         <p>Your Email <span>*</span></p>
         <input type="email" name="email" placeholder="Enter your email" required maxlength="50" class="box">
         <p>Your Password <span>*</span></p>
         <input type="password" name="pass" placeholder="Enter your password" required maxlength="20" class="box">
         <p>Confirm Password <span>*</span></p>
         <input type="password" name="c_pass" placeholder="Confirm your password" required maxlength="20" class="box">
         <p>Select Profile Picture <span>*</span></p>
         <input type="file" name="profile_picture" accept="image/*" required class="box">
         <input type="submit" value="Register" name="submit" class="btn">
      </form>
   </div>
</section>

<footer class="footer">
   &copy; Copyright @ 2024 by <span>Quantum Coders</span> | All Rights Reserved!
</footer>

<!-- Custom JS File Link -->
<script src="js/script.js"></script>

</body>
</html>
