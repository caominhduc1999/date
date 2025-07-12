<?php
// Simple authentication (you can enhance this)
$adminPassword = 'valentine2024'; // Change this to a secure password

if (isset($_POST['password']) && $_POST['password'] === $adminPassword) {
    $authenticated = true;
} else {
    $authenticated = false;
}

// Function to parse log entries
function parseLogEntries($logFile) {
    $entries = [];
    if (file_exists($logFile)) {
        $logContent = file_get_contents($logFile);
        if ($logContent !== false) {
            $lines = explode("\n", trim($logContent));
            foreach ($lines as $line) {
                if (!empty($line)) {
                    // Parse different log formats (simplified)
                    if (preg_match('/^\[(.*?)\] Date: (.*?) \| Time: (.*?) \| Location: (.*?)$/', $line, $matches)) {
                        $entries[] = [
                            'type' => 'Date',
                            'timestamp' => $matches[1],
                            'selection' => $matches[2] . ' at ' . $matches[3] . ' - ' . $matches[4]
                        ];
                    } elseif (preg_match('/^\[(.*?)\] Date: (.*?)$/', $line, $matches)) {
                        // Fallback for old format
                        $entries[] = [
                            'type' => 'Date',
                            'timestamp' => $matches[1],
                            'selection' => $matches[2]
                        ];
                    } elseif (preg_match('/^\[(.*?)\] Food: (.*?)$/', $line, $matches)) {
                        $entries[] = [
                            'type' => 'Food',
                            'timestamp' => $matches[1],
                            'selection' => $matches[2]
                        ];
                    } elseif (preg_match('/^\[(.*?)\] Dessert: (.*?)$/', $line, $matches)) {
                        $entries[] = [
                            'type' => 'Dessert',
                            'timestamp' => $matches[1],
                            'selection' => $matches[2]
                        ];
                    } elseif (preg_match('/^\[(.*?)\] Activities: (.*?)$/', $line, $matches)) {
                        $entries[] = [
                            'type' => 'Activities',
                            'timestamp' => $matches[1],
                            'selection' => $matches[2]
                        ];
                    }
                }
            }
        }
    }
    return $entries;
}

// Get all log entries
$dateEntries = parseLogEntries('logs/date_submissions.log');
$foodEntries = parseLogEntries('logs/food_selections.log');
$dessertEntries = parseLogEntries('logs/dessert_selections.log');
$activityEntries = parseLogEntries('logs/activity_selections.log');

// Combine all entries and sort by timestamp
$allEntries = array_merge($dateEntries, $foodEntries, $dessertEntries, $activityEntries);
usort($allEntries, function($a, $b) {
    return strtotime($b['timestamp']) - strtotime($a['timestamp']);
});

// Get statistics
$stats = [
    'total_submissions' => count($allEntries),
    'date_submissions' => count($dateEntries),
    'food_submissions' => count($foodEntries),
    'dessert_submissions' => count($dessertEntries),
    'activity_submissions' => count($activityEntries)
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View All Logs - Valentine Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
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
        .stats {
            background-color: #e9ecef;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 30px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        .stat-item {
            text-align: center;
            padding: 15px;
            background: white;
            border-radius: 5px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .stat-number {
            font-size: 2em;
            font-weight: bold;
            color: #007bff;
        }
        .log-entry {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 15px;
        }
        .log-entry h3 {
            margin: 0 0 10px 0;
            color: #007bff;
        }
        .log-entry p {
            margin: 5px 0;
            color: #666;
        }
        .type-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 0.8em;
            font-weight: bold;
            color: white;
        }
        .type-date { background-color: #28a745; }
        .type-food { background-color: #ffc107; color: #212529; }
        .type-dessert { background-color: #fd7e14; }
        .type-activities { background-color: #6f42c1; }
        .no-entries {
            text-align: center;
            color: #666;
            font-style: italic;
            padding: 40px;
        }
        .filter-buttons {
            margin-bottom: 20px;
            text-align: center;
        }
        .filter-btn {
            padding: 8px 16px;
            margin: 0 5px;
            border: 1px solid #007bff;
            background: white;
            color: #007bff;
            border-radius: 5px;
            cursor: pointer;
        }
        .filter-btn.active {
            background: #007bff;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📊 Valentine Admin Dashboard</h1>
        
        <?php if (!$authenticated): ?>
            <div class="login-form">
                <form method="POST">
                    <input type="password" name="password" placeholder="Enter admin password" required>
                    <button type="submit">View Logs</button>
                </form>
            </div>
        <?php else: ?>
            <div class="stats">
                <div class="stat-item">
                    <div class="stat-number"><?php echo $stats['total_submissions']; ?></div>
                    <div>Total Submissions</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo $stats['date_submissions']; ?></div>
                    <div>Date Selections</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo $stats['food_submissions']; ?></div>
                    <div>Food Selections</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo $stats['dessert_submissions']; ?></div>
                    <div>Dessert Selections</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo $stats['activity_submissions']; ?></div>
                    <div>Activity Selections</div>
                </div>
            </div>
            
            <div class="filter-buttons">
                <button class="filter-btn active" onclick="filterEntries('all')">All</button>
                <button class="filter-btn" onclick="filterEntries('date')">Dates</button>
                <button class="filter-btn" onclick="filterEntries('food')">Food</button>
                <button class="filter-btn" onclick="filterEntries('dessert')">Dessert</button>
                <button class="filter-btn" onclick="filterEntries('activities')">Activities</button>
            </div>
            
            <?php if (empty($allEntries)): ?>
                <div class="no-entries">
                    <p>No submissions have been made yet.</p>
                </div>
            <?php else: ?>
                <div id="log-entries">
                    <?php foreach ($allEntries as $index => $entry): ?>
                        <div class="log-entry" data-type="<?php echo strtolower($entry['type']); ?>">
                            <h3>
                                <span class="type-badge type-<?php echo strtolower($entry['type']); ?>">
                                    <?php echo $entry['type']; ?>
                                </span>
                                Submission #<?php echo count($allEntries) - $index; ?>
                            </h3>
                            <p><strong>Selection:</strong> <?php echo htmlspecialchars($entry['selection']); ?></p>
                            <p><strong>Submitted:</strong> <?php echo htmlspecialchars($entry['timestamp']); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <script>
        function filterEntries(type) {
            const entries = document.querySelectorAll('.log-entry');
            const buttons = document.querySelectorAll('.filter-btn');
            
            // Update button states
            buttons.forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
            
            // Filter entries
            entries.forEach(entry => {
                if (type === 'all' || entry.dataset.type === type) {
                    entry.style.display = 'block';
                } else {
                    entry.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html> 