<?php

// Replace these values with your MySQL database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "virtual_charity_hub";

// Create a connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// API endpoint to get the list of campaigns
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Select all campaigns from the database
    $sql = "SELECT * FROM campaigns";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $campaigns = array();
        // Fetch campaigns and store them in an array
        while ($row = $result->fetch_assoc()) {
            $campaigns[] = $row;
        }
        header("Access-Control-Allow-Origin: http://localhost:4200");

        echo json_encode([$campaigns]);
    } else {
        // If no campaigns found, return an empty array
        header("Access-Control-Allow-Origin: http://localhost:4200");

        echo json_encode([[]]);
    }
} else {
    header("Access-Control-Allow-Origin: http://localhost:4200");

    echo json_encode(['error' => 'Invalid request']);
}

// Close the connection
$conn->close();
?>
