<?php
// Include necessary files
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

// Connect to the database
// Function to create a donation
function createDonation($donorId, $charityId, $campaignId, $amount, $IsSuccess) {
    // Connect to your database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "virtual_charity_hub";

    $conn = mysqli_connect($servername, $username, $password, $dbname);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $donorEmail = "";
    $sql = "SELECT Name, Email FROM users WHERE Id = $donorId";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $donorEmail = $row['Email'];
        $donorName = $row['Name'];
    }
    $paymentDate = date("Y-m-d H:i:s"); // Format: YYYY-MM-DD HH:MM:SS

    // Prepare the SQL statement
    $sql = "INSERT INTO donations (DonorId, CharityId, CampaignId, Amount, PaymentDate, IsSuccess) 
            VALUES ('$donorId', ";

    // Add the charity id value or NULL if not provided
    if ($charityId !== "") {
        $sql .= "'$charityId'";
    } else {
        $sql .= "NULL";
    }

    $sql .= ", ";

    // Add the campaign id value or NULL if not provided
    if ($campaignId !== "") {
        $sql .= "'$campaignId'";
    } else {
        $sql .= "NULL";
    }

    $sql .= ", '$amount', '$paymentDate', 1)";

    // Execute the SQL statement
    if (mysqli_query($conn, $sql)) {
        header("Access-Control-Allow-Origin: http://localhost:4200");
        mysqli_close($conn);
        sendDonationNotificationEmail($donorEmail, $donorName, $donorId, $charityId, $campaignId, $amount, $paymentDate); // Send email notification
        return true; // Donation created successfully
    } else {
        header("Access-Control-Allow-Origin: http://localhost:4200");
        mysqli_close($conn);
        return false; // Donation creation failed
    }
}

// Function to send donation notification email
function sendDonationNotificationEmail($donorEmail,$donorName,$donorId, $charityId,$campaignId, $amount, $paymentDate) {
    // Create a new PHPMailer instance
    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->Mailer = "smtp";
    $mail->SMTPDebug  = 0;  
    $mail->SMTPAuth   = TRUE;
    $mail->SMTPSecure = "tls";
    $mail->Port       = 587;
    $mail->Host       = "smtp.gmail.com";
    $mail->Username   = "virtualcharityhub@gmail.com";
    $mail->Password   = "mtqy bnkg elsb tcer"; 


    // Set email content and headers
    $mail->IsHTML(true);
    $mail->IsHTML(true);
    $mail->AddAddress($donorEmail);
    $mail->SetFrom("virtualcharityhub@gmail.com", "Virtual Charity Hub");
    $mail->Subject = "New Donation Received";
    $mail->Body = "Dear $donorName,<br><br>"
                . "We are incredibly grateful for your generous donation of $ $amount. Your support means the world to us and plays a crucial role in our mission."
                . "Your kindness and compassion inspire us every day, and we are honored to have you as a supporter of our cause. Together, we are making a difference and bringing hope to those in need.<br><br>"
                . "Thank you once again for your generosity and for believing in our mission. Your donation will help us create a brighter future for all.<br><br>"
                . "With heartfelt gratitude,<br>"
                . "Virtual Charity Hub";

    // Send the email
    $mail->send();
}

// Check if the donation creation API endpoint is called
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get data from the request
    $donorId = $_POST["donorId"];
    $charityId = isset($_POST["charityId"]) ? $_POST["charityId"] :"";
    $campaignId = isset($_POST["campaignId"]) ? $_POST["campaignId"] :"";
    $amount = $_POST["amount"];
    $IsSuccess = $_POST["IsSuccess"];
    $creationResult = createDonation($donorId, $charityId,$campaignId, $amount, $IsSuccess);

    // Send a JSON response
    header("Content-Type: application/json");

    if ($creationResult) {
        echo json_encode(array("success" => true, "message" => "Donation created successfully"));
    } else {
        echo json_encode(array("success" => false, "message" => "Donation creation failed"));
    }
} else {
    // Send an error response if the request method is not POST
    header("Content-Type: application/json");

    echo json_encode(array("success" => false, "message" => "Invalid request method"));
}
?>
