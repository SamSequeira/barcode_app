<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "barcode_appdb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve form data
$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];

// Check if the email exists in the database
$sql = "SELECT * FROM login WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Fetch user data
    $user = $result->fetch_assoc();

    // Verify the password
    if (password_verify($password, $user['password'])) {
        // Password matches, login successful
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        
        // Redirect to dashboard or some other protected page
        header("Location: dashboard.php");
        exit();
    } else {
        // Password doesn't match
        $_SESSION['login_error'] = "Incorrect password. Please try again.";
        header("Location: login.php");
        exit();
    }
} else {
    // No such email found
    $_SESSION['login_error'] = "No such email found.";
    header("Location: login.php");
    exit();
}

$conn->close();
