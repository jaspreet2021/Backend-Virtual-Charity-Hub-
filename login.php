<?php

// Replace these values with your MySQL database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "virtual_charity_hub";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function loginUser($conn, $email, $password) {
    $email = sanitizeInput($email);
    $password = sanitizeInput($password);

    // Fetch user data from the database based on the provided email
    $sql = "SELECT * FROM users WHERE Email = '$email'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $user = mysqli_fetch_assoc($result);

        // Verify the entered password against the stored hash
        if ($user && password_verify($password, $user['Password'])) {
            return $user; // Login successful, return user data
        } else {
            return null; // Incorrect password or user not found
        }
    } else {
        return null; // Query failed
    }
}


// API endpoint to handle user login
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get data from the request
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Call the loginUser function
    $user = loginUser($conn, $email, $password);

    // Send a JSON response
    header("Content-Type: application/json");

    if ($user) {
        echo json_encode(array("success" => true, "message" => "Login successful", "user" => $user));
    } else {
        echo json_encode(array("success" => false, "message" => "Incorrect email or password"));
    }
} else {
    // Send an error response if the request method is not POST
    header("Content-Type: application/json");
    echo json_encode(array("success" => false, "message" => "Invalid request method"));
}

// Close the database connection
mysqli_close($conn);

?>
