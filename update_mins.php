<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "barcode_appdb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Update query to calculate and store the difference in minutes
$updateSql = "UPDATE users
              SET mins = TIMESTAMPDIFF(MINUTE, entry_time, NOW())
              WHERE entry_time IS NOT NULL AND exit_time IS NULL";

if ($conn->query($updateSql) === TRUE) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => $conn->error]);
}

$conn->close();
?>
