<?php
/**
 * Sihat Al-Hajj Platform Setup Script
 * 
 * Combines installation, migration, and database setup
 * Works on Windows & Linux
 */

// Check if already installed
if (file_exists('.env') && file_exists('storage' . DIRECTORY_SEPARATOR . 'installed.lock')) {
    die('Platform is already installed. Delete storage/installed.lock to reinstall.');
}

$step = $_GET['step'] ?? 1;
$errors = [];
$success = [];
$isCommandLine = php_sapi_name() === 'cli';

// Handle form submissions
if ($_POST) {
    switch ($step) {
        case 2:
            $errors = checkRequirements();
            if (empty($errors)) {
                $step = 3;
            }
            break;
            
        case 3:
            $result = setupDatabase($_POST);
            if ($result['success']) {
                $success[] = 'Database configured successfully';
                $step = 4;
            } else {
                $errors[] = $result['message'];
            }
            break;
            
        case 4:
            $result = createAdminUser($_POST);
            if ($result['success']) {
                $success[] = 'Admin user created successfully';
                $step = 5;
            } else {
                $errors[] = $result['message'];
            }
            break;
            
        case 5:
            $result = finalizeInstallation();
            if ($result['success']) {
                $success[] = 'Installation completed successfully';
                $step = 6;
            } else {
                $errors[] = $result['message'];
            }
            break;
    }
}

function checkRequirements() {
    $errors = [];
    
    // Check PHP version
    if (version_compare(PHP_VERSION, '7.4.0', '<')) {
        $errors[] = 'PHP 7.4 or higher is required. Current version: ' . PHP_VERSION;
    }
    
    // Check required extensions
    $required_extensions = ['pdo', 'pdo_mysql', 'mbstring', 'openssl', 'tokenizer', 'xml', 'ctype', 'json'];
    foreach ($required_extensions as $ext) {
        if (!extension_loaded($ext)) {
            $errors[] = "Required PHP extension missing: $ext";
        }
    }
    
    // Check directory permissions (Windows fallback: just check writable)
    $directories = [
        'storage',
        'storage' . DIRECTORY_SEPARATOR . 'logs',
        'storage' . DIRECTORY_SEPARATOR . 'cache',
        'public' . DIRECTORY_SEPARATOR . 'uploads'
    ];
    foreach ($directories as $dir) {
        if (!is_writable($dir)) {
            $errors[] = "Directory not writable: $dir";
        }
    }
    
    return $errors;
}

