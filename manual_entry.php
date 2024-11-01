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
    <title>Manual Entry</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        body { background-color: #f8f9fa; }
        .manual-entry { margin-top: 20px; }
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
        <div class="main-content container">
            <h1 class="text-center"><u>Manual Entry</u></h1>
            <div class="manual-entry">
                <form action="manual_entry_handler.php" method="POST">
                    <div class="form-group">
                        <label for="barcode">Barcode</label>
                        <input type="text" class="form-control" id="barcode" name="barcode" placeholder="Roll number" required>
                    </div>
                    <div class="form-group">
                        <label for="entry_time">Entry Time</label>
                        <input type="datetime-local" class="form-control" id="entry_time" name="entry_time">
                    </div>
                    <div class="form-group">
                        <label for="exit_time">Exit Time</label>
                        <input type="datetime-local" class="form-control" id="exit_time" name="exit_time">
                    </div>
                    <div class="form-group">
                        <label for="lib-type">Library Type</label>
                        <select name="lib-type" id="lib-type" class="form-control">
                            <option value="physical">Physical</option>
                            <option value="digital">Digital</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="desg">Designation</label>
                        <select name="desg" id="desg" class="form-control" required>
                            <option value="Student">Student</option>
                            <option value="Staff">Staff</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="dept">Department</label>
                        <select name="dept" id="dept" class="form-control" required>
                            <option value="COMP">COMP</option>
                            <option value="ECOMP">ECOMP</option>
                            <option value="COMP">ECE</option>
                            <option value="ECOMP">MAE</option>
                            <option value="MECH">MBA</option>
                            <option value="MECH">MECH</option>
                        </select>                    
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Submit</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
