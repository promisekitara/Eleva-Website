<?php
// File: eleva-sonic/api/upload.php
// This is an API endpoint for uploading audio files and saving track details to the database.
// It is designed to be called via a form submission, preferably using JavaScript/Fetch API.

// Set the response header to JSON to ensure the client knows how to interpret the response.
header('Content-Type: application/json');

// Include the database connection file from the parent directory.
require_once __DIR__ . '/../config/database.php';

// Define the base upload directory. Ensure this directory exists and is writable by the web server.
$upload_dir = __DIR__ . '/../uploads/tracks/';
// Create the directory if it doesn't exist
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

// Function to send a JSON response
function sendJsonResponse($status, $message) {
    echo json_encode(['status' => $status, 'message' => $message]);
    exit();
}

// Check if a file was uploaded
if (!isset($_FILES['audio_file']) || $_FILES['audio_file']['error'] !== UPLOAD_ERR_OK) {
    sendJsonResponse('error', 'No file uploaded or there was an upload error.');
}

// Get other form data
$title = $_POST['title'] ?? '';
$artist = $_POST['artist'] ?? '';
$album = $_POST['album'] ?? '';
$genre = $_POST['genre'] ?? '';

// Basic validation for form data
if (empty($title) || empty($artist) || empty($album) || empty($genre)) {
    sendJsonResponse('error', 'All fields (title, artist, album, genre) are required.');
}

// Process the uploaded file
$file_info = $_FILES['audio_file'];
$file_name = basename($file_info['name']);
$file_tmp_name = $file_info['tmp_name'];
$file_size = $file_info['size'];
$file_type = mime_content_type($file_tmp_name);

// Validate file type
$allowed_types = ['audio/mpeg', 'audio/wav', 'audio/x-wav'];
if (!in_array($file_type, $allowed_types)) {
    sendJsonResponse('error', 'Invalid file type. Only MP3 and WAV files are allowed.');
}

// Generate a unique filename to prevent overwriting existing files
$file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
$new_file_name = uniqid('track_', true) . '.' . $file_ext;
$destination_path = $upload_dir . $new_file_name;
$db_file_path = 'uploads/tracks/' . $new_file_name; // Path to store in the database

// Move the uploaded file from its temporary location to the destination directory
if (!move_uploaded_file($file_tmp_name, $destination_path)) {
    sendJsonResponse('error', 'Failed to move the uploaded file.');
}

// Prepare and bind a SQL query to insert the new track into the database
try {
    $stmt = $conn->prepare("INSERT INTO tracks (title, artist, album_title, genre, file_path) VALUES (?, ?, ?, ?, ?)");
    if ($stmt === false) {
        throw new Exception("MySQLi prepare failed: " . $conn->error);
    }
    
    // Bind parameters to the statement to prevent SQL injection
    $stmt->bind_param("sssss", $title, $artist, $album, $genre, $db_file_path);
    
    // Execute the statement
    if ($stmt->execute()) {
        $stmt->close();
        sendJsonResponse('success', 'Track uploaded successfully!');
    } else {
        $stmt->close();
        throw new Exception("Execute failed: " . $stmt->error);
    }
} catch (Exception $e) {
    // If an error occurred, remove the uploaded file to avoid orphaned files
    if (file_exists($destination_path)) {
        unlink($destination_path);
    }
    sendJsonResponse('error', 'Database error: ' . $e->getMessage());
} finally {
    // Close the database connection.
    $conn->close();
}
?>
