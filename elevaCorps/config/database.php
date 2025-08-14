<?php
// File: config/database.php
// This file is responsible for establishing a connection to the MySQL database.

// --- Database Credentials ---
// Define constants for database connection details.
// You must replace these placeholder values with your actual database credentials.
define('DB_SERVER', 'localhost'); // The database server address, often 'localhost'
define('DB_USERNAME', 'root');      // Your database username
define('DB_PASSWORD', '');          // Your database password
define('DB_NAME', 'eleva-kreatives_db'); // The name of your database

// --- Attempt to connect to the database ---
// Create a new mysqli connection object.
// The @ symbol suppresses warnings, which is a common practice
// to handle connection errors in a more controlled way.
$conn = @new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// --- Check the connection ---
// If the connection was not successful, display an error message and terminate the script.
if ($conn->connect_error) {
    // In a production environment, you might want to log this error
    // instead of displaying it directly to the user for security reasons.
    die("ERROR: Could not connect to the database. " . $conn->connect_error);
}
// If the connection is successful, the script will continue and the $conn object
// will be available for other files that include this one.

?>
