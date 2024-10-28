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
    <title>Library Management Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <style>
                /* Add this in your CSS file or within a <style> tag */
        .logout-container {
            position: fixed; /* Stay in a fixed position on the screen */
            top: 30px; /* Distance from the top */
            right: 20px; /* Distance from the right */
            z-index: 1000; /* Ensure it's above other content */
        }

        .logout-btn {
            text-decoration: none;
            color: white;
            background-color: #f44336; /* A nice red color */
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
        }

        .logout-btn:hover {
            background-color: #d32f2f; /* Darker red on hover */
        }
    </style>
</head>
<body>
    <script> alert("Welcome, <?php echo $_SESSION['user_name']; ?>!");</script>
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
        <div class="main-content">
            <h1>📖 Welcome to the Library Entry and Exit Management System 🖥️</h1>
            <!-- Main content -->
            <div class="container mt-4">
                <div class="row">
                    <!-- First Column -->
                    <div class="col-md-8">
                        <div class="row">
                            <!-- Row 1 -->
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Entries and Exits Chart</h5>
                                        <canvas id="entriesExitsChart"></canvas>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">User Statistics</h5>
                                        <ul id="user-stats" class="list-unstyled">
                                            <li>Frequent Visitors: Loading...</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- Row 2 -->
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Physical vs Digital Entries</h5>
                                        <canvas id="physicalDigitalEntriesChart" class="chart-size-small"></canvas>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">User Statistics Pie Chart</h5>
                                        <canvas id="userStatsPieChart" class="chart-size-small"></canvas>
                                    </div>
                                </div>
                            </div>
                            <!-- Row 3 -->
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Alerts and Notifications</h5>
                                        <p id="alerts">Loading...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Second Column -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body" id="active-users-card">
                                <h5 class="card-title">Active Users</h5>
                                <p id="active-users">Loading...</p>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Recent Activity</h5>
                                <table id="recent-activity" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Barcode</th>
                                            <th>Entry Time</th>
                                            <th>Mins</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr><td colspan="2">Loading...</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Add this in your HTML where the user is logged in -->
    <!-- Add this inside your HTML body where you want the logout link -->
    <div class="logout-container">
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>

    <script>
        
        // Example of fetching and displaying statistics data
        function fetchDashboardData() {
            fetch('dashboard_stats.php')
                .then(response => response.json())
                .then(data => {
                    // Update Active Users
                    document.getElementById('active-users').innerText = data.active_users;

                    // Update User Statistics
                    const userStats = data.frequent_visitors.map(visitor => `<li>${visitor}</li>`).join('');
                    document.getElementById('user-stats').innerHTML = ` 
                        <li>Frequent Visitors:</li>
                        <ul>${userStats}</ul>
                    `;

                    // Update Alerts and Notifications
                    document.getElementById('alerts').innerText = data.alerts;

                    // Update Recent Activity Table
                    const recentActivityBody = document.querySelector('#recent-activity tbody');
                    recentActivityBody.innerHTML = data.recent_activity.length ? 
                        data.recent_activity.map(item => `
                            <tr>
                                <td>${item.barcode}</td>
                                <td>${item.entry_time}</td>
                                <td>${item.mins}</td>
                            </tr>
                        `).join('') :
                        '<tr><td colspan="2">No recent activity.</td></tr>';

                    // Render Charts
                    renderCharts(data);
                })
                .catch(error => console.error('Error fetching data:', error));
        }

        function renderCharts(data) {
            const ctxEntriesExits = document.getElementById('entriesExitsChart').getContext('2d');
            new Chart(ctxEntriesExits, {
                type: 'bar',
                data: {
                    labels: ['Entries', 'Exits'],
                    datasets: [{
                        label1: 'Entries',
                        label2: 'Exits',
                        data: [data.total_entries, data.total_exits],
                        backgroundColor: ['#4e73df', '#1cc88a'],
                        borderColor: ['#4e73df', '#1cc88a'],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            const ctxUserStatsPie = document.getElementById('userStatsPieChart').getContext('2d');
            new Chart(ctxUserStatsPie, {
                type: 'pie',
                data: {
                    labels: ['Total Users', 'Frequent Visitors'],
                    datasets: [{
                        label: 'User Statistics',
                        data: [data.total_users, data.frequent_visitors.length],
                        backgroundColor: ['#36a2eb', '#ff6384'],
                        borderColor: ['#36a2eb', '#ff6384'],
                        borderWidth: 1
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'right',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    if (label) {
                                        label += ': ' + context.raw;
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });

            const ctxPhysicalDigital = document.getElementById('physicalDigitalEntriesChart').getContext('2d');
            new Chart(ctxPhysicalDigital, {
                type: 'doughnut',
                data: {
                    labels: ['Physical Entries', 'Digital Entries'],
                    datasets: [{
                        label: 'Entries',
                        data: [data.physical_entries, data.digital_entries],
                        backgroundColor: ['#4e73df', '#36b9cc'],
                        borderColor: ['#ffffff', '#ffffff'],
                        borderWidth: 2
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'right',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    if (label) {
                                        label += ': ' + context.raw;
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });

        }
        function updateMinsPeriodically() {
            setInterval(() => {
                fetch('update_mins.php')
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === "success") {
                            console.log("Mins updated successfully!");
                        } else {
                            console.error("Error updating mins:", data.message);
                        }
                    })
                    .catch(error => console.error("Fetch error:", error));
            }, 5000); // Update every 5 secs
        }
        
        // Call the function on page load
        fetchDashboardData();
        updateMinsPeriodically();
    </script>
</body>
</html>
