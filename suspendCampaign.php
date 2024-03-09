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

// API endpoint to approve or reject a charity
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['campaignId'], $_POST['action'])) {
    $campaignId = $_POST['campaignId'];
    $action = $_POST['action']; // suspend

    // Update charity status in the database
    $sql = "UPDATE campaigns SET Status = " . ($action === 'suspend' ? '3' : '0') . " WHERE CampaignId = $campaignId";

    if ($conn->query($sql) === TRUE) {
        header("Content-Type: application/json");
        header("Access-Control-Allow-Origin: http://localhost:4200");
    
        echo json_encode(['message' => 'Campaign ' . ucfirst($action) . 'd successfully']);
    } else {
        header("Content-Type: application/json");
        header("Access-Control-Allow-Origin: http://localhost:4200");
    
        echo json_encode(['error' => 'Error updating charity status: ' . $conn->error]);
    }
} else {
    header("Content-Type: application/json");
    header("Access-Control-Allow-Origin: http://localhost:4200");

    echo json_encode(['error' => 'Invalid request']);
}

// Close the connection
$conn->close();
?>
