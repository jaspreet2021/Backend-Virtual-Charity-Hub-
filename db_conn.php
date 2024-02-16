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
echo "Connected successfully";

// Database name
$db = "virtual_charity_hub";

// Check if the database "virtual_charity_hub" exists
if (!mysqli_select_db($conn, $db)) {
    // Create the database if it does not exist
    $sqlCreateDB = "CREATE DATABASE $db";
    
    if (mysqli_query($conn, $sqlCreateDB)) {
        echo "Database created successfully: $db";
    } else {
        echo "Error creating database: " . mysqli_error($conn);
    }
} else {
    echo "Database already exists: $db";
}

mysqli_select_db($conn, $db);

// Check if the "users" table already exists
$tableName = "users";
$sqlCheckTableUsers = "SHOW TABLES LIKE '$tableName'";
$resultCheckTableUsers = mysqli_query($conn, $sqlCheckTableUsers);

if (mysqli_num_rows($resultCheckTableUsers) > 0) {
    echo "Table '$tableName' already exists";
} else {
    // Define the SQL query to create the "users" table
    $sqlCreateTableUsers = "
        CREATE TABLE IF NOT EXISTS users (
            Id INT AUTO_INCREMENT PRIMARY KEY,
            Name VARCHAR(45),
            Email VARCHAR(45),
            Password VARCHAR(45),
            Phone VARCHAR(45),
            Role VARCHAR(45),
            EmailVerified TINYINT,
            EmailAuthToken VARCHAR(100)
        )
    ";

    // Execute the table creation query
    if (mysqli_query($conn, $sqlCreateTableUsers)) {
        echo "Table '$tableName' created successfully";
    } else {
        echo "Error creating table: " . mysqli_error($conn);
    }
}

// Check if the "charities" table already exists
$tableName = "charities";
$sqlCheckTableItems = "SHOW TABLES LIKE '$tableName'";
$resultCheckTableItems = mysqli_query($conn, $sqlCheckTableItems);

if (mysqli_num_rows($resultCheckTableItems) > 0) {
    echo "Table '$tableName' already exists";
} else {
    // Define the SQL query to create the "items" table
    $sqlCreateTableItems = "
        CREATE TABLE IF NOT EXISTS charities(
            CharityId INT AUTO_INCREMENT PRIMARY KEY,
            CharityName VARCHAR(45),
            CharityDescription TEXT,
            CharityImageUrl VARCHAR(45),
            DocumentUrl VARCHAR(45),
            IsApproved TINYINT,
            CharityLocation VARCHAR(45),
            UserId Int,
            FOREIGN KEY (UserId) REFERENCES users(Id)
        )
    ";

    // Execute the table creation query
    if (mysqli_query($conn, $sqlCreateTableItems)) {
        echo "Table '$tableName' created successfully";
    } else {
        echo "Error creating table: " . mysqli_error($conn);
    }
}

// Check if the "donations" table already exists
$tableName = "donations";
$sqlCheckTableDonations = "SHOW TABLES LIKE '$tableName'";
$resultCheckTableDonations = mysqli_query($conn, $sqlCheckTableDonations);

if (mysqli_num_rows($resultCheckTableDonations) > 0) {
    echo "Table '$tableName' already exists";
} else {
    // Define the SQL query to create the "donations" table
    $sqlCreateTableDonations = "
        CREATE TABLE IF NOT EXISTS donations (
            DonationId INT AUTO_INCREMENT PRIMARY KEY,
            DonorId INT,
            CharityId INT,
            Amount INT,
            PaymentDate DATE,
            IsSuccess TINYINT,
            FOREIGN KEY (DonorId) REFERENCES users(Id),
            FOREIGN KEY (CharityId) REFERENCES charities(CharityId)
        )
    ";

    // Execute the table creation query
    if (mysqli_query($conn, $sqlCreateTableDonations)) {
        echo "Table '$tableName' created successfully";
    } else {
        echo "Error creating table: " . mysqli_error($conn);
    }
}

$tableName = "campaigns";
$sqlCheckTableCampaigns = "SHOW TABLES LIKE '$tableName'";
$resultCheckTableCampaigns = mysqli_query($conn, $sqlCheckTableCampaigns);

if (mysqli_num_rows($resultCheckTableCampaigns) > 0) {
    echo "Table '$tableName' already exists";
} else {
    // Define the SQL query to create the "donations" table
    $sqlCreateTableCampaigns = "
        CREATE TABLE IF NOT EXISTS campaigns (
            CampaignId INT AUTO_INCREMENT PRIMARY KEY,
            CampaignName VARCHAR(45),
            CampaignDescription TEXT,
            CampaignImageUrl VARCHAR(100),
            CampaignAmount INT,
            IsApproved TINYINT,
            CharityId INT,
            FOREIGN KEY (CharityId) REFERENCES charities(CharityId)

        )
    ";

    // Execute the table creation query
    if (mysqli_query($conn, $sqlCreateTableCampaigns)) {
        echo "Table '$tableName' created successfully";
    } else {
        echo "Error creating table: " . mysqli_error($conn);
    }
}

$tableName = "paymentDetails";
$sqlCheckTablepaymentDetails = "SHOW TABLES LIKE '$tableName'";
$resultCheckTablepaymentDetails = mysqli_query($conn, $sqlCheckTablepaymentDetails);

if (mysqli_num_rows($resultCheckTablepaymentDetails) > 0) {
    echo "Table '$tableName' already exists";
} else {
    // Define the SQL query to create the "donations" table
    $sqlCreateTablepaymentDetails = "
        CREATE TABLE IF NOT EXISTS paymentDetails (
            PaymentDetailsId INT AUTO_INCREMENT PRIMARY KEY,
            UserId INT,
            CardNumber VARCHAR(45),
            CardExpiry VARCHAR(10),
            CardCVV VARCHAR(10),
            AccountNumber INT,
            IFSCCode VARCHAR(50),
            
            FOREIGN KEY (UserId) REFERENCES users(Id)

        )
    ";

    // Execute the table creation query
    if (mysqli_query($conn, $sqlCreateTablepaymentDetails)) {
        echo "Table '$tableName' created successfully";
    } else {
        echo "Error creating table: " . mysqli_error($conn);
    }
}


?>