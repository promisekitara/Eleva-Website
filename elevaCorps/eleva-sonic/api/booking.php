<?php
// File: eleva-production/api/booking.php
// This is an API endpoint for handling booking requests for artists.
// It receives POST data from a form, validates it, and saves the booking to the database.

// Set the response header to JSON to ensure the client knows how to interpret the response.
header('Content-Type: application/json');

// Include the database connection file from the parent directory.
require_once __DIR__ . '/../../config/database.php';

// Function to send a JSON response and exit the script.
function sendJsonResponse($status, $message, $data = null) {
    $response = ['status' => $status, 'message' => $message];
    if ($data !== null) {
        $response['data'] = $data;
    }
    echo json_encode($response);
    exit();
}

// Ensure the request is a POST request.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJsonResponse('error', 'Invalid request method. Only POST is allowed.');
}

// Get and sanitize POST data.
$artist_id = trim(htmlspecialchars($_POST['artist_id'] ?? ''));
$booker_name = trim(htmlspecialchars($_POST['booker_name'] ?? ''));
$booker_email = trim(htmlspecialchars($_POST['booker_email'] ?? ''));
$event_date = trim(htmlspecialchars($_POST['event_date'] ?? ''));
$event_details = trim(htmlspecialchars($_POST['event_details'] ?? ''));

// Basic validation.
if (empty($artist_id) || empty($booker_name) || empty($booker_email) || empty($event_date) || empty($event_details)) {
    sendJsonResponse('error', 'All fields are required.');
}

// Validate email format.
if (!filter_var($booker_email, FILTER_VALIDATE_EMAIL)) {
    sendJsonResponse('error', 'Invalid email address format.');
}

// Validate date format (assuming YYYY-MM-DD).
if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $event_date)) {
    sendJsonResponse('error', 'Invalid event date format. Please use YYYY-MM-DD.');
}

// Assuming a 'bookings' table exists with the following structure:
// CREATE TABLE bookings (
//     id INT AUTO_INCREMENT PRIMARY KEY,
//     artist_id INT,
//     booker_name VARCHAR(255),
//     booker_email VARCHAR(255),
//     event_date DATE,
//     event_details TEXT,
//     booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
//     FOREIGN KEY (artist_id) REFERENCES artists(id) ON DELETE CASCADE
// );

// Insert the booking into the database.
try {
    $stmt = $conn->prepare("INSERT INTO bookings (artist_id, booker_name, booker_email, event_date, event_details) VALUES (?, ?, ?, ?, ?)");
    if ($stmt === false) {
        throw new Exception("MySQLi prepare failed: " . $conn->error);
    }
    
    // Bind parameters to the statement to prevent SQL injection.
    $stmt->bind_param("issss", $artist_id, $booker_name, $booker_email, $event_date, $event_details);
    
    // Execute the statement.
    if ($stmt->execute()) {
        $stmt->close();
        sendJsonResponse('success', 'Booking request submitted successfully! We will contact you shortly.');
    } else {
        $stmt->close();
        throw new Exception("Execute failed: " . $stmt->error);
    }
} catch (Exception $e) {
    sendJsonResponse('error', 'Database error: ' . $e->getMessage());
} finally {
    // Close the database connection.
    $conn->close();
}

?>
