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

// API endpoint to delete a campaign
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['campaignId'])) {
    $campaignId = $_POST['campaignId'];

    // Delete campaign from the database
    $sql = "DELETE FROM campaigns WHERE CampaignId = $campaignId";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['message' => 'Campaign deleted successfully']);
    } else {
        echo json_encode(['error' => 'Error deleting campaign: ' . $conn->error]);
    }
} else {
    echo json_encode(['error' => 'Invalid request']);
}

// Close the connection
$conn->close();
?>
