<?php
// Simple authentication (you can enhance this)
$adminPassword = 'valentine2024'; // Change this to a secure password

if (isset($_POST['password']) && $_POST['password'] === $adminPassword) {
    $authenticated = true;
} else {
    $authenticated = false;
}

$logFile = 'logs/date_submissions.log';
$logEntries = [];

if (file_exists($logFile)) {
    $logContent = file_get_contents($logFile);
    if ($logContent !== false) {
        $lines = explode("\n", trim($logContent));
        foreach ($lines as $line) {
            if (!empty($line)) {
                // Parse log entry: [timestamp] Date: date | Time: time | Location: location
                if (preg_match('/^\[(.*?)\] Date: (.*?) \| Time: (.*?) \| Location: (.*?)$/', $line, $matches)) {
                    $logEntries[] = [
                        'timestamp' => $matches[1],
                        'selected_date' => $matches[2],
                        'selected_time' => $matches[3],
                        'pickup_location' => $matches[4]
                    ];
                } elseif (preg_match('/^\[(.*?)\] Date: (.*?)$/', $line, $matches)) {
                    // Fallback for old format
                    $logEntries[] = [
                        'timestamp' => $matches[1],
                        'selected_date' => $matches[2]
                    ];
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Submitted Dates</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .login-form {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-form input[type="password"] {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-right: 10px;
        }
        .login-form button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .date-entry {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 15px;
        }
        .date-entry h3 {
            margin: 0 0 10px 0;
            color: #007bff;
        }
        .date-entry p {
            margin: 5px 0;
            color: #666;
        }
        .no-dates {
            text-align: center;
            color: #666;
            font-style: italic;
        }
        .stats {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📅 Submitted Dates</h1>
        
        <?php if (!$authenticated): ?>
            <div class="login-form">
                <form method="POST">
                    <input type="password" name="password" placeholder="Enter admin password" required>
                    <button type="submit">View Dates</button>
                </form>
            </div>
        <?php else: ?>
            <div class="stats">
                <h3>📊 Statistics</h3>
                <p><strong>Total submissions:</strong> <?php echo count($logEntries); ?></p>
                <?php if (!empty($logEntries)): ?>
                    <p><strong>Latest submission:</strong> <?php echo end($logEntries)['timestamp']; ?></p>
                <?php endif; ?>
            </div>
            
            <?php if (empty($logEntries)): ?>
                <div class="no-dates">
                    <p>No dates have been submitted yet.</p>
                </div>
            <?php else: ?>
                <?php foreach (array_reverse($logEntries) as $index => $entry): ?>
                    <div class="date-entry">
                        <h3>Submission #<?php echo count($logEntries) - $index; ?></h3>
                        <p><strong>Selected Date:</strong> <?php echo htmlspecialchars($entry['selected_date']); ?></p>
                        <?php if (isset($entry['selected_time'])): ?>
                            <p><strong>Selected Time:</strong> <?php echo htmlspecialchars($entry['selected_time']); ?></p>
                        <?php endif; ?>
                        <?php if (isset($entry['pickup_location'])): ?>
                            <p><strong>Pickup Location:</strong> <?php echo htmlspecialchars($entry['pickup_location']); ?></p>
                        <?php endif; ?>
                        <p><strong>Submitted:</strong> <?php echo htmlspecialchars($entry['timestamp']); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html> 