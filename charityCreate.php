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

// API endpoint to create a charity
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['CharityName'], $_POST['CharityDescription'], $_POST['CharityLocation'], $_POST['UserId'])) {
    $charityName = $_POST['CharityName'];
    $charityDescription = $_POST['CharityDescription'];
    $charityLocation = $_POST['CharityLocation'];
    $userId = $_POST['UserId'];
    $Status = isset($_POST['Status']) ? $_POST['Status'] : 0;


    // File uploads
    $charityImage = $_FILES['CharityImage'];
    $charityDocument = $_FILES['CharityDocument'];

    // Move uploaded files to a desired location
    $imageUploadPath = 'uploads/images/';
    $documentUploadPath = 'uploads/documents/';

    $charityImageName = $imageUploadPath . basename($charityImage['name']);
    $charityDocumentName = $documentUploadPath . basename($charityDocument['name']);

    move_uploaded_file($charityImage['tmp_name'], $charityImageName);
    move_uploaded_file($charityDocument['tmp_name'], $charityDocumentName);

    // Insert charity into the database
    $sql = "INSERT INTO charities (CharityName, CharityDescription, CharityImageUrl, DocumentUrl, Status , CharityLocation, UserId)
            VALUES ('$charityName', '$charityDescription', '$charityImageName', '$charityDocumentName', '$Status','$charityLocation', $userId)";

    if ($conn->query($sql) === TRUE) {
        $charityId = $conn->insert_id;
        echo json_encode(['message' => 'Charity created successfully', 'charityId' => $charityId]);
    } else {
        echo json_encode(['error' => 'Error creating charity: ' . $conn->error]);
    }
} else {
    echo json_encode(['error' => 'Invalid request']);
}

// Close the connection
$conn->close();
?>
