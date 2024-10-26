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

// Read the query parameters
$type = $_GET['type'];

// Validate and sanitize the input
$type = $conn->real_escape_string($type);

// Fetch entries based on the type
$sql = "SELECT * FROM users WHERE login_type = '$type' ORDER BY entry_time DESC";
$result = $conn->query($sql);

// Prepare CSV file
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="entries.csv"');

$output = fopen('php://output', 'w');

// Write column headings
fputcsv($output, ['ID', 'Barcode', 'Username', 'Entry Time', 'Exit Time']);

// Write data rows
while ($row = $result->fetch_assoc()) {
    fputcsv($output, $row);
}

fclose($output);
$conn->close();
exit();
?>
