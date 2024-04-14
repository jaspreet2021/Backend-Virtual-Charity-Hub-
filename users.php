

<?php
header("Access-Control-Allow-Origin: http://localhost:4200");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");


// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "virtual_charity_hub";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to create a new user
function createUser($conn, $name, $email, $password, $phone, $role)
{
    $emailVerified = 1; // Default value
    $emailAuthToken = ""; // Default value
    $sql = "INSERT INTO users (Name, Email, Password, Phone, Role, EmailVerified, EmailAuthToken) 
            VALUES ('$name', '$email', '$password', '$phone', '$role', '$emailVerified', '$emailAuthToken')";
    if ($conn->query($sql) === TRUE) {
        return $conn->insert_id;
    } else {
        return false;
    }
}

// Function to retrieve all users
function getAllUsers($conn)
{
    $sql = "SELECT * FROM users where IsDeleted=0";
    $result = $conn->query($sql);
    $users = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }
    return $users;
}

// Function to retrieve a user by ID
function getUserById($conn, $userId)
{
    $sql = "SELECT * FROM users WHERE Id = '$userId'";
    $result = $conn->query($sql);
    if ($result->num_rows == 1) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}

// Function to update a user by ID

// Function to delete a user by ID
function deleteUserById($conn, $userId)
{
    $sql = "UPDATE users set IsDeleted=1 WHERE Id= $userId ";
   if ($conn->query($sql) === true) {
         return true;
     } else {
         return false;
     }
}

if ($_SERVER["REQUEST_METHOD"] === 'OPTIONS') {
    header('HTTP/1.1 200 OK');
    header("Access-Control-Allow-Origin: http://localhost:4200");
    header("Access-Control-Allow-Methods: *");
    // exit();
} 
// Handle CRUD operations based on request method and parameters
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Create new user
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $phone = $_POST["phone"];
    $role = $_POST["role"];
    $hashedPassword = hashPassword($password);

    $result = createUser($conn, $name, $email,$hashedPassword, $phone, $role);
    if ($result !== false) {
        // User creation successful
        header("Content-Type: application/json");
        header("Access-Control-Allow-Origin: http://localhost:4200");

        // Return the user ID along with success message
        echo json_encode(array("success" => true, "userId" => $result, "message" => "User created successfully"));
    } else {
        // User creation failed
        header("Content-Type: application/json");
        header("Access-Control-Allow-Origin: http://localhost:4200");

        echo json_encode(array("success" => false, "message" => "User creation failed"));
    }
} elseif ($_SERVER["REQUEST_METHOD"] === "GET") {
    // Retrieve all users or user by ID
    if (isset($_GET["id"])) {
        $userId = $_GET["id"];
        $user = getUserById($conn, $userId);
        if ($user) {
            header("Content-Type: application/json");
            header("Access-Control-Allow-Origin: http://localhost:4200");
    
            echo json_encode($user);
        } else {
            header("Content-Type: application/json");
            header("Access-Control-Allow-Origin: http://localhost:4200");
    
            echo json_encode(array("success" => false, "message" => "User not found"));
        }
    } else {
        header("Content-Type: application/json");
        header("Access-Control-Allow-Origin: http://localhost:4200");

        $users = getAllUsers($conn);
        echo json_encode($users);
    }

} elseif ($_SERVER["REQUEST_METHOD"] === "DELETE") {
    // Delete user
    header("Content-Type: application/json");
    header("Access-Control-Allow-Origin: http://localhost:4200");
    $userId = $_GET["id"];    
    $result = deleteUserById($conn, $userId);

    if ($result) {
       
        echo json_encode(array("success" => true, "message" => "User deleted successfully"));
    } else {      

        echo json_encode(array("success" => false, "message" => "User deletion failed"));
    }
} else {
    header("Content-Type: application/json");
    header("Access-Control-Allow-Origin: http://localhost:4200");

    // Invalid request method
    echo json_encode(array("success" => false, "message" => "Invalid request method"));
}

function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

?>
