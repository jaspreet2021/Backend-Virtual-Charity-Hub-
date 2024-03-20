<?php
// Include necessary files
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

// Connect to the database
// Function to create a donation
function createDonation($donorId, $charityId, $amount, $IsSuccess) {
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
    $sql = "SELECT Email FROM users WHERE Id = $donorId";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $donorEmail = $row['Email'];
    }
    $paymentDate = date("Y-m-d H:i:s"); // Format: YYYY-MM-DD HH:MM:SS

    // Prepare the SQL statement
    $sql = "INSERT INTO donations (DonorId, CharityId, Amount, PaymentDate, IsSuccess) 
            VALUES ('$donorId', '$charityId', '$amount', '$paymentDate', 1)";

    // Execute the SQL statement
    if (mysqli_query($conn, $sql)) {
        mysqli_close($conn);
        sendDonationNotificationEmail($donorEmail,$donorId, $charityId, $amount, $paymentDate); // Send email notification
        return true; // Donation created successfully
    } else {
        mysqli_close($conn);
        return false; // Donation creation failed
    }
}

// Function to send donation notification email
function sendDonationNotificationEmail($donorEmail,$donorId, $charityId, $amount, $paymentDate) {
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
    $mail->Subject = "Email Verification for Virtual Charity Hub";  $mail->Subject = "New Donation Received";
    $mail->Body = "A new donation has been received!\n\nDonor ID: $donorId\nCharity ID: $charityId\nAmount: $amount\nPayment Date: $paymentDate";

    // Send the email
    $mail->send();
}

// Check if the donation creation API endpoint is called
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get data from the request
    $donorId = $_POST["donorId"];
    $charityId = $_POST["charityId"];
    $amount = $_POST["amount"];
    $IsSuccess = $_POST["IsSuccess"];

    // Call the createDonation function
    $creationResult = createDonation($donorId, $charityId, $amount, $IsSuccess);

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
