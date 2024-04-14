<?php
header("Access-Control-Allow-Origin: http://localhost:4200");
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
    // Initialize the SQL query
    $sql = "SELECT * FROM campaigns WHERE IsDeleted = 0";

    // Check if status parameter is provided
    if(isset($_GET['status'])) {
        // Use prepared statement to prevent SQL injection
        $status = $_GET['status'];
        $sql .= " AND Status = ?";
    }

    // Check if id parameter is provided
    if(isset($_GET['id'])) {
        $id = $_GET['id'];
        // Use prepared statement to prevent SQL injection
        $sql .= " AND CampaignId = ?";
    }

    // Check if CharityId parameter is provided
    if(isset($_GET['CharityId'])) {
        $charityId = $_GET['CharityId'];
        // Use prepared statement to prevent SQL injection
        $sql .= " AND CharityId = ?";
    }

    // Prepare and execute the SQL query
    $stmt = mysqli_prepare($conn, $sql);

    // Bind parameters if status is provided
    if(isset($status)) {
        mysqli_stmt_bind_param($stmt, "s", $status);
    }

    // Bind parameters if id is provided
    if(isset($id)) {
        mysqli_stmt_bind_param($stmt, "i", $id);
    }

    // Bind parameters if CharityId is provided
    if(isset($charityId)) {
        mysqli_stmt_bind_param($stmt, "i", $charityId);
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result->num_rows > 0) {
        $campaigns = array();
        // Fetch campaigns and store them in an array
        while ($row = $result->fetch_assoc()) {
            $campaigns[] = $row;
        }
        header("Access-Control-Allow-Origin: http://localhost:4200");
        echo json_encode($campaigns);
    } else {
        // If no campaigns found, return an empty array
        header("Access-Control-Allow-Origin: http://localhost:4200");
        echo json_encode([]);
    }
} else {
    header("Access-Control-Allow-Origin: http://localhost:4200");
    echo json_encode(['error' => 'Invalid request']);
}

// Close the connection
$conn->close();
?>
