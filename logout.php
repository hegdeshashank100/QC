<?php
session_start(); // Start the session to access session variables

// Unset all session variables
session_unset();

// Destroy the session
session_destroy();

// Optionally, clear the user_id cookie if it's set
if (isset($_COOKIE['user_id'])) {
    setcookie('user_id', '', time() - 3600, '/'); // Expire the cookie
}

// Redirect to the login page
header('Location:login.php');
exit;
?>
