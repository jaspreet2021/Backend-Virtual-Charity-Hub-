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

// API endpoint to update a campaign
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['campaignId'])) {
    $campaignId = $_POST['campaignId'];
    $sqlUpdates = array();

    // Check and construct SQL update query for each field
    if (isset($_POST['CampaignName'])) {
        $sqlUpdates[] = "CampaignName = '" . $_POST['CampaignName'] . "'";
    }
    if (isset($_POST['CampaignDescription'])) {
        $sqlUpdates[] = "CampaignDescription = '" . $_POST['CampaignDescription'] . "'";
    }
    if (isset($_POST['CampaignImageUrl'])) {
        $sqlUpdates[] = "CampaignImageUrl = '" . $_POST['CampaignImageUrl'] . "'";
    }
    if (isset($_POST['CampaignAmount'])) {
        $sqlUpdates[] = "CampaignAmount = " . $_POST['CampaignAmount'];
    }
     if (isset($_POST['CharityId'])) {
        $sqlUpdates[] = "CharityId = " . $_POST['CharityId'];
    }

    // Construct the SQL update string
    $sqlUpdateString = implode(", ", $sqlUpdates);

    // Update campaign in the database
    if (!empty($sqlUpdateString)) {
        $sql = "UPDATE campaigns SET $sqlUpdateString WHERE CampaignId = $campaignId";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(['message' => 'Campaign updated successfully']);
        } else {
            echo json_encode(['error' => 'Error updating campaign: ' . $conn->error]);
        }
    } else {
        echo json_encode(['error' => 'No fields to update provided']);
    }
} else {
    echo json_encode(['error' => 'Invalid request']);
}

// Close the connection
$conn->close();
?>