function setupDatabase($data) {
    try {
        // Create database if it doesn't exist
        $pdo = new PDO(
            "mysql:host={$data['db_host']}",
            $data['db_username'],
            $data['db_password'],
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$data['db_name']}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        
        // Connect to the specific database
        $pdo = new PDO(
            "mysql:host={$data['db_host']};dbname={$data['db_name']};charset=utf8mb4",
            $data['db_username'],
            $data['db_password'],
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        
        // Import database schema
        $schemaFile = 'database' . DIRECTORY_SEPARATOR . 'sihat-al-haj_db.sql';
        if (file_exists($schemaFile)) {
            $schema = file_get_contents($schemaFile);
            $statements = array_filter(array_map('trim', explode(';', $schema)));
            
            foreach ($statements as $statement) {
                if (!empty($statement) && !preg_match('/^(\/\*|--|\\s*$)/', $statement)) {
                    try {
                        $pdo->exec($statement);
                    } catch (PDOException $e) {
                        if (strpos($e->getMessage(), 'already exists') === false) {
                            throw $e;
                        }
                    }
                }
            }
        }
        
        // Insert sample data
        $sampleDataFile = 'database' . DIRECTORY_SEPARATOR . 'sample_data.sql';
        if (file_exists($sampleDataFile)) {
            $sampleData = file_get_contents($sampleDataFile);
            $statements = array_filter(array_map('trim', explode(';', $sampleData)));
            
            foreach ($statements as $statement) {
                if (!empty($statement) && !preg_match('/^(\/\*|--|\\s*$)/', $statement)) {
                    try {
                        $pdo->exec($statement);
                    } catch (PDOException $e) {
                        if (strpos($e->getMessage(), 'Duplicate entry') === false) {
                            throw $e;
                        }
                    }
                }
            }
        }
        
        // Create .env file
        if (file_exists('.env.example')) {
            $env_content = file_get_contents('.env.example');
            $env_content = str_replace('root', $data['db_username'], $env_content);
            $env_content = str_replace('password', $data['db_password'], $env_content);
            $env_content = str_replace('sihat_al_haj', $data['db_name'], $env_content);
            $env_content = str_replace('localhost', $data['db_host'], $env_content);
            $env_content = str_replace('your-32-character-secret-key-here', generateRandomKey(32), $env_content);
            file_put_contents('.env', $env_content);
        }
        
        return ['success' => true];
    } catch (Exception $e) {
        return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
    }
}

function createAdminUser($data) {
    try {
        // Use database config constants
        $pdo = new PDO(
            "mysql:host=localhost;dbname=sihat_al_haj;charset=utf8mb4",
            'root',
            '',
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        
        // Check if admin already exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM Users WHERE user_type = 'administrator'");
        $stmt->execute();
        
        if ($stmt->fetchColumn() > 0) {
            return ['success' => true, 'message' => 'Admin user already exists'];
        }
        
        // Create admin user
        $stmt = $pdo->prepare("
            INSERT INTO Users (
                username, email, password_hash, user_type, status,
                first_name_ar, first_name_en, last_name_ar, last_name_en,
                created_at, updated_at
            ) VALUES (?, ?, ?, 'administrator', 'active', ?, ?, ?, ?, NOW(), NOW())
        ");
        
        $stmt->execute([
            $data['username'] ?? 'admin',
            $data['email'] ?? 'admin@sihat-al-haj.sa',
            password_hash($data['password'] ?? 'admin123', PASSWORD_DEFAULT),
            $data['first_name_ar'] ?? 'مدير',
            $data['first_name_en'] ?? 'Admin',
            $data['last_name_ar'] ?? 'النظام',
            $data['last_name_en'] ?? 'System'
        ]);
        
        // Create sample guide and pilgrim users
        createSampleUsers($pdo);
        
        return ['success' => true];
    } catch (Exception $e) {
        return ['success' => false, 'message' => 'User creation error: ' . $e->getMessage()];
    }
}

function createSampleUsers($pdo) {
    // Create guide user
    $stmt = $pdo->prepare("
        INSERT IGNORE INTO Users (
            username, email, password_hash, user_type, status,
            first_name_ar, first_name_en, last_name_ar, last_name_en,
            created_at, updated_at
        ) VALUES (?, ?, ?, 'guide', 'active', ?, ?, ?, ?, NOW(), NOW())
    ");
    
    $stmt->execute([
        'guide1',
        'guide@sihat-al-haj.sa',
        password_hash('guide123', PASSWORD_DEFAULT),
        'أحمد',
        'Ahmed',
        'المرشد',
        'Guide'
    ]);
    
    // Create medical team user
    $stmt->execute([
        'doctor1',
        'doctor@sihat-al-haj.sa',
        password_hash('doctor123', PASSWORD_DEFAULT),
        'د. سارة',
        'Dr. Sara',
        'الطبيبة',
        'Doctor'
    ]);
    
    // Update user type for medical personnel
    $pdo->exec("UPDATE Users SET user_type = 'medical_personnel' WHERE username = 'doctor1'");
    
    // Create pilgrim user
    $stmt->execute([
        'pilgrim1',
        'pilgrim@sihat-al-haj.sa',
        password_hash('pilgrim123', PASSWORD_DEFAULT),
        'محمد',
        'Mohammed',
        'الحاج',
        'Pilgrim'
    ]);
    
    // Update user type for pilgrim
    $pdo->exec("UPDATE Users SET user_type = 'pilgrim' WHERE username = 'pilgrim1'");
}

function finalizeInstallation() {
    try {
        // Create installation lock file
        $lockFile = 'storage' . DIRECTORY_SEPARATOR . 'installed.lock';
        file_put_contents($lockFile, date('Y-m-d H:i:s'));
        
        // Windows: chmod has no effect → just notify
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            return ['success' => true, 'message' => 'Installation completed (Note: chmod skipped on Windows)'];
        }

        // Linux/Unix: set proper permissions
        chmod('storage', 0755);
        chmod('storage/logs', 0755);
        chmod('storage/cache', 0755);
        chmod('public/uploads', 0755);
        
        return ['success' => true];
    } catch (Exception $e) {
        return ['success' => false, 'message' => 'Finalization error: ' . $e->getMessage()];
    }
}

function generateRandomKey($length = 32) {
    return bin2hex(random_bytes($length / 2));
}

// Command line setup function
function runCommandLineSetup() {
    echo "=== Sihat Al-Hajj Platform Setup ===\n\n";
    
    // Check requirements
    echo "Checking requirements...\n";
    $errors = checkRequirements();
    if (!empty($errors)) {
        echo "\n❌ Requirements check failed:\n";
        foreach ($errors as $error) {
            echo "  - $error\n";
        }
        exit(1);
    }
    echo "✓ All requirements met\n\n";
    
    // Setup database with default values
    echo "Setting up database...\n";
    $dbData = [
        'db_host' => 'localhost',
        'db_name' => 'sihat_al_haj',
        'db_username' => 'root',
        'db_password' => ''
    ];
    
    $result = setupDatabase($dbData);
    if (!$result['success']) {
        echo "❌ Database setup failed: {$result['message']}\n";
        exit(1);
    }
    echo "✓ Database setup completed\n\n";
    
    // Create admin user
    echo "Creating admin user...\n";
    $adminData = [
        'username' => 'admin',
        'email' => 'admin@sihat-al-haj.sa',
        'password' => 'admin123',
        'first_name_ar' => 'مدير',
        'first_name_en' => 'Admin',
        'last_name_ar' => 'النظام',
        'last_name_en' => 'System'
    ];
    
    $result = createAdminUser($adminData);
    if (!$result['success']) {
        echo "❌ Admin user creation failed: {$result['message']}\n";
        exit(1);
    }
    echo "✓ Admin user created\n\n";
    
    // Finalize installation
    echo "Finalizing installation...\n";
    $result = finalizeInstallation();
    if (!$result['success']) {
        echo "❌ Installation finalization failed: {$result['message']}\n";
        exit(1);
    }
    echo "✓ Installation completed\n\n";
    
    // Show summary
    echo "=== Setup Complete ===\n";
    echo "Database: sihat_al_haj\n";
    echo "\nDefault login credentials:\n";
    echo "Admin: admin@sihat-al-haj.sa / admin123\n";
    echo "Guide: guide@sihat-al-haj.sa / guide123\n";
    echo "Doctor: doctor@sihat-al-haj.sa / doctor123\n";
    echo "Pilgrim: pilgrim@sihat-al-haj.sa / pilgrim123\n";
    echo "\nAccess the application at: http://localhost:8080/sihat-al-haj/\n";
}

// Run command line setup if called from CLI
if ($isCommandLine) {
    runCommandLineSetup();
    exit(0);
}

?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sihat Al-Hajj Platform Setup</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; }
        .container { max-width: 800px; margin: 50px auto; background: white; border-radius: 10px; box-shadow: 0 0 20px rgba(0,0,0,0.1); }
        .header { background: #2563eb; color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { padding: 30px; }
        .step { background: #f8fafc; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
        .btn { background: #2563eb; color: white; padding: 12px 30px; border: none; border-radius: 5px; cursor: pointer; }
        .btn:hover { background: #1d4ed8; }
        .error { background: #fee2e2; color: #dc2626; padding: 10px; border-radius: 5px; margin-bottom: 20px; }
        .success { background: #dcfce7; color: #16a34a; padding: 10px; border-radius: 5px; margin-bottom: 20px; }
        .progress { background: #e5e7eb; height: 10px; border-radius: 5px; margin-bottom: 20px; }
        .progress-bar { background: #2563eb; height: 100%; border-radius: 5px; transition: width 0.3s; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🕋 Sihat Al-Hajj Platform Setup</h1>
            <p>Complete platform installation and configuration</p>
        </div>
        
        <div class="content">
            <div class="progress">
                <div class="progress-bar" style="width: <?= ($step / 6) * 100 ?>%"></div>
            </div>
            
            <?php if (!empty($errors)): ?>
                <div class="error">
                    <?php foreach ($errors as $error): ?>
                        <p>❌ <?= htmlspecialchars($error) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
                <div class="success">
                    <?php foreach ($success as $msg): ?>
                        <p>✅ <?= htmlspecialchars($msg) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <?php if ($step == 1): ?>
                <div class="step">
                    <h2>Welcome to Sihat Al-Hajj Setup</h2>
                    <p>This wizard will guide you through the installation process.</p>
                    <br>
                    <form method="post" action="?step=2">
                        <button type="submit" class="btn">Start Installation</button>
                    </form>
                </div>
                
            <?php elseif ($step == 2): ?>
                <div class="step">
                    <h2>System Requirements Check</h2>
                    <form method="post" action="?step=3">
                        <button type="submit" class="btn">Check Requirements</button>
                    </form>
                </div>
                
            <?php elseif ($step == 3): ?>
                <div class="step">
                    <h2>Database Configuration</h2>
                    <form method="post" action="?step=4">
                        <div class="form-group">
                            <label>Database Host:</label>
                            <input type="text" name="db_host" value="localhost" required>
                        </div>
                        <div class="form-group">
                            <label>Database Name:</label>
                            <input type="text" name="db_name" value="sihat_al_haj" required>
                        </div>
                        <div class="form-group">
                            <label>Database Username:</label>
                            <input type="text" name="db_username" value="root" required>
                        </div>
                        <div class="form-group">
                            <label>Database Password:</label>
                            <input type="password" name="db_password" value="">
                        </div>
                        <button type="submit" class="btn">Setup Database</button>
                    </form>
                </div>
                
            <?php elseif ($step == 4): ?>
                <div class="step">
                    <h2>Create Admin User</h2>
                    <form method="post" action="?step=5">
                        <div class="form-group">
                            <label>Username:</label>
                            <input type="text" name="username" value="admin" required>
                        </div>
                        <div class="form-group">
                            <label>Email:</label>
                            <input type="email" name="email" value="admin@sihat-al-haj.sa" required>
                        </div>
                        <div class="form-group">
                            <label>Password:</label>
                            <input type="password" name="password" value="admin123" required>
                        </div>
                        <button type="submit" class="btn">Create Admin User</button>
                    </form>
                </div>
                
            <?php elseif ($step == 5): ?>
                <div class="step">
                    <h2>Finalize Installation</h2>
                    <form method="post" action="?step=6">
                        <button type="submit" class="btn">Complete Installation</button>
                    </form>
                </div>
                
            <?php elseif ($step == 6): ?>
                <div class="step">
                    <h2>🎉 Installation Complete!</h2>
                    <p><strong>Database:</strong> sihat_al_haj</p>
                    <br>
                    <p><strong>Default Login Credentials:</strong></p>
                    <ul>
                        <li>Admin: admin@sihat-al-haj.sa / admin123</li>
                        <li>Guide: guide@sihat-al-haj.sa / guide123</li>
                        <li>Doctor: doctor@sihat-al-haj.sa / doctor123</li>
                        <li>Pilgrim: pilgrim@sihat-al-haj.sa / pilgrim123</li>
                    </ul>
                    <br>
                    <a href="/" class="btn">Access Application</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>