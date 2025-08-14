<?php
// File: admin/index.php
// Main admin dashboard page with dynamic data from the database.

session_start();

// Enable error reporting for debugging purposes
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Check if the user is logged in. If not, redirect to the login page.
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

// Include the database configuration file for connection details
require_once '../config/database.php';

// Get the logged-in username from the session
$username = $_SESSION['username'] ?? 'Admin';

// --- DATABASE QUERIES TO FETCH DYNAMIC DATA ---
$data = [
    'users' => ['count' => 0, 'latest' => []],
    'blog' => ['count' => 0, 'latest' => []],
    'reviews' => ['count' => 0, 'latest' => []],
    'partners' => ['count' => 0, 'latest' => []],
    'services' => ['count' => 0, 'latest' => []],
    'team' => ['count' => 0, 'latest' => []],
];

// Establish a database connection
$conn = null;
try {
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Function to safely fetch counts and latest items
    function fetchData($conn, $table, $orderByCol, $latestTitleCol, $latestDateCol = null) {
        $count_query = "SELECT COUNT(id) AS total FROM $table";
        $latest_query = "SELECT " . ($latestDateCol ? "$latestTitleCol, $latestDateCol" : "$latestTitleCol") . " FROM $table ORDER BY $orderByCol DESC LIMIT 5";
        
        $count_result = $conn->query($count_query);
        $count = $count_result ? $count_result->fetch_assoc()['total'] : 0;
        
        $latest_result = $conn->query($latest_query);
        $latest = [];
        if ($latest_result) {
            while ($row = $latest_result->fetch_assoc()) {
                $latest[] = $row;
            }
        }
        
        return ['count' => $count, 'latest' => $latest];
    }

    // Fetch data for all tables
    $data['users'] = fetchData($conn, 'users', 'created_at', 'username');
    $data['blog'] = fetchData($conn, 'blog', 'date_created', 'title', 'date_created');
    $data['reviews'] = fetchData($conn, 'reviews', 'date_created', 'client_name', 'date_created');
    $data['partners'] = fetchData($conn, 'partners', 'id', 'name');
    $data['services'] = fetchData($conn, 'services', 'id', 'service_name');
    $data['team'] = fetchData($conn, 'team', 'id', 'name');

    // Close the connection
    $conn->close();

} catch (Exception $e) {
    error_log("Database Error: " . $e->getMessage());
    $error_message = "An error occurred while fetching data.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ELEVA | Admin Dashboard</title>
    <!-- Include your external stylesheet -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /*
         * Dashboard Layout CSS
         */
        :root {
            --bg-color: #0e0e0e;
            --sidebar-bg: #1c1c1c;
            --main-bg: #121212;
            --text-color: #e0e0e0;
            --accent-color: #FFD700;
            --secondary-text: #b0b0b0;
            --border-color: #333;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            margin: 0;
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styling */
        .sidebar {
            width: 250px;
            background-color: var(--sidebar-bg);
            padding: 2rem 1.5rem;
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .sidebar-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .sidebar-header h1 {
            font-size: 1.5rem;
            color: var(--accent-color);
            font-weight: 700;
        }

        .sidebar-nav {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .sidebar-nav li {
            margin-bottom: 1rem;
        }
        .sidebar-nav > li > a {
            display: block;
            padding: 0.8rem 1rem;
            color: var(--secondary-text);
            text-decoration: none;
            border-radius: 8px;
            transition: background-color 0.2s, color 0.2s;
            font-weight: 500;
        }
        .sidebar-nav > li > a:hover, .sidebar-nav > li > a.active {
            background-color: var(--accent-color);
            color: var(--sidebar-bg);
        }

        /* Sub-menu styling for the dropdown */
        .dropdown-menu {
            display: none;
            list-style: none;
            padding: 0;
            margin: 0.5rem 0 0 1rem;
            border-left: 2px solid var(--accent-color);
        }
        .dropdown-menu a {
            padding: 0.5rem 1rem;
            font-size: 0.9em;
        }
        .dropdown-menu li {
            margin: 0;
        }
        
        /* Dropdown active state */
        .sidebar-nav > li.active .dropdown-menu {
            display: block;
        }
        .sidebar-nav .dropdown-toggle {
            cursor: pointer;
            position: relative;
        }
        .sidebar-nav .dropdown-toggle::after {
            content: '▼';
            font-size: 0.7em;
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            transition: transform 0.2s;
        }
        .sidebar-nav .dropdown-toggle.active::after {
            transform: translateY(-50%) rotate(180deg);
        }

        /* Main Content Area */
        .main-content {
            flex-grow: 1;
            padding: 2rem;
            background-color: var(--main-bg);
        }

        .dashboard-header {
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .dashboard-header h2 {
            font-size: 2rem;
            margin: 0;
        }
        .dashboard-header .welcome-text {
            font-size: 1.1rem;
            color: var(--secondary-text);
        }

        /* Dashboard Overview Cards */
        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }
        .card {
            background-color: var(--sidebar-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 16px rgba(0,0,0,0.3);
            text-align: center;
        }
        .card h3 {
            font-size: 1.25rem;
            margin-top: 0;
            color: var(--accent-color);
        }
        .card p {
            font-size: 2em;
            font-weight: 700;
            margin: 0;
        }
        .card p span {
            font-size: 0.5em;
            font-weight: 400;
            color: var(--secondary-text);
        }

        /* Recent Activity Section */
        .recent-activity {
            margin-top: 3rem;
        }
        .recent-activity h3 {
            font-size: 1.75rem;
            margin-bottom: 1.5rem;
            color: var(--text-color);
        }
        .activity-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }
        .activity-card {
            background-color: var(--sidebar-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 16px rgba(0,0,0,0.3);
        }
        .activity-card h4 {
            margin-top: 0;
            color: var(--accent-color);
            font-size: 1.2rem;
        }
        .activity-card ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .activity-card li {
            padding: 0.5rem 0;
            border-bottom: 1px solid var(--border-color);
        }
        .activity-card li:last-child {
            border-bottom: none;
        }
        .activity-card li span {
            display: block;
        }
        .activity-card li .item-title {
            color: var(--text-color);
            font-weight: 500;
        }
        .activity-card li .item-date {
            font-size: 0.8em;
            color: var(--secondary-text);
        }

        /* Logout Button */
        .logout-button {
            display: block;
            width: 100%;
            padding: 0.8rem;
            background-color: #FF6B6B;
            color: var(--sidebar-bg);
            border: none;
            border-radius: 8px;
            text-align: center;
            font-weight: 700;
            text-decoration: none;
            transition: background-color 0.2s, transform 0.1s;
        }
        .logout-button:hover {
            background-color: #e55b5b;
            transform: translateY(-2px);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }
            .sidebar {
                width: 100%;
                border-right: none;
                border-bottom: 1px solid var(--border-color);
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div>
            <div class="sidebar-header">
                <h1>Admin Panel</h1>
            </div>
            <ul class="sidebar-nav">
                <li><a href="index.php" class="active">Dashboard</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" onclick="toggleDropdown(this)">Content</a>
                    <ul class="dropdown-menu">
                        <li><a href="content_list.php?type=blog">Blog Posts</a></li>
                        <li><a href="content_list.php?type=reviews">Reviews</a></li>
                        <li><a href="content_list.php?type=services">Services</a></li>
                        <li><a href="content_list.php?type=partners">Partners</a></li>
                        <li><a href="content_list.php?type=team">Team</a></li>
                    </ul>
                </li>
                <li><a href="users.php">User Management</a></li>
                <li><a href="settings.php">Settings</a></li>
            </ul>
        </div>
        <div>
            <a href="logout.php" class="logout-button">Logout</a>
        </div>
    </div>
    <div class="main-content">
        <div class="dashboard-header">
            <h2>Dashboard</h2>
            <p class="welcome-text">Welcome, <?php echo htmlspecialchars($username); ?>!</p>
        </div>
        
        <?php if (isset($error_message)): ?>
            <div style="color: red; padding: 1rem; background-color: #331f1f; border: 1px solid #663333; border-radius: 8px; margin-bottom: 2rem;">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <div class="dashboard-cards">
            <div class="card">
                <h3>Total Users</h3>
                <p><?php echo $data['users']['count']; ?><span> users</span></p>
            </div>
            <div class="card">
                <h3>Total Blog Posts</h3>
                <p><?php echo $data['blog']['count']; ?><span> posts</span></p>
            </div>
            <div class="card">
                <h3>Total Reviews</h3>
                <p><?php echo $data['reviews']['count']; ?><span> reviews</span></p>
            </div>
            <div class="card">
                <h3>Total Partners</h3>
                <p><?php echo $data['partners']['count']; ?><span> partners</span></p>
            </div>
            <div class="card">
                <h3>Total Services</h3>
                <p><?php echo $data['services']['count']; ?><span> services</span></p>
            </div>
            <div class="card">
                <h3>Total Team Members</h3>
                <p><?php echo $data['team']['count']; ?><span> members</span></p>
            </div>
        </div>

        <div class="recent-activity">
            <h3>Recent Activity</h3>
            <div class="activity-list">
                <div class="activity-card">
                    <h4>Latest Blog Posts</h4>
                    <ul>
                        <?php if (empty($data['blog']['latest'])): ?>
                            <li><span class="item-title">No recent posts.</span></li>
                        <?php else: ?>
                            <?php foreach ($data['blog']['latest'] as $item): ?>
                                <li>
                                    <span class="item-title"><?php echo htmlspecialchars($item['title']); ?></span>
                                    <span class="item-date"><?php echo date("F j, Y", strtotime($item['date_created'])); ?></span>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>

                <div class="activity-card">
                    <h4>Latest Reviews</h4>
                    <ul>
                        <?php if (empty($data['reviews']['latest'])): ?>
                            <li><span class="item-title">No recent reviews.</span></li>
                        <?php else: ?>
                            <?php foreach ($data['reviews']['latest'] as $item): ?>
                                <li>
                                    <span class="item-title"><?php echo htmlspecialchars($item['client_name']); ?></span>
                                    <span class="item-date"><?php echo date("F j, Y", strtotime($item['date_created'])); ?></span>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
                
                <div class="activity-card">
                    <h4>New Users</h4>
                    <ul>
                        <?php if (empty($data['users']['latest'])): ?>
                            <li><span class="item-title">No new users.</span></li>
                        <?php else: ?>
                            <?php foreach ($data['users']['latest'] as $item): ?>
                                <li><span class="item-title"><?php echo htmlspecialchars($item['username']); ?></span></li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // JavaScript to handle the dropdown menu in the sidebar
        function toggleDropdown(element) {
            const parentLi = element.closest('li');
            parentLi.classList.toggle('active');
            element.classList.toggle('active');
        }
    </script>
</body>
</html>
