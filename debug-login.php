<?php
// Debug login API directly
header('Content-Type: text/html');
echo "<h2>Login API Debug Test</h2>";

// Test 1: Check if auth.php exists
echo "<h3>1. API File Check</h3>";
if (file_exists(__DIR__ . '/api/auth.php')) {
    echo "✅ auth.php exists<br>";
} else {
    echo "❌ auth.php missing<br>";
}

// Test 2: Test database connection
echo "<h3>2. Database Connection</h3>";
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'laundry_db';

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($conn->connect_error) {
    echo "❌ Database connection failed: " . $conn->connect_error . "<br>";
} else {
    echo "✅ Database connected<br>";
    
    // Check users table
    $result = $conn->query("SELECT COUNT(*) as count FROM users");
    if ($result) {
        $row = $result->fetch_assoc();
        echo "✅ Users table has " . $row['count'] . " records<br>";
    } else {
        echo "❌ Cannot access users table<br>";
    }
}

// Test 3: Manual login test
echo "<h3>3. Manual Login Test</h3>";
if (isset($_POST['test_login'])) {
    session_start();
    
    $username = 'staff';
    $password = 'password';
    $role = 'staff';
    
    $q = "SELECT id, username, password, role, full_name, email FROM users WHERE username='$username' AND role='$role' AND is_active=1 LIMIT 1";
    $res = $conn->query($q);
    
    if (!$res || $res->num_rows === 0) {
        echo "❌ User not found<br>";
    } else {
        $user = $res->fetch_assoc();
        echo "✅ User found: " . $user['full_name'] . "<br>";
        
        if (password_verify($password, $user['password'])) {
            echo "✅ Password verified<br>";
            echo "✅ Login would succeed<br>";
        } else {
            echo "❌ Password verification failed<br>";
            echo "Stored hash: " . substr($user['password'], 0, 20) . "...<br>";
        }
    }
} else {
    echo '<form method="post"><button name="test_login" type="submit">Test Staff Login</button></form>';
}

// Test 4: Direct API call
echo "<h3>4. Direct API Test</h3>";
echo '<button onclick="testAPI()">Test API Call</button>';
echo '<div id="apiResult"></div>';

echo '<script>
async function testAPI() {
    try {
        const response = await fetch("api/auth.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ action: "login", username: "staff", password: "password", role: "staff" })
        });
        
        const result = await response.json();
        document.getElementById("apiResult").innerHTML = "<pre>" + JSON.stringify(result, null, 2) + "</pre>";
    } catch (error) {
        document.getElementById("apiResult").innerHTML = "<div style=\"color: red;\">Error: " + error.message + "</div>";
    }
}
</script>';

echo "<hr>";
echo "<p><a href='login.php'>Back to Login Page</a></p>";
?>
