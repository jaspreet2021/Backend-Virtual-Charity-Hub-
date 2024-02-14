<?php

// Function to sanitize and validate input data
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Function to hash the password
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "virtual_charity_hub";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to send email verification


// Function to handle user registration
function registerUser($conn, $name, $email, $password, $phone, $role) {
    $name = sanitizeInput($name);
    $email = sanitizeInput($email);
    $password = sanitizeInput($password);
    $phone = sanitizeInput($phone);
    $role = sanitizeInput($role);

    // Check if the email already exists
    $sqlchk = "SELECT COUNT(*) as count FROM users WHERE Email = '$email'";
    $result = mysqli_query($conn, $sqlchk);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        
        // If email exists, return an error response
        if ($row['count'] > 0) {
            echo json_encode(array("success" => false, "message" => "Email Already Registered"));
            return false;
        }
    } else {
        return false;
    }

    // Hash the password
    $hashedPassword = hashPassword($password);

    // Insert user data into the 'users' table
    $sql = "INSERT INTO users (Name, Email, Password, Phone, Role, EmailVerified, EmailAuthToken) 
            VALUES ('$name', '$email', '$hashedPassword', '$phone', '$role', 0, '')";

    if (mysqli_query($conn, $sql)) {
        return true; // Registration successful
    } else {
        return false; // Registration failed
    }
}


// Check if the registration API endpoint is called
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get data from the request
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $phone = $_POST["phone"];
    $role = $_POST["role"];

    

    // Call the registerUser function
    $registrationResult = registerUser($conn, $name, $email, $password, $phone, $role);

    // Send a JSON response
    header("Content-Type: application/json");
    header("Access-Control-Allow-Origin: http://localhost:4200");

    if ($registrationResult) {
        echo json_encode(array("success" => true, "message" => "Registration successful"));
    } else {
        echo json_encode(array("success" => false, "message" => "Registration failed"));
    }
} else {
    // Send an error response if the request method is not POST
    header("Content-Type: application/json");
    header("Access-Control-Allow-Origin: http://localhost:4200");
    echo json_encode(array("success" => false, "message" => "Invalid request method"));
}

// Close the database connection
mysqli_close($conn);

?>
