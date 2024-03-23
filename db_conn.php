<?php
$servername = "localhost";
$username = "root";
$password = "";

// Create connection
$conn = mysqli_connect($servername, $username, $password);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
echo "Connected successfully<br>";

// Database name
$db = "virtual_charity_hub";

// Check if the database "virtual_charity_hub" exists
if (!mysqli_select_db($conn, $db)) {
    // Create the database if it does not exist
    $sqlCreateDB = "CREATE DATABASE $db";
    
    if (mysqli_query($conn, $sqlCreateDB)) {
        echo "Database created successfully: $db<br>";
    } else {
        echo "Error creating database: " . mysqli_error($conn);
    }
} else {
    echo "Database already exists: $db<br>";
}

mysqli_select_db($conn, $db);

// Create users table
$tableName = "users";
$sqlCreateTableUsers = "CREATE TABLE IF NOT EXISTS $tableName (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(45),
    Email VARCHAR(45),
    Password VARCHAR(45),
    Phone VARCHAR(45),
    Role VARCHAR(45),
    EmailVerified TINYINT DEFAULT 1,
    EmailAuthToken VARCHAR(100),
    IsDeleted TINYINT DEFAULT 0
)";

if (mysqli_query($conn, $sqlCreateTableUsers)) {
    echo "Table '$tableName' created successfully<br>";
} else {
    echo "Error creating table '$tableName': " . mysqli_error($conn);
}

// Create charities table
$tableName = "charities";
$sqlCreateTableCharities = "CREATE TABLE IF NOT EXISTS $tableName (
    CharityId INT AUTO_INCREMENT PRIMARY KEY,
    CharityName VARCHAR(45),
    CharityDescription TEXT,
    CharityImageUrl TEXT,
    DocumentUrl TEXT,
    Status TINYINT,
    IsDeleted TINYINT DEFAULT 0,
    CharityLocation VARCHAR(45),
    UserId INT,
    FOREIGN KEY (UserId) REFERENCES users(Id)
)";

if (mysqli_query($conn, $sqlCreateTableCharities)) {
    echo "Table '$tableName' created successfully<br>";
} else {
    echo "Error creating table '$tableName': " . mysqli_error($conn);
}


// Create campaigns table
$tableName = "campaigns";
$sqlCreateTableCampaigns = "CREATE TABLE IF NOT EXISTS $tableName (
    CampaignId INT AUTO_INCREMENT PRIMARY KEY,
    CampaignName VARCHAR(45),
    CampaignDescription TEXT,
    CampaignImageUrl TEXT,
    CampaignAmount INT,
    Status TINYINT,
    IsDeleted TINYINT DEFAULT 0,
    CharityId INT,
    FOREIGN KEY (CharityId) REFERENCES charities(CharityId)
)";

if (mysqli_query($conn, $sqlCreateTableCampaigns)) {
    echo "Table '$tableName' created successfully<br>";
} else {
    echo "Error creating table '$tableName': " . mysqli_error($conn);
}

// Create paymentDetails table
$tableName = "paymentDetails";
$sqlCreateTablePaymentDetails = "CREATE TABLE IF NOT EXISTS $tableName (
    PaymentDetailsId INT AUTO_INCREMENT PRIMARY KEY,
    UserId INT,
    CardNumber VARCHAR(45),
    CardExpiry VARCHAR(10),
    CardCVV VARCHAR(10),
    AccountNumber INT,
    IFSCCode VARCHAR(50),
    FOREIGN KEY (UserId) REFERENCES users(Id)
)";

if (mysqli_query($conn, $sqlCreateTablePaymentDetails)) {
    echo "Table '$tableName' created successfully<br>";
} else {
    echo "Error creating table '$tableName': " . mysqli_error($conn);
}
// Create donations table


$tableName = "donations";
$sqlCreateTableDonations = "CREATE TABLE IF NOT EXISTS $tableName (
    DonationId INT AUTO_INCREMENT PRIMARY KEY,
    DonorId INT,
    CharityId INT NULL,
    CampaignId INT NULL,
    Amount INT,
    PaymentDate DATE,
    IsSuccess TINYINT,
    FOREIGN KEY (DonorId) REFERENCES users(Id),
    FOREIGN KEY (CharityId) REFERENCES charities(CharityId),
    FOREIGN KEY (CampaignId) REFERENCES campaigns(CampaignId)
)";

if (mysqli_query($conn, $sqlCreateTableDonations)) {
    echo "Table '$tableName' created successfully<br>";
} else {
    echo "Error creating table '$tableName': " . mysqli_error($conn);
}

?>
