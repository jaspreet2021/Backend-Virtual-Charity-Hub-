<?php
// Include necessary files
require 'vendor/autoload.php'; // Include PHPMailer autoloader

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Function to retrieve donations by a particular donor
function getDonationsByDonor($donorId) {
    // Connect to your database
    $servername = "localhost";
$username = "root";
$password = "";
$dbname = "virtual_charity_hub";

    $conn = mysqli_connect($servername, $username, $password, $dbname);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Prepare the SQL statement
    $sql = "SELECT donations.*, charities.CharityName AS CharityName, campaigns.CampaignName AS CampaignName 
            FROM donations 
            LEFT JOIN charities ON donations.CharityId = charities.CharityId 
            LEFT JOIN campaigns ON donations.CampaignId = campaigns.CampaignId
            WHERE donations.DonorId = '$donorId'";

    // Execute the SQL statement
    $result = mysqli_query($conn, $sql);

    // Check if there are any donations
    if (mysqli_num_rows($result) > 0) {
        $donations = array();
        // Fetch each row of the result as an associative array
        while ($row = mysqli_fetch_assoc($result)) {
            // Add the donation details to the donations array
            $donations[] = $row;
        }
        mysqli_close($conn);
        return $donations;
    } else {
        mysqli_close($conn);
        return null; // No donations found for the donor
    }
}

// Check if the donor ID is provided in the request
if (isset($_GET['donorId'])) {
    // Retrieve the donor ID from the request
    $donorId = $_GET['donorId'];

    // Get donations for the specified donor ID
    $donations = getDonationsByDonor($donorId);
    header("Content-Type: application/json");
    header("Access-Control-Allow-Origin: http://localhost:4200");

    // Check if donations are found for the donor
    if ($donations !== null) {
        // Donations found for the donor
        echo json_encode($donations);
    } else {
        // No donations found for the donor
        echo json_encode($donations);
    }
} else {
    header("Content-Type: application/json");
    header("Access-Control-Allow-Origin: http://localhost:4200");

    // Donor ID not provided in the request
    echo json_encode(array("message" => "Please provide the donor ID"));
}
?>
