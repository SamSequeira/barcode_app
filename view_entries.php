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
    <title>View Entries</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <style>
        body {
            background-color: #343a40; /* Dark background */
            color: #ffffff; /* Light text color */
        }
        .container {
            margin-top: 20px;
        }
        .img-container {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
        }
        .img-btn {
            cursor: pointer;
            width: 300px;
            height: auto;
        }
        .button-container {
            text-align: center;
            margin-top: 10px;
        }
        .hidden {
            display: none;
        }
        .back-btn {
            margin-top: 20px;
        }
        .pagination-container {
            text-align: center;
            margin-top: 20px;
        }
        h1 {
            margin-bottom: 40px;
        }
        /* Custom styles for DataTable */
        #entryTable {
            background-color: #212529;
            border-radius: 8px;
            overflow: hidden;
        }
        #entryTable th {
            background-color: #007bff;
            color: #ffffff;
            text-align: center;
        }
        #entryTable td {
            text-align: center;
            border: 1px solid #495057;
            color: #ffffff;
        }
        #entryTable tbody tr:hover {
            background-color: #495057;
        }
        #entryTable tbody tr:nth-child(even) {
            background-color: #343a40;
        }
        #entryTable tbody tr:nth-child(odd) {
            background-color: #212529;
        }
        .dataTables_filter input {
            background-color: #495057;
            color: #ffffff;
            border: 1px solid #007bff;
            border-radius: 4px;
            padding: 5px;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            color: #ffffff;
            background: #007bff;
            border-radius: 4px;
            padding: 5px 10px;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #0056b3;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current { 
            background: #0056b3;
        }
        .dataTables_wrapper .dataTables_filter label { color: #f8f9fa; }
        .dataTables_wrapper .dataTables_length label { color: #f8f9fa; }
        .dataTables_wrapper .dataTables_info { color: #f8f9fa; }
        .dataTables_wrapper .dataTables_empty { color: #f8f9fa; }
        .btn-custom { margin: 10px; }
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
            <!-- Breadcrumb Navigation -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dashboard.php" class="text-black">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="view_entries.php" class="text-black">View Entries</a></li>
                    <li class="breadcrumb-item active" aria-current="page" id="breadcrumb-type">Select Library</li>
                </ol>
            </nav>
            <div id="selection">
                <h1 class="text-center">View Entries</h1>
                <div class="img-container">
                    <div>
                        <img src="https://blogs.slv.vic.gov.au/wp-content/uploads/2013/08/Bookself_AC.jpg" alt="Physical Library" style="width:400px;" class="img-btn" onclick="showEntries('Physical')">
                        <div class="button-container">
                            <button class="btn btn-primary" onclick="showEntries('Physical')">Physical Library</button>
                        </div>
                    </div>
                    <div>
                        <img src="https://www.littlelives.org.uk/wp-content/uploads/2021/11/Technology-Donations-Programme-600x479.jpg" alt="Digital Lab Library" style="width:400px;" class="img-btn" onclick="showEntries('Digital')">
                        <div class="button-container">
                            <button class="btn btn-primary" onclick="showEntries('Digital')">Digital Library</button>
                        </div>
                    </div>
                </div>
            </div>
            <div id="entries" class="hidden">
                <div class="d-flex justify-content-between" style="margin-bottom: 20px;">
                    <button class="btn btn-secondary back-btn" onclick="goBack()">Back</button>
                    <div>
                        <button class="btn btn-success" onclick="downloadEntries()">Download Entries</button>
                    </div>
                </div>
                <table class="table table-striped" id="entryTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Barcode</th>
                            <th>Username</th>
                            <th>Designation</th>
                            <th>Entry Time</th>
                            <th>Exit Time</th>
                            <th>Mins</th>
                        </tr>
                    </thead>
                    <tbody id="entryTableBody">
                        <!-- Table rows will be populated here -->
                    </tbody>
                </table>
                <div class="pagination-container" id="pagination">
                    <!-- Pagination controls will be populated here -->
                </div>
            </div>
        </div>
    </div>
    <script>
        let currentPage = 1;
        const entriesPerPage = 7;
        let entriesData = [];

        function showEntries(type) {
            document.getElementById('breadcrumb-type').textContent = type + ' Library';
            document.getElementById('selection').classList.add('hidden');
            document.getElementById('entries').classList.remove('hidden');
            fetchEntries(type);
        }

        function goBack() {
            document.getElementById('selection').classList.remove('hidden');
            document.getElementById('entries').classList.add('hidden');
        }

        function fetchEntries(type) {
            fetch(`fetch_entries.php?type=${type}`)
                .then(response => response.json())
                .then(data => {
                    // Sort entries by entry_time in descending order
                    entriesData = data.sort((a, b) => new Date(b.entry_time) - new Date(a.entry_time));
                    currentPage = 1;
                    renderTable();
                });
        }

        function renderTable() {
            const tableBody = document.getElementById('entryTableBody');
            tableBody.innerHTML = ''; // Clear existing data
            entriesData.forEach(entry => {
                const mins = calculateMinutes(entry.entry_time, entry.exit_time);
                const formattedEntryTime = new Date(entry.entry_time).toLocaleString(); // Format entry time
                const formattedExitTime = new Date(entry.exit_time).toLocaleString(); // Format entry time
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${entry.id}</td>
                    <td>${entry.barcode}</td>
                    <td>${entry.username}</td>
                    <td>${entry.desg}</td>
                    <td>${formattedEntryTime}</td> <!-- Use formatted entry time -->
                    <td>${formattedExitTime}</td>
                    <td>${mins !== null ? mins : 'N/A'}</td>
                `;
                tableBody.appendChild(row);
            });
            // Initialize DataTable
            $('#entryTable').DataTable({
                "order": [[3, "desc"]], // Ensure the entry_time column (index 3) is sorted in descending order
                "paging": true,
                "searching": true
            });
        }


        function calculateMinutes(entryTime, exitTime) {
            if (!exitTime) return null;
            const entryDate = new Date(entryTime);
            const exitDate = new Date(exitTime);
            return Math.round((exitDate - entryDate) / 60000); // Difference in minutes
        }


        function downloadEntries() {
        const type = document.getElementById('breadcrumb-type').textContent.replace(' Library', '');
        window.location.href = `download_entries.php?type=${type}`;
        }
    </script>
</body>
</html>
