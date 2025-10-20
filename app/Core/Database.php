<?php

namespace App\Core;

/**
 * Database Class
 * 
 * Handles database connections and provides a singleton PDO instance
 */
class Database
{
    private static $instance = null;
    private $connection;
    
    private function __construct()
    {
        $this->connect();
    }
    
    /**
     * Get database instance (Singleton pattern)
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        
        return self::$instance->connection;
    }
    
    /**
     * Establish database connection
     */
    private function connect()
    {
        try {
            // Load database configuration
            $config = require CONFIG_PATH . '/database.php';
            
            $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset={$config['charset']}";
            
            $options = [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_EMULATE_PREPARES => false,
                \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$config['charset']} COLLATE {$config['collation']}"
            ];
            
            $this->connection = new \PDO($dsn, $config['username'], $config['password'], $options);
            
        } catch (\PDOException $e) {
            throw new \Exception("Database connection failed: " . $e->getMessage());
        }
    }
    
    /**
     * Prevent cloning
     */
    private function __clone() {}
    
    /**
     * Prevent unserialization
     */
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize singleton");
    }
    
    /**
     * Begin transaction
     */
    public static function beginTransaction()
    {
        return self::getInstance()->beginTransaction();
    }
    
    /**
     * Commit transaction
     */
    public static function commit()
    {
        return self::getInstance()->commit();
    }
    
    /**
     * Rollback transaction
     */
    public static function rollback()
    {
        return self::getInstance()->rollback();
    }
    
    /**
     * Execute a transaction with callback
     */
    public static function transaction($callback)
    {
        $db = self::getInstance();
        
        try {
            $db->beginTransaction();
            
            $result = $callback($db);
            
            $db->commit();
            
            return $result;
            
        } catch (\Exception $e) {
            $db->rollback();
            throw $e;
        }
    }
    
    /**
     * Prepare and execute a query
     */
    public static function query($sql, $params = [])
    {
        $db = self::getInstance();
        $stmt = $db->prepare($sql);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->execute();
        return $stmt;
    }
    
    /**
     * Get single row
     */
    public static function fetchOne($sql, $params = [])
    {
        $stmt = self::query($sql, $params);
        return $stmt->fetch();
    }
    
    /**
     * Get all rows
     */
    public static function fetchAll($sql, $params = [])
    {
        $stmt = self::query($sql, $params);
        return $stmt->fetchAll();
    }
    
    /**
     * Execute insert/update/delete query
     */
    public static function execute($sql, $params = [])
    {
        $stmt = self::query($sql, $params);
        return $stmt->rowCount();
    }
    
    /**
     * Get last insert ID
     */
    public static function lastInsertId()
    {
        return self::getInstance()->lastInsertId();
    }
    
    /**
     * Check if table exists
     */
    public static function tableExists($tableName)
    {
        $sql = "SHOW TABLES LIKE :table";
        $stmt = self::query($sql, [':table' => $tableName]);
        return $stmt->rowCount() > 0;
    }
    
    /**
     * Get table columns
     */
    public static function getTableColumns($tableName)
    {
        $sql = "DESCRIBE {$tableName}";
        return self::fetchAll($sql);
    }
    
    /**
     * Run database migrations
     */
    public static function migrate()
    {
        $migrationPath = defined('APP_ROOT') ? APP_ROOT . '/database/migrations' : __DIR__ . '/../../database/migrations';
        
        if (!is_dir($migrationPath)) {
            return;
        }
        
        // Create migrations table if it doesn't exist
        $sql = "CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255) NOT NULL,
            executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        
        self::execute($sql);
        
        // Get executed migrations
        $executedMigrations = self::fetchAll("SELECT migration FROM migrations");
        $executedMigrations = array_column($executedMigrations, 'migration');
        
        // Get migration files
        $migrationFiles = glob($migrationPath . '/*.sql');
        sort($migrationFiles);
        
        foreach ($migrationFiles as $file) {
            $migrationName = basename($file, '.sql');
            
            if (!in_array($migrationName, $executedMigrations)) {
                $sql = file_get_contents($file);
                
                try {
                    self::execute($sql);
                    
                    // Record migration
                    self::execute(
                        "INSERT INTO migrations (migration) VALUES (:migration)",
                        [':migration' => $migrationName]
                    );
                    
                    echo "Executed migration: {$migrationName}\n";
                    
                } catch (\Exception $e) {
                    echo "Failed to execute migration {$migrationName}: " . $e->getMessage() . "\n";
                    break;
                }
            }
        }
    }
}

