<?php
namespace App\Config;

use PDO;
use PDOException;

class Database
{
    private static $instance = null;
    private $connection;

    private function __construct()
    {
        // قراءة الإعدادات من .env أو استخدام القيم الافتراضية
        $host = $_ENV['DB_HOST'] ?? 'localhost';
        $dbname = $_ENV['DB_NAME'] ?? 'mobile_repair_system';
        $user = $_ENV['DB_USER'] ?? 'root';
        $pass = $_ENV['DB_PASS'] ?? '';

        try {
            $this->connection = new PDO(
                "mysql:host={$host};dbname={$dbname};charset=utf8mb4",
                $user,
                $pass,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            die("فشل الاتصال بقاعدة البيانات: " . $e->getMessage());
        }
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }
    
    /**
     * تنفيذ استعلام SQL (للترحيلات أو التحديثات)
     */
    public function exec(string $sql): int
    {
        return $this->connection->exec($sql);
    }
    
    /**
     * بدء المعاملة
     */
    public function beginTransaction(): void
    {
        $this->connection->beginTransaction();
    }
    
    /**
     * تأكيد المعاملة
     */
    public function commit(): void
    {
        $this->connection->commit();
    }
    
    /**
     * التراجع عن المعاملة
     */
    public function rollBack(): void
    {
        $this->connection->rollBack();
    }
}