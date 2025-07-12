<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die('Method not allowed');
}

// Get the submitted food selections
$selectedFoods = $_POST['food'] ?? [];

// Convert to array if it's a string (single selection)
if (!is_array($selectedFoods)) {
    $selectedFoods = [$selectedFoods];
}

// Validate that at least one food is selected
if (empty($selectedFoods)) {
    http_response_code(400);
    die('Please select at least one food item');
}

// Create logs directory if it doesn't exist
$logsDir = 'logs';
if (!is_dir($logsDir)) {
    mkdir($logsDir, 0755, true);
}

// Prepare log entry
$timestamp = date('Y-m-d H:i:s');
$foodList = implode(', ', $selectedFoods);

// Format log entry
$logEntry = sprintf(
    "[%s] Food: %s\n",
    $timestamp,
    $foodList
);

// File path for the log file
$logFile = $logsDir . '/food_selections.log';

// Append the log entry to the file
$saveResult = file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);

if ($saveResult === false) {
    http_response_code(500);
    die('Failed to save food selection');
}

// Redirect to dessert page
header('Location: dessert.html');
exit;
?> 