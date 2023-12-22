<?php
session_start(); // Start the session

// Check if the user is logged in (user ID is stored in the session)
if (isset($_SESSION['user_id'])) {
    // If logged in, destroy the session and redirect to the login page
    session_destroy();
}

header("Location: index.php");
exit();
?>
