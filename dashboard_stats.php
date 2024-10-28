<?php
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

// Fetch total entries and exits
$totalEntriesSql = "SELECT COUNT(*) as total_entries FROM users WHERE date(entry_time) = curdate()";
$totalExitsSql = "SELECT COUNT(*) as total_exits FROM users WHERE date(exit_time) = curdate()";
$totalEntriesResult = $conn->query($totalEntriesSql);
$totalExitsResult = $conn->query($totalExitsSql);

if ($totalEntriesResult && $totalExitsResult) {
    $totalEntries = $totalEntriesResult->fetch_assoc()['total_entries'];
    $totalExits = $totalExitsResult->fetch_assoc()['total_exits'];
} else {
    $totalEntries = 0;
    $totalExits = 0;
}

// Fetch active users
$activeUsersSql = "SELECT COUNT(DISTINCT barcode) as active_users FROM users WHERE exit_time IS NULL";
$activeUsersResult = $conn->query($activeUsersSql);
$activeUsers = $activeUsersResult ? $activeUsersResult->fetch_assoc()['active_users'] : 0;

// Fetch recent activity
$recentActivitySql = "SELECT barcode, entry_time, mins FROM users ORDER BY mins ASC  LIMIT 5";
$recentActivityResult = $conn->query($recentActivitySql);
$recentActivity = [];
if ($recentActivityResult) {
    while ($row = $recentActivityResult->fetch_assoc()) {
        $recentActivity[] = $row;
    }
}

// Fetch user statistics
$totalUsersSql = "SELECT COUNT(DISTINCT barcode) as total_users FROM users";
$frequentVisitorsSql = "SELECT barcode, COUNT(*) as visits FROM users WHERE date(entry_time) = curdate() GROUP BY barcode ORDER BY visits DESC LIMIT 5";
$totalUsersResult = $conn->query($totalUsersSql);
$frequentVisitorsResult = $conn->query($frequentVisitorsSql);

$totalUsers = $totalUsersResult ? $totalUsersResult->fetch_assoc()['total_users'] : 0;
$frequentVisitors = [];
if ($frequentVisitorsResult) {
    while ($row = $frequentVisitorsResult->fetch_assoc()) {
        $frequentVisitors[] = "{$row['barcode']} - Visits: {$row['visits']}";
    }
}

// Fetch physical and digital entries
$physicalEntriesSql = "SELECT COUNT(*) as count FROM users WHERE login_type = 'Physical' AND date(entry_time) = curdate()";
$digitalEntriesSql = "SELECT COUNT(*) as count FROM users WHERE login_type = 'Digital' AND date(entry_time) = curdate()";
$physicalEntriesResult = $conn->query($physicalEntriesSql);
$digitalEntriesResult = $conn->query($digitalEntriesSql);

$physicalEntries = $physicalEntriesResult ? $physicalEntriesResult->fetch_assoc()['count'] : 0;
$digitalEntries = $digitalEntriesResult ? $digitalEntriesResult->fetch_assoc()['count'] : 0;

// Fetch alerts
$alerts = "Check for users who might have left without checking out.";

// Output JSON
echo json_encode([
    'total_entries' => $totalEntries,
    'total_exits' => $totalExits,
    'active_users' => $activeUsers,
    'recent_activity' => $recentActivity,
    'total_users' => $totalUsers,
    'frequent_visitors' => $frequentVisitors,
    'alerts' => $alerts,
    'physical_entries' => $physicalEntries,
    'digital_entries' => $digitalEntries
]);

$conn->close();
?>
