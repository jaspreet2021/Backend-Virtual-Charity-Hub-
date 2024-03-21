<?php

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "virtual_charity_hub";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Function to update a user by ID with specific fields
function updateUserById($conn, $userId, $fields)
{
    $sql = "UPDATE users SET ";
    $updates = array();

    // Loop through the fields array and construct the SET part of the SQL query
    foreach ($fields as $key => $value) {
        // Escape the values to prevent SQL injection
        $escapedValue = mysqli_real_escape_string($conn, $value);
        // Add the field and its value to the updates array
        $updates[] = "$key='$escapedValue'";
    }

    // Join the updates array into a string separated by commas
    $sql .= implode(", ", $updates);
    $sql .= " WHERE Id='$userId'";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}

// Handle POST request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the user ID from the request body
    $userId = $_POST["Id"];

    // Specify the fields you want to update
    $fields = array();
    if (isset($_POST["Name"])) {
        $fields["Name"] = $_POST["Name"];
    }
    if (isset($_POST["Email"])) {
        $fields["Email"] = $_POST["Email"];
    }
    if (isset($_POST["Password"])) {
        $hashedPassword = password_hash($_POST["Password"], PASSWORD_DEFAULT);
        $fields["password"] = $hashedPassword;
    }
    
    if (isset($_POST["Phone"])) {
        $fields["Phone"] = $_POST["Phone"];
    }
    if (isset($_POST["Role"])) {
        $fields["Role"] = $_POST["Role"];
    }

    // Call updateUserById with the specified fields
    $result = updateUserById($conn, $userId, $fields);
    header("Content-Type: application/json");
    header("Access-Control-Allow-Origin: http://localhost:4200");

    if ($result) {
        
        echo json_encode(array("success" => true, "message" => "User updated successfully"));
    } else {
        echo json_encode(array("success" => false, "message" => "User update failed"));
    }
}
?>