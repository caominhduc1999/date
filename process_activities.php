<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die('Method not allowed');
}

// Get the submitted activity selections
$selectedActivities = $_POST['activities'] ?? [];

// Convert to array if it's a string (single selection)
if (!is_array($selectedActivities)) {
    $selectedActivities = [$selectedActivities];
}

// Validate that at least one activity is selected
if (empty($selectedActivities)) {
    http_response_code(500);
    die('Please select at least one activity');
}

// Create logs directory if it doesn't exist
$logsDir = 'logs';
if (!is_dir($logsDir)) {
    mkdir($logsDir, 0755, true);
}

// Prepare log entry
$timestamp = date('Y-m-d H:i:s');
$activityList = implode(', ', $selectedActivities);

// Format log entry
$logEntry = sprintf(
    "[%s] Activities: %s\n",
    $timestamp,
    $activityList
);

// File path for the log file
$logFile = $logsDir . '/activity_selections.log';

// Append the log entry to the file
$saveResult = file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);

if ($saveResult === false) {
    http_response_code(500);
    die('Failed to save activity selection');
}

// Redirect to last page
header('Location: lastpage.html');
exit;
?> 