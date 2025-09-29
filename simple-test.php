<?php
echo "<h2>Simple PHP Test</h2>";
echo "PHP is working!<br>";
echo "Current time: " . date('Y-m-d H:i:s') . "<br>";

// Test MySQL
$conn = new mysqli('localhost', 'root', '', 'laundry_db');
if ($conn->connect_error) {
    echo "❌ MySQL Error: " . $conn->connect_error;
} else {
    echo "✅ MySQL Connected<br>";
    $result = $conn->query("SELECT COUNT(*) as count FROM users");
    if ($result) {
        $row = $result->fetch_assoc();
        echo "✅ Found " . $row['count'] . " users";
    }
}
?>
