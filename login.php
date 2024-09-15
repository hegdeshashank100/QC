<?php
include 'components/connect.php'; // Ensure 'connect.php' provides a valid database connection
session_start(); // Start the session to track login state

if (isset($_SESSION['user_id'])) {
    header('Location: home.php'); // Redirect to home if already logged in
    exit;
}

// Generate a CSRF token for security
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Generate and validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $message[] = 'Invalid request!';
    } else {
        // Capture and sanitize the email
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        // Capture the raw password
        $password = $_POST['pass'];

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message[] = 'Invalid email format!';
        } else {
            // Query to fetch user by email
            $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? LIMIT 1");
            $select_user->execute([$email]);
            $row = $select_user->fetch(PDO::FETCH_ASSOC);

            // Check if user exists
            if ($select_user->rowCount() > 0) {
                // Verify the password using password_verify()
                if (password_verify($password, $row['password'])) {
                    // Set a session for the logged-in user
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['user_name'] = $row['name'];

                    // Optionally set a cookie for "remember me" functionality (30 days expiration)
                    if (isset($_POST['remember_me'])) {
                        setcookie('user_id', $row['id'], time() + 60*60*24*30, '/');
                    }

                    header('Location: home.php'); // Redirect to the home page
                    exit;
                } else {
                    $message[] = 'Incorrect password!';
                }
            } else {
                $message[] = 'No account found with that email!';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- Font Awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    <!-- Custom CSS file link -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="form-container">

    <form action="" method="post" class="login">
        <h3>Welcome back!</h3>

        <!-- Display error messages -->
        <?php if (isset($message)) : ?>
            <div class="error-messages">
                <?php foreach ($message as $msg) : ?>
                    <p style="color: red;"><?php echo htmlspecialchars($msg); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- CSRF Token -->
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

        <!-- Email input -->
        <p>Your email <span>*</span></p>
        <input type="email" name="email" placeholder="Enter your email" maxlength="50" required class="box">

        <!-- Password input -->
        <p>Your password <span>*</span></p>
        <input type="password" name="pass" placeholder="Enter your password" maxlength="20" required class="box">

        <!-- Remember Me checkbox -->
        <p><input type="checkbox" name="remember_me" id="remember_me"><label for="remember_me">Remember me</label></p>

        <!-- Link to register page -->
        <p class="link">Don't have an account? <a href="register.php">Register now</a></p>

        <!-- Submit button -->
        <input type="submit" name="submit" value="Login now" class="btn">
    </form>

</section>

<?php include 'components/footer.php'; ?>

<!-- Custom JS file link -->
<script src="js/script.js"></script>

</body>
</html>
