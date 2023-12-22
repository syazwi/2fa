<?php
session_start(); // Start the session
include('config.php'); // Include the database configuration file

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    // Login form was submitted
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Perform SQL query to check if the provided credentials are valid
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // Authentication successful, store user ID in a session variable
        $row = $result->fetch_assoc();
        $_SESSION['user_id'] = $row['id'];

        // Redirect to the 2FA verification page
        header("Location: 2fa.php");
        exit();
    } else {
        // Authentication failed, display an error message
        $login_error_message = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2>Login</h2>
                <?php if (isset($login_error_message)) { ?>
                    <div class="alert alert-danger"><?php echo $login_error_message; ?></div>
                <?php } ?>
                <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary" name="login">Login</button>
                    <a href="register.php" class="btn btn-success ml-2">Register</a> <!-- Add Register button -->
                </form>
            </div>
        </div>
    </div>
</body>
</html>
