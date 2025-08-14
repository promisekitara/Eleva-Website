<?php
/**
 * Eleva Corps Functions Library
 * This file contains a collection of reusable functions for the website.
 */

// Include the database connection to use it in functions if needed
require_once 'database.php';

/**
 * Sanitizes a string input before outputting it to the browser.
 * This is crucial to prevent Cross-Site Scripting (XSS) attacks.
 * For preventing SQL injection, use prepared statements.
 * @param string $data The string to sanitize.
 * @return string The sanitized string.
 */
function sanitize_input($data) {
    // Remove whitespace from the beginning and end of the string
    $data = trim($data);
    // Remove backslashes
    $data = stripslashes($data);
    // Convert special characters to HTML entities to prevent XSS
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * Checks if a user is logged into the admin dashboard.
 * @return bool True if logged in, false otherwise.
 */
function is_logged_in() {
    // Check if the session variable 'loggedin' is set and is true
    return isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
}

/**
 * Redirects the user to a specified URL.
 * @param string $url The URL to redirect to.
 * @return void
 */
function redirect_to($url) {
    header("Location: $url");
    exit();
}

/**
 * Displays a formatted error message.
 * @param string $message The error message to display.
 * @return void
 */
function show_error_message($message) {
    echo "<div class='alert alert-danger'>{$message}</div>";
}

/**
 * Handles file uploads, validating file type and size.
 * @param array $file The $_FILES array for the uploaded file.
 * @param string $target_directory The directory where the file will be saved.
 * @param array $allowed_types An array of allowed file extensions (e.g., ['jpg', 'png', 'jpeg']).
 * @param int $max_size The maximum file size in bytes.
 * @return string|false Returns the new file name on success, false on failure.
 */
function upload_file($file, $target_directory, $allowed_types, $max_size) {
    $file_name = $file['name'];
    $file_tmp_name = $file['tmp_name'];
    $file_size = $file['size'];
    $file_error = $file['error'];

    // Check for upload errors
    if ($file_error !== 0) {
        show_error_message("File upload error: {$file_error}");
        return false;
    }

    // Check file size
    if ($file_size > $max_size) {
        show_error_message("File is too large. Maximum size is " . ($max_size / 1024 / 1024) . " MB.");
        return false;
    }

    // Get file extension
    $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    // Check if the file type is allowed
    if (!in_array($file_extension, $allowed_types)) {
        show_error_message("Invalid file type. Allowed types are: " . implode(', ', $allowed_types));
        return false;
    }

    // Generate a unique filename to prevent overwriting existing files
    $new_file_name = uniqid('', true) . "." . $file_extension;
    $target_file_path = $target_directory . $new_file_name;

    // Move the uploaded file to the target directory
    if (move_uploaded_file($file_tmp_name, $target_file_path)) {
        return $new_file_name;
    } else {
        show_error_message("There was an error moving the uploaded file.");
        return false;
    }
}
