<?php
// Connect to the database
$connection = mysqli_connect("localhost", "root", "", "barcode_appdb");

if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}
// Get the barcode and login type from the request
$inputData = json_decode(file_get_contents('php://input'), true);
$barcode = $inputData['barcode'];
$loginType = $inputData['loginType']; // Get login type 
$current_time = date("Y-m-d H:i:s");

// Check if there is an open entry for this barcode and login type
$checkQuery = "SELECT * FROM users WHERE barcode = '$barcode' AND login_type = '$loginType' AND exit_time IS NULL";
$checkResult = mysqli_query($connection, $checkQuery);

if (mysqli_num_rows($checkResult) > 0) {
    // There is an existing open entry, update the exit time
    $updateQuery = "UPDATE users SET exit_time = '$current_time' WHERE barcode = '$barcode' AND login_type = '$loginType' AND exit_time IS NULL";
    if (mysqli_query($connection, $updateQuery)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update exit time']);
    }
} else {
    // No open entry, create a new entry
    $query = "SELECT id, name, email,desg,dept FROM stud_tr WHERE id = '$barcode'";
    $result = mysqli_query($connection, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $username = $row['name'];
        $desg = $row['desg'];
        $dept = $row['dept'];

        // Insert the new entry record into the users table
        $insertQuery = "INSERT INTO users (barcode, username, entry_time, login_type,desg,dept) VALUES ('$barcode', '$username', '$current_time', '$loginType','$desg','$dept')";
        if (mysqli_query($connection, $insertQuery)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to insert entry time']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Barcode not found']);
    }
}

// Close the connection
mysqli_close($connection);
?>
