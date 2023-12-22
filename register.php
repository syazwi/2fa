<?php
include('config.php'); // Include the database configuration file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Registration form was submitted
    $reg_username = $_POST['reg_username'];
    $reg_email = $_POST['reg_email'];
    $reg_password = $_POST['reg_password'];
    
    $two_fa_input = "";
    
    for ($i = 1; $i <= 8; $i++) {
        $input_name = "2fa_input_" . $i;
        $two_fa_input .= $_POST[$input_name];
    }

    // Perform SQL query to insert a new user into the database
    $sql = "INSERT INTO users (username, email, password, 2fa_secret) VALUES ('$reg_username', '$reg_email', '$reg_password', '$two_fa_input')";
    
    if ($conn->query($sql) === TRUE) {
        // Registration successful, set a session variable for success
        $_SESSION['registration_success'] = true;
        // Close the database connection
        $conn->close();
    } else {
        // Registration failed, display an error message
        $registration_error_message = "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Add custom CSS for styling the input boxes */
        .square-input {
            width: 40px;
            height: 40px;
            text-align: center;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2>Register</h2>
                <?php if (isset($_SESSION['registration_success']) && $_SESSION['registration_success']) { ?>
                    <div class="alert alert-success">Registration successful!</div>
                    <script>
                        // Wait for 1.5 seconds and then redirect to index.php
                        setTimeout(function() {
                            window.location.href = 'index.php';
                        }, 1500);
                    </script>
                <?php } elseif (isset($registration_error_message)) { ?>
                    <div class="alert alert-danger"><?php echo $registration_error_message; ?></div>
                <?php } ?>
                <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <div class="form-group">
                        <label for="reg_username">Username:</label>
                        <input type="text" class="form-control" id="reg_username" name="reg_username" required>
                    </div>
                    <div class="form-group">
                        <label for="reg_email">Email:</label>
                        <input type="email" class="form-control" id="reg_email" name="reg_email" required>
                    </div>
                    <div class="form-group">
                        <label for="reg_password">Password:</label>
                        <input type="password" class="form-control" id="reg_password" name="reg_password" required>
                    </div>
                    <div class="form-group">
                        <label for="2fa_secret">2FA Secret:</label>
                        <div class="d-flex">
                            <?php for ($i = 1; $i <= 8; $i++) { ?>
                                <input type="text" class="form-control square-input mx-1" id="2fa_input_<?php echo $i; ?>" name="2fa_input_<?php echo $i; ?>" maxlength="1" required>
                            <?php } ?>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success" name="register">Register</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
