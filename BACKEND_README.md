# PHP Backend for Date Form Submission

This backend handles the date selection form submission from your valentine website and stores the selected dates.

## Files Created

1. **`process_date.php`** - Backend script that processes date form submissions
2. **`process_food.php`** - Backend script that processes food form submissions
3. **`process_dessert.php`** - Backend script that processes dessert form submissions
4. **`process_activities.php`** - Backend script that processes activities form submissions
5. **`date_success.php`** - Success page shown after date submission
6. **`view_dates.php`** - Admin page to view date submissions only
7. **`view_all_logs.php`** - Comprehensive admin dashboard to view all submissions
8. **Log files** (created automatically):
   - `logs/date_submissions.log` - Date selections
   - `logs/food_selections.log` - Food selections
   - `logs/dessert_selections.log` - Dessert selections
   - `logs/activity_selections.log` - Activity selections

## Setup Instructions

### 1. Web Server Requirements
- PHP 7.0 or higher
- Web server (Apache, Nginx, or built-in PHP server)
- Write permissions for the `data` directory

### 2. Using PHP Built-in Server (for testing)
```bash
# Navigate to your project directory
cd valentine.github.io-main

# Start PHP server
php -S localhost:8000

# Access your site at http://localhost:8000
```

### 3. Using Apache/Nginx
- Place all files in your web server's document root
- Ensure PHP is installed and configured
- Make sure the `data` directory is writable by the web server

## How It Works

### Form Submission Flow
1. User selects a date on `date.html` → submits to `process_date.php` → stores in `logs/date_submissions.log` → redirects to `date_success.php`
2. User continues to `food.html` → submits to `process_food.php` → stores in `logs/food_selections.log` → redirects to `dessert.html`
3. User continues to `dessert.html` → submits to `process_dessert.php` → stores in `logs/dessert_selections.log` → redirects to `activities.html`
4. User continues to `activities.html` → submits to `process_activities.php` → stores in `logs/activity_selections.log` → redirects to `lastpage.html`

### Data Storage
All selections are stored in separate log files:

**Date selections** (`logs/date_submissions.log`):
```
[2024-02-10 15:30:45] Date: 2024-02-14
```

**Food selections** (`logs/food_selections.log`):
```
[2024-02-10 15:35:22] Food: pizza, sushi
```

**Dessert selections** (`logs/dessert_selections.log`):
```
[2024-02-10 15:40:15] Dessert: boba, mochi
```

**Activity selections** (`logs/activity_selections.log`):
```
[2024-02-10 15:45:30] Activities: cinema, park
```

## Admin Access

### View All Submissions (Recommended)
- Navigate to `view_all_logs.php` in your browser
- Enter the admin password: `valentine2024`
- View all submissions (dates, food, dessert, activities) with filtering options
- See statistics and comprehensive dashboard

### View Individual Logs
- **Dates only**: Navigate to `view_dates.php`
- **All logs**: Navigate to `view_all_logs.php` (recommended)

### Security Notes
- Change the admin password in `view_dates.php` (line 3)
- Consider implementing proper authentication for production use
- The `data` directory should be protected from direct web access

## File Permissions

Ensure the following permissions:
```bash
# Make logs directory writable
chmod 755 logs/
chmod 644 logs/date_submissions.log
```

## Troubleshooting

### Common Issues

1. **"Failed to save date" error**
   - Check if the `logs` directory exists and is writable
   - Ensure PHP has write permissions

2. **Form not submitting**
   - Verify PHP is installed and running
   - Check web server configuration
   - Ensure `process_date.php` is accessible

3. **Date validation errors**
   - Make sure the date input has the `name="selected_date"` attribute
   - Check that the date format is YYYY-MM-DD

### Debug Mode
The PHP script includes error reporting. Check your web server's error logs for detailed information.

## Customization

### Adding More Fields
To store additional information, modify the log entry format in `process_date.php`:
```php
$logEntry = sprintf(
    "[%s] Date: %s | IP: %s | User-Agent: %s | Additional: %s\n",
    $timestamp,
    $selectedDate,
    $ipAddress,
    $userAgent,
    $additionalField
);
```

### Database Storage
For production use, consider using a database instead of JSON files:
- MySQL/MariaDB
- SQLite
- PostgreSQL

## Security Considerations

1. **Input Validation**: The script validates date format and requires the date field
2. **XSS Protection**: Output is escaped using `htmlspecialchars()`
3. **File Permissions**: Ensure proper file permissions for the data directory
4. **Admin Access**: Change the default admin password

## Support

If you encounter issues:
1. Check the web server error logs
2. Verify PHP is properly installed and configured
3. Ensure all file permissions are correct
4. Test with the built-in PHP server first 