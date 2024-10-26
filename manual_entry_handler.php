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

$barcode = $_POST['barcode'];
$entryTime = $_POST['entry_time'];
$exitTime = $_POST['exit_time'];
$libType = $_POST['lib-type'];
$dept = $_POST['dept'];
$desg = $_POST['desg'];

// Check for the barcode in the stud_tr table
$userQuery = "SELECT id, name,desg,dept FROM stud_tr WHERE id = '$barcode'";
$userResult = $conn->query($userQuery);

if ($userResult->num_rows > 0) {
    // Fetch user details
    $user = $userResult->fetch_assoc();
    $username = $user['name'];

    // Check for an existing entry with NULL exit_time
    $existingEntryQuery = "SELECT * FROM users WHERE barcode = '$barcode' AND exit_time IS NULL AND login_type = '$libType'";
    $existingEntryResult = $conn->query($existingEntryQuery);

    if ($existingEntryResult->num_rows > 0) {
        // Existing entry found, update the exit time if provided
        if (!empty($exitTime)) {
            // Update exit time
            $updateSql = "UPDATE users SET exit_time = '$exitTime' WHERE barcode = '$barcode' AND exit_time IS NULL AND login_type = '$libType'";
            if ($conn->query($updateSql) === TRUE) {
                echo "<script>alert('Exit time updated successfully'); window.location.href='manual_entry.html';</script>";
            } else {
                echo "<script>alert('Error updating exit time: " . $conn->error . "'); window.location.href='manual_entry.html';</script>";
            }
        } else {
            echo "<script>alert('Exit time not provided. Existing entry remains unchanged.'); window.location.href='manual_entry.html';</script>";
        }
    } else {
        // No existing entry, so insert a new record
        $insertSql = "INSERT INTO users (barcode, username, entry_time, exit_time, login_type, desg, dept) VALUES ('$barcode', '$username', '$entryTime', NULL, '$libType', '$desg', '$dept')";
        if ($conn->query($insertSql) === TRUE) {
            echo "<script>alert('Record added successfully'); window.location.href='manual_entry.html';</script>";
        } else {
            echo "<script>alert('Error adding record: " . $conn->error . "'); window.location.href='manual_entry.html';</script>";
        }
    }
} else {
    echo "<script>alert('Barcode not found in the system'); window.location.href='manual_entry.html';</script>";
}

$conn->close();
?>
