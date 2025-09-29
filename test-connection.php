<?php
// Test database connection and API endpoints
echo "<h2>Laundry Management - Connection Test</h2>";

// Test 1: Database Connection
echo "<h3>1. Database Connection Test</h3>";
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'laundry_db';

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($conn->connect_error) {
    echo "<div style='color: red;'>❌ Database connection failed: " . $conn->connect_error . "</div>";
    echo "<p><strong>Solutions:</strong></p>";
    echo "<ul>";
    echo "<li>Make sure XAMPP MySQL is running</li>";
    echo "<li>Check if database 'laundry_db' exists in phpMyAdmin</li>";
    echo "<li>Import schema.sql and users_table.sql</li>";
    echo "</ul>";
} else {
    echo "<div style='color: green;'>✅ Database connection successful!</div>";
    
    // Test 2: Check if tables exist
    echo "<h3>2. Database Tables Test</h3>";
    $tables = ['users', 'customers', 'orders', 'notifications', 'schedules', 'price_config'];
    foreach ($tables as $table) {
        $result = $conn->query("SHOW TABLES LIKE '$table'");
        if ($result && $result->num_rows > 0) {
            echo "<div style='color: green;'>✅ Table '$table' exists</div>";
        } else {
            echo "<div style='color: red;'>❌ Table '$table' missing</div>";
        }
    }
    
    // Test 3: Check if users exist
    echo "<h3>3. Demo Users Test</h3>";
    $result = $conn->query("SELECT username, role FROM users");
    if ($result && $result->num_rows > 0) {
        echo "<div style='color: green;'>✅ Users found:</div>";
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li>" . htmlspecialchars($row['username']) . " (" . htmlspecialchars($row['role']) . ")</li>";
        }
        echo "</ul>";
    } else {
        echo "<div style='color: red;'>❌ No users found. Import users_table.sql</div>";
    }
}

// Test 4: API Endpoints
echo "<h3>4. API Endpoints Test</h3>";
$api_files = ['auth.php', 'customers.php', 'orders.php', 'notifications.php', 'pricing.php', 'schedule.php'];
foreach ($api_files as $file) {
    $path = __DIR__ . "/api/$file";
    if (file_exists($path)) {
        echo "<div style='color: green;'>✅ API file 'api/$file' exists</div>";
    } else {
        echo "<div style='color: red;'>❌ API file 'api/$file' missing</div>";
    }
}

// Test 5: PHP Configuration
echo "<h3>5. PHP Configuration</h3>";
echo "<div>PHP Version: " . phpversion() . "</div>";
echo "<div>MySQL Extension: " . (extension_loaded('mysqli') ? '✅ Available' : '❌ Missing') . "</div>";
echo "<div>JSON Extension: " . (extension_loaded('json') ? '✅ Available' : '❌ Missing') . "</div>";

echo "<hr>";
echo "<p><strong>If all tests pass, try accessing:</strong></p>";
echo "<ul>";
echo "<li><a href='login.php'>Login Page</a></li>";
echo "<li><a href='api/auth.php' target='_blank'>Test Auth API</a> (should show error)</li>";
echo "</ul>";
?>
