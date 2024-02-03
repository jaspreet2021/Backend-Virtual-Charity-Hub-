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

// API endpoint to get a list of charities
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT * FROM charities";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $charities = array();

        while ($row = $result->fetch_assoc()) {
            $charities[] = $row;
        }

        echo json_encode(['charities' => $charities]);
    } else {
        echo json_encode(['charities' => []]);
    }
} else {
    echo json_encode(['error' => 'Invalid request']);
}

// Close the connection
$conn->close();
?>
