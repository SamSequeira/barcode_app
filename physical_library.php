<?php
// dashboard.php
session_start();

if (!isset($_SESSION['user_name'])) {
    header("Location: login.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Physical Library</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .nav-link {
            color: #ffffff; /* White color for nav links */
        }
        .nav-link:hover {
            background-color: #343a40; /* Darker background on hover */
        }
        .scanner-container {
            margin: 30px auto; /* Centering the scanner container */
            max-width: 600px; /* Max width for the scanner area */
            padding: 20px; /* Padding around content */
            background-color: #232323; /* Dark background for scanner area */
            border-radius: 10px; /* Rounded corners */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5); /* Subtle shadow */
        }
        h1 {
            color: #007bff; /* Primary color for the heading */
            font-weight: bold; /* Bold font for emphasis */
            text-align: center; /* Center the heading */
            margin-bottom: 30px; /* Space below heading */
        }
        #scanner-input {
            width: 100%; /* Full width for the input field */
            padding: 15px; /* Add padding for better appearance */
            font-size: 1.2em; /* Slightly larger text */
            border: 1px solid #444; /* Border for the input field */
            border-radius: 5px; /* Rounded corners */
            background-color: #333; /* Dark background for input */
            color: #ffffff; /* White text for input */
            margin-bottom: 20px; /* Space below input */
        }
        #scanner-input::placeholder {
            color: #aaaaaa; /* Lighter color for placeholder text */
        }
        #barcode-result {
            margin-top: 20px; 
            font-size: 1.5em; 
            color: #0d4ebf; /* Green color for scanned barcode */
            font-weight: bold; /* Bold text for emphasis */
        }
        #success-message {
            margin-top: 10px; 
            color: #28a745; /* Green color for success message */
            font-size: 1.2em; 
            font-weight: bold; /* Bold text for emphasis */
        }
        #error-message {
            margin-top: 10px; 
            color: #dc3545; /* Red color for error message */
            font-size: 1.2em; 
            font-weight: bold; /* Bold text for emphasis */
            display: none;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <div class="sidebar p-3">
            <h4>Library Management</h4>
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="physical_library.php">Physical Library</a></li>
                <li class="nav-item"><a class="nav-link" href="digital_library.php">Digital Lab Library</a></li>
                <li class="nav-item"><a class="nav-link" href="manual_entry.php">Manual Entry</a></li>
                <li class="nav-item"><a class="nav-link" href="view_entries.php">View Entries</a></li>
            </ul>
        </div>
        
        <div class="scanner-container">
            <h1>Physical Library</h1>
            <h2 class="my-4">Scan a Barcode</h2>
            <input type="text" id="scanner-input" placeholder="Scan Barcode Here" autofocus />
            <div id="barcode-result">Scanned Barcode: <span id="barcode"></span></div>
            <div id="success-message"></div>
            <div id="error-message"></div> <!-- New div for error messages -->
            <input type="hidden" id="login-type" value="Physical" />
        </div>
    </div>

    <script src="app.js"></script>
</body>
</html>
