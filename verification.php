<?php
// Include necessary files
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

// Function to verify email
function verifyEmail($userId, $token) {
    // Connect to your database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "virtual_charity_hub";

    $conn = mysqli_connect($servername, $username, $password, $dbname);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Query to update email verification status
    $sql = "UPDATE users SET EmailVerified = 1 WHERE id = $userId AND EmailAuthToken = '$token'";

    // Execute the query
    if (mysqli_query($conn, $sql)) {
        header("Location: verified.php");
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);
}

// Check if the required parameters are set in the URL
if (isset($_GET['id']) && isset($_GET['code'])) {
    // Get the user ID and token from the URL
    $userId = $_GET['id'];
    $token = $_GET['code'];
    // Call the verifyEmail function
    verifyEmail($userId, $token);
} else {
    // Display an error message if the required parameters are not set
    echo "Invalid verification link!";
}
?>
