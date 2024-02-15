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

// API endpoint to create a campaign
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['CampaignName'], $_POST['CampaignDescription'], $_POST['CampaignAmount'], $_POST['CharityId']) && isset($_FILES['CampaignImage'])) {
    $campaignName = $_POST['CampaignName'];
    $campaignDescription = $_POST['CampaignDescription'];
    $campaignAmount = $_POST['CampaignAmount'];
    $isApproved = isset($_POST['IsApproved']) ? $_POST['IsApproved'] : 0;
    $charityId = $_POST['CharityId'];

    // File uploads
    $campaignImage = $_FILES['CampaignImage'];
    $imageUploadPath = 'uploads/images/';
    $campaignImageUrl = $imageUploadPath . basename($campaignImage['name']);

    if (move_uploaded_file($campaignImage['tmp_name'], $campaignImageUrl)) {
        // Insert campaign into the database
        $sql = "INSERT INTO campaigns (CampaignName, CampaignDescription, CampaignImageUrl, CampaignAmount, IsApproved, CharityId)
            VALUES ('$campaignName', '$campaignDescription', '$campaignImageUrl', $campaignAmount, $isApproved, $charityId)";

        if ($conn->query($sql) === TRUE) {
            $campaignId = $conn->insert_id;
            echo json_encode(['message' => 'Campaign created successfully', 'campaignId' => $campaignId]);
        } else {
            echo json_encode(['error' => 'Error creating campaign: ' . $conn->error]);
        }
    } else {
        echo json_encode(['error' => 'Error uploading image']);
    }
} else {
    echo json_encode(['error' => 'Invalid request']);
}

// Close the connection
$conn->close();
?>