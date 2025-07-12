<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die('Method not allowed');
}

// Get the submitted date, time, and location
$selectedDate = $_POST['selected_date'] ?? null;
$selectedTime = $_POST['selected_time'] ?? null;
$pickupLocation = $_POST['pickup_location'] ?? null;

// Validate the date
if (!$selectedDate) {
    http_response_code(400);
    die('Date is required');
}

// Validate date format
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $selectedDate)) {
    http_response_code(400);
    die('Invalid date format');
}

// Validate the time
if (!$selectedTime) {
    http_response_code(400);
    die('Time is required');
}

// Validate time format
if (!preg_match('/^\d{2}:\d{2}$/', $selectedTime)) {
    http_response_code(400);
    die('Invalid time format');
}

// Validate the pickup location
if (!$pickupLocation) {
    http_response_code(400);
    die('Pickup location is required');
}

// Sanitize the pickup location
$pickupLocation = trim($pickupLocation);
if (strlen($pickupLocation) < 3) {
    http_response_code(400);
    die('Pickup location must be at least 3 characters');
}

// Create logs directory if it doesn't exist
$logsDir = 'logs';
if (!is_dir($logsDir)) {
    mkdir($logsDir, 0755, true);
}

// Prepare log entry
$timestamp = date('Y-m-d H:i:s');

// Format log entry
$logEntry = sprintf(
    "[%s] Date: %s | Time: %s | Location: %s\n",
    $timestamp,
    $selectedDate,
    $selectedTime,
    $pickupLocation
);

// File path for the log file
$logFile = $logsDir . '/date_submissions.log';

// Append the log entry to the file
$saveResult = file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);

if ($saveResult === false) {
    http_response_code(500);
    die('Failed to save date');
}

// Redirect to success page
header('Location: date_success.php?date=' . urlencode($selectedDate) . '&time=' . urlencode($selectedTime) . '&location=' . urlencode($pickupLocation));
exit;
?> 