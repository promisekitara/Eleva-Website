<?php
// File: production/api/upload.php
// This is an API endpoint for handling file uploads.
// It receives a file via POST request, validates it, saves it to a directory,
// and records its details in the database.

// Set the response header to JSON to ensure the client knows how to interpret the response.
header('Content-Type: application/json');

// Include the database connection file. The path assumes a structure like:
// /production/
//   - /api/
//     - upload.php (this file)
//   - /config/
//     - database.php
require_once __DIR__ . '/../config/database.php';

// Define the directory where uploaded files will be stored.
// The path is relative to the location of this script.
// Ensure this directory exists and has write permissions (e.g., chmod 775).
$uploadDirectory = __DIR__ . '/../uploads/';

// Function to send a JSON response and exit the script.
function sendJsonResponse($status, $message, $filePath = null) {
    $response = ['status' => $status, 'message' => $message];
    if ($filePath !== null) {
        $response['filePath'] = $filePath;
    }
    echo json_encode($response);
    exit();
}

// Ensure the request is a POST request.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJsonResponse('error', 'Invalid request method. Only POST is allowed.');
}

// Check if a file was uploaded and there were no upload errors.
if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    // Handle different upload errors.
    switch ($_FILES['file']['error']) {
        case UPLOAD_ERR_NO_FILE:
            sendJsonResponse('error', 'No file was uploaded.');
            break;
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            sendJsonResponse('error', 'The uploaded file is too large.');
            break;
        default:
            sendJsonResponse('error', 'An unknown upload error occurred.');
            break;
    }
}

// Get file details.
$fileTmpPath = $_FILES['file']['tmp_name'];
$fileName = $_FILES['file']['name'];
$fileSize = $_FILES['file']['size'];
$fileType = $_FILES['file']['type'];
$fileNameCmps = explode('.', $fileName);
$fileExtension = strtolower(end($fileNameCmps));

// --- Security and Validation ---

// Sanitize the filename to prevent path traversal and other issues.
$sanitizedFileName = preg_replace("/[^a-zA-Z0-9.\-]/", "", $fileName);

// Generate a unique filename to avoid overwrites and a more secure file path.
$newFileName = md5(time() . $fileName) . '.' . $fileExtension;
$destPath = $uploadDirectory . $newFileName;

// Define allowed file types. This is a crucial security step.
$allowedFileTypes = ['image/jpeg', 'image/png', 'application/pdf'];
if (!in_array($fileType, $allowedFileTypes)) {
    sendJsonResponse('error', 'Invalid file type. Only JPEG, PNG, and PDF are allowed.');
}

// Define allowed file extensions.
$allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
if (!in_array($fileExtension, $allowedExtensions)) {
    sendJsonResponse('error', 'Invalid file extension.');
}

// Define a maximum file size (e.g., 5MB).
$maxFileSize = 5 * 1024 * 1024; // 5 MB in bytes
if ($fileSize > $maxFileSize) {
    sendJsonResponse('error', 'File size exceeds the 5MB limit.');
}

// --- File Handling and Database Insertion ---

// Move the file from its temporary location to the final destination.
if (move_uploaded_file($fileTmpPath, $destPath)) {
    // File upload was successful. Now, save the details to the database.
    try {
        // Assumed 'files' table structure:
        // CREATE TABLE files (
        //     id INT AUTO_INCREMENT PRIMARY KEY,
        //     original_name VARCHAR(255) NOT NULL,
        //     unique_name VARCHAR(255) NOT NULL,
        //     file_path VARCHAR(255) NOT NULL,
        //     file_type VARCHAR(50) NOT NULL,
        //     file_size INT NOT NULL,
        //     upload_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        // );

        $stmt = $conn->prepare("INSERT INTO files (original_name, unique_name, file_path, file_type, file_size) VALUES (?, ?, ?, ?, ?)");
        if ($stmt === false) {
            throw new Exception("MySQLi prepare failed: " . $conn->error);
        }
        
        // Bind parameters. 'sssis' indicates two strings, one integer, and one string.
        $stmt->bind_param("sssii", $fileName, $newFileName, $destPath, $fileType, $fileSize);
        
        if ($stmt->execute()) {
            $stmt->close();
            sendJsonResponse('success', 'File uploaded and saved to the database successfully!', '/uploads/' . $newFileName);
        } else {
            $stmt->close();
            throw new Exception("Execute failed: " . $stmt->error);
        }
     } catch (Exception $e) {
        // If the database insert fails, attempt to delete the uploaded file to avoid orphaned files.
        if (file_exists($destPath)) {
            unlink($destPath);
        }
        sendJsonResponse('error', 'Database error: ' . $e->getMessage());
    }
} else {
    sendJsonResponse('error', 'An error occurred while moving the file.');
}

// Ensure the database connection is closed.
if ($conn) {
    $conn->close();
}
?>
