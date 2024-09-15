<?php
include 'connect.php'; // Include your database connection file

if (isset($_GET['search_box'])) {
    $searchQuery = mysqli_real_escape_string($conn, $_GET['search_box']);

    // Example SQL query to search for courses (modify according to your database schema)
    $sql = "SELECT * FROM courses WHERE course_name LIKE '%$searchQuery%'";
    $result = mysqli_query($conn, $sql);

    echo '<!DOCTYPE html>
    <html lang="en">
    <head>
       <meta charset="UTF-8">
       <meta http-equiv="X-UA-Compatible" content="IE=edge">
       <meta name="viewport" content="width=device-width, initial-scale=1.0">
       <title>Search Results</title>
       <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
       <link rel="stylesheet" href="css/style.css">
    </head>
    <body>';

    include 'header.php'; // Include your header (if you have a separate header file)

    echo '<section class="search-results">
        <div class="container">
            <h1 class="heading">Search Results</h1>
            <div class="results">';

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="result-item">
                <h3>' . htmlspecialchars($row['course_name']) . '</h3>
                <p>' . htmlspecialchars($row['course_description']) . '</p>
                <a href="course-detail.php?id=' . $row['course_id'] . '" class="inline-btn">View Details</a>
            </div>';
        }
    } else {
        echo '<p>No results found for "' . htmlspecialchars($searchQuery) . '". Try a different search.</p>';
    }

    echo '</div>
        </div>
    </section>';

    include 'footer.php'; // Include your footer (if you have a separate footer file)

    echo '<script src="js/script.js"></script>
    </body>
    </html>';

    mysqli_close($conn);
} else {
    // Redirect or show an error message if no search query is provided
    header('Location: home.html');
    exit();
}
