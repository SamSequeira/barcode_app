<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "barcode_appdb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode([
        'success' => false,
        'message' => 'Connection failed: ' . $conn->connect_error
    ]));
}

// Read the query parameters
$type = $_GET['type'];

// Validate and sanitize the input
$type = $conn->real_escape_string($type);

// Fetch entries based on the type
$sql = "SELECT * FROM users WHERE login_type = '$type' ORDER BY id DESC";
$result = $conn->query($sql);

$entries = [];
while ($row = $result->fetch_assoc()) {
    $entries[] = $row;
}

echo json_encode($entries);

$conn->close();
?>
