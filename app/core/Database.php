<?php
class Database
{
    private static $instance = null;
    private $connection;

    private function __construct($config)
    {
        $dsn = "{$config['driver']}:host={$config['host']};dbname={$config['database']};charset={$config['charset']}";

        try {
            $this->connection = new PDO($dsn, $config['username'], $config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    }

    public static function init($config)
    {
        if (self::$instance === null) {
            self::$instance = new self($config);
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            throw new Exception("Database not initialized");
        }
        return self::$instance->connection;
    }

    public static function query($sql, $params = [])
    {
        $stmt = self::getInstance()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}
