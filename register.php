<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "virtual_charity_hub";
$conn = mysqli_connect($servername, $username, $password, $dbname);


if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function sendVerificationEmail($email, $token, $lastInsertedId,$conn) {
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
    $mail->AddAddress($email);
    $mail->SetFrom("virtualcharityhub@gmail.com", "Virtual Charity Hub");
    $mail->Subject = "Email Verification for Virtual Charity Hub";
    $mail->Body = getEmailContent($conn, $lastInsertedId, $token);

    // Send the email
    if (!$mail->send()) {
        // Handle email sending failure
        return false;
    } else {
        // Email sent successfully
        return true;
    }
}
function getEmailContent($conn, $lastInsertedId, $token) {
    // Load the HTML template
    $template = file_get_contents('email_template.html');
    
    // Query to fetch the user's name from the database
    $sql = "SELECT Name FROM users WHERE Id = $lastInsertedId";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $name = $row['Name'];
    } else {
        // Default name if user not found or query fails
        $name = "User";
    }

    // Replace placeholders with actual values
    $template = str_replace('[User]', $name, $template);
    $verificationLink = "http://localhost:8080/Backend-Virtual-Charity-Hub-/verification.php?id=$lastInsertedId&code=$token";
    $template = str_replace('[VerificationLink]', $verificationLink, $template);

    return $template;
}


 
// Function to sanitize and validate input data
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Function to hash the password
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}


$conn = mysqli_connect($servername, $username, $password, $dbname);


if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to send email verification


// Function to handle user registration
function registerUser($conn, $name, $email, $password, $phone, $role) {
    $name = sanitizeInput($name);
    $email = sanitizeInput($email);
    $password = sanitizeInput($password);
    $phone = sanitizeInput($phone);
    $role = sanitizeInput($role);


    // Check if the email already exists
    $sqlchk = "SELECT COUNT(*) as count FROM users WHERE Email = '$email'";
    $result = mysqli_query($conn, $sqlchk);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        
        // If email exists, return an error response
        if ($row['count'] > 0) {
            echo json_encode(array("success" => false, "message" => "Email Already Registered"));
            return false;
        }
    } else {
        return false;
    }

    // Hash the password
    $hashedPassword = hashPassword($password);
    $token=substr(md5(mt_rand()),0,15);

    // Insert user data into the 'users' table
    $sql = "INSERT INTO users (Name, Email, Password, Phone, Role, EmailVerified, EmailAuthToken) 
            VALUES ('$name', '$email', '$hashedPassword', '$phone', '$role', 0, '$token')";

    if (mysqli_query($conn, $sql)) {
        $lastInsertedId = mysqli_insert_id($conn);

         sendVerificationEmail($email, $token, $lastInsertedId,$conn);
    

         return $lastInsertedId; // Return the user ID
        } else {
        return false; // Registration failed
    }
}


// Check if the registration API endpoint is called
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get data from the request
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $phone = $_POST["phone"];
    $role = $_POST["role"];

    

    // Call the registerUser function
    $registrationResult = registerUser($conn, $name, $email, $password, $phone, $role);

    // Send a JSON response
    header("Content-Type: application/json");
    header("Access-Control-Allow-Origin: http://localhost:4200");

    if ($registrationResult) {
        echo json_encode(array("success" => true, "message" => "Registration successful", "userId" => $registrationResult));
    } else {
        echo json_encode(array("success" => false, "message" => "Registration failed"));
    }
} else {
    // Send an error response if the request method is not POST
    header("Content-Type: application/json");
    header("Access-Control-Allow-Origin: http://localhost:4200");
    echo json_encode(array("success" => false, "message" => "Invalid request method"));
}

// Close the database connection
mysqli_close($conn);

?> 