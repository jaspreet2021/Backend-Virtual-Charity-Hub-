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
    // Check if 'id' parameter is provided
    if(isset($_GET['id'])) {
        // Get charity by ID
        $id = $_GET['id'];
        
        // Use prepared statement to prevent SQL injection
        $sql = "SELECT * FROM charities WHERE Isdeleted = 0 AND CharityId = ?";
        
        // Prepare and execute the SQL query
        $stmt = mysqli_prepare($conn, $sql);
        
        // Bind parameters
        mysqli_stmt_bind_param($stmt, "i", $id);
        
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($result->num_rows > 0) {
            $charity = $result->fetch_assoc();
            header("Access-Control-Allow-Origin: http://localhost:4200");
            echo json_encode($charity);
        } else {
            header("Access-Control-Allow-Origin: http://localhost:4200");
            echo json_encode(['error' => 'Charity not found']);
        }
    } else {
        // Initialize the SQL query
        $sql = "SELECT * FROM charities WHERE Isdeleted = 0";

        // Check if status parameter is provided
        if(isset($_GET['status'])) {
            // Use prepared statement to prevent SQL injection
            $status = $_GET['status'];
            $sql .= " AND Status = ?";
        }

        // Prepare and execute the SQL query
        $stmt = mysqli_prepare($conn, $sql);

        // Bind parameters if status is provided
        if(isset($status)) {
            mysqli_stmt_bind_param($stmt, "s", $status);
        }

        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result->num_rows > 0) {
            $charities = array();

            while ($row = $result->fetch_assoc()) {
                $charities[] = $row;
            }
            header("Access-Control-Allow-Origin: http://localhost:4200");
            echo json_encode($charities);
        } else {
            header("Access-Control-Allow-Origin: http://localhost:4200");
            echo json_encode([]);
        }
    }
} else {
    header("Access-Control-Allow-Origin: http://localhost:4200");
    echo json_encode(['error' => 'Invalid request']);
}

// Close the connection
$conn->close();
?>
