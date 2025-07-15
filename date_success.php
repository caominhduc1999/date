<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Date Submitted Successfully</title>
    <link rel="stylesheet" href="css/date.css">
    <style>
        .success-container {
            text-align: center;
            padding: 50px 20px;
            max-width: 600px;
            margin: 0 auto;
        }
        .success-message {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 30px;
        }
        .continue-btn {
            background-color: #007bff;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .continue-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="success-container">
        <h1>🎉 Thank you!</h1>
        <div class="success-message">
            <h2>Your date has been saved!</h2>
            <p><strong>Date:</strong> <?php echo htmlspecialchars($_GET['date'] ?? 'N/A'); ?></p>
            <p><strong>Time:</strong> <?php echo htmlspecialchars($_GET['time'] ?? 'N/A'); ?></p>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($_GET['location'] ?? 'N/A'); ?></p>
        </div>
        <a href="food.html" class="continue-btn">Continue to choose a meal →</a>
    </div>
</body>
</html> 