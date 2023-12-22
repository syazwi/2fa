<?php
session_start(); // Start the session
include('config.php'); // Include the database configuration file

// Check if the user is logged in (user ID is stored in the session)
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to the login page
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verify 2FA code
    $two_fa_input = "";
    
    for ($i = 1; $i <= 8; $i++) {
        $input_name = "two_fa_input_" . $i;
        $two_fa_input .= $_POST[$input_name];
    }

    // Retrieve the user's 2FA secret from the database based on user ID
    $sql = "SELECT 2fa_secret FROM users WHERE id = $user_id";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $two_fa_secret = $row['2fa_secret'];

        // Implement your 2FA verification logic here
        if ($two_fa_input === $two_fa_secret) {
            // 2FA verification successful, redirect to the dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            // 2FA verification failed, display an error message
            $verification_error_message = "2FA verification failed. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>2FA Verification</title>
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
                <h2>2FA Verification</h2>
                <?php if (isset($verification_error_message)) { ?>
                    <div class="alert alert-danger"><?php echo $verification_error_message; ?></div>
                <?php } ?>
                <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <div class="form-group">
                        <label for="two_fa_input">Enter 2FA Code:</label>
                        <div class="d-flex">
                            <?php for ($i = 1; $i <= 8; $i++) { ?>
                                <input type="text" class="form-control square-input mx-1" id="two_fa_input_<?php echo $i; ?>" name="two_fa_input_<?php echo $i; ?>" maxlength="1" required>
                            <?php } ?>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Verify</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
