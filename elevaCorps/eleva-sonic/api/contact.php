<?php
// File: api/booking.php
// API endpoint for handling booking requests (generalized for Eleva Corps).

header('Content-Type: application/json');
require_once __DIR__ . '/../config/database.php';

function sendJsonResponse($status, $message, $data = null) {
    $response = ['status' => $status, 'message' => $message];
    if ($data !== null) {
        $response['data'] = $data;
    }
    echo json_encode($response);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJsonResponse('error', 'Invalid request method. Only POST is allowed.');
}

// Get and sanitize POST data.
$service      = trim(htmlspecialchars($_POST['service'] ?? ''));
$name         = trim(htmlspecialchars($_POST['name'] ?? ''));
$email        = trim(htmlspecialchars($_POST['email'] ?? ''));
$phone        = trim(htmlspecialchars($_POST['phone'] ?? ''));
$event_date   = trim(htmlspecialchars($_POST['event_date'] ?? ''));
$message      = trim(htmlspecialchars($_POST['message'] ?? ''));

// Basic validation.
if (empty($service) || empty($name) || empty($email) || empty($event_date) || empty($message)) {
    sendJsonResponse('error', 'All fields are required.');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    sendJsonResponse('error', 'Invalid email address format.');
}

if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $event_date)) {
    sendJsonResponse('error', 'Invalid event date format. Please use YYYY-MM-DD.');
}

// Insert the booking into the database.
try {
    $stmt = $conn->prepare("INSERT INTO bookings (service, name, email, phone, date, message, status) VALUES (?, ?, ?, ?, ?, ?, 'pending')");
    if ($stmt === false) {
        throw new Exception("MySQLi prepare failed: " . $conn->error);
    }
    $stmt->bind_param("ssssss", $service, $name, $email, $phone, $event_date, $message);

    if ($stmt->execute()) {
        $stmt->close();
        sendJsonResponse('success', 'Booking request submitted successfully! We will contact you shortly.');
    } else {
        throw new Exception("Execute failed: " . $stmt->error);
    }
} catch (Exception $e) {
    sendJsonResponse('error', 'Database error: ' . $e->getMessage());
} finally {
    $conn->close();
}
?>