<?php
// Simple debug script to test the application
echo "<h1>Debug Information</h1>";

echo "<h2>PHP Version</h2>";
echo phpversion();

echo "<h2>Request Information</h2>";
echo "REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD'] . "<br>";
echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "<br>";
echo "SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "<br>";

echo "<h2>File Paths</h2>";
echo "Current directory: " . __DIR__ . "<br>";
echo "Index.php exists: " . (file_exists(__DIR__ . '/index.php') ? 'Yes' : 'No') . "<br>";
echo "Routes file exists: " . (file_exists(__DIR__ . '/routes/web.php') ? 'Yes' : 'No') . "<br>";
echo "HomeController exists: " . (file_exists(__DIR__ . '/app/Controllers/HomeController.php') ? 'Yes' : 'No') . "<br>";

echo "<h2>Database Test</h2>";
try {
    $pdo = new PDO('mysql:host=localhost;dbname=sihat_al_haj', 'root', '');
    echo "Database connection: Success<br>";
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM System_Content");
    $result = $stmt->fetch();
    echo "System_Content records: " . $result['count'] . "<br>";
} catch (Exception $e) {
    echo "Database connection: Failed - " . $e->getMessage() . "<br>";
}

echo "<h2>Test Route Loading</h2>";
try {
    require_once __DIR__ . '/app/Core/Router.php';
    $router = require_once __DIR__ . '/routes/web.php';
    echo "Routes loaded successfully<br>";
    echo "Router class: " . get_class($router) . "<br>";
} catch (Exception $e) {
    echo "Route loading failed: " . $e->getMessage() . "<br>";
}
?>