<?php
// File: production/api/contact.php
// This is an API endpoint for handling contact form submissions.
// It receives POST data, validates it, and saves the message to the database.

// Set the response header to JSON to ensure the client knows how to interpret the response.
header('Content-Type: application/json');

// Include the database connection file. The path assumes a structure like:
// /production/
//   - /api/
//     - contact.php (this file)
//   - /config/
//     - database.php
require_once __DIR__ . '/../config/database.php';

// Function to send a JSON response and exit the script.
function sendJsonResponse($status, $message) {
    echo json_encode(['status' => $status, 'message' => $message]);
    exit();
}

// Ensure the request is a POST request.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJsonResponse('error', 'Invalid request method. Only POST is allowed.');
}

// Get and sanitize POST data.
$name = trim(htmlspecialchars($_POST['name'] ?? ''));
$email = trim(htmlspecialchars($_POST['email'] ?? ''));
$subject = trim(htmlspecialchars($_POST['subject'] ?? ''));
$message = trim(htmlspecialchars($_POST['message'] ?? ''));

// Basic validation for required fields.
if (empty($name) || empty($email) || empty($subject) || empty($message)) {
    sendJsonResponse('error', 'All fields are required.');
}

// Validate email format.
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    sendJsonResponse('error', 'Invalid email address format.');
}

// Assumed 'contact_messages' table structure:
// CREATE TABLE contact_messages (
//     id INT AUTO_INCREMENT PRIMARY KEY,
//     name VARCHAR(255) NOT NULL,
//     email VARCHAR(255) NOT NULL,
//     subject VARCHAR(255) NOT NULL,
//     message TEXT NOT NULL,
//     submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
// );

// Insert the contact message into the database using a prepared statement for security.
try {
    $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
    if ($stmt === false) {
        throw new Exception("MySQLi prepare failed: " . $conn->error);
    }
    
    // Bind parameters to the statement. The 'ssss' indicates four string parameters.
    $stmt->bind_param("ssss", $name, $email, $subject, $message);
    
    // Execute the statement.
    if ($stmt->execute()) {
        $stmt->close();
        sendJsonResponse('success', 'Your message has been sent successfully! We will get back to you soon.');
    } else {
        $stmt->close();
        throw new Exception("Execute failed: " . $stmt->error);
    }
} catch (Exception $e) {
    sendJsonResponse('error', 'Database error: ' . $e->getMessage());
} finally {
    // Close the database connection.
    if ($conn) {
        $conn->close();
    }
}
?>
