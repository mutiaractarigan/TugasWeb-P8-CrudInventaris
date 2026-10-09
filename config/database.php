<?php
// Koneksi database dengan PDO (Singleton pattern):
// objek koneksi hanya dibuat sekali, lalu dipakai ulang.
class Database
{
    private static $instance = null;
    private $pdo;

    // Sesuaikan dengan pengaturan Laragon / XAMPP
    private $host = 'localhost';
    private $dbname = 'inventaris_db';
    private $username = 'root';
    private $password = '';

    // Constructor private supaya tidak bisa "new Database()" dari luar
    private function __construct()
    {
        $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4";

        try {
            $this->pdo = new PDO($dsn, $this->username, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            die('Koneksi database gagal: ' . $e->getMessage());
        }
    }

    // Cegah object di-clone
    private function __clone()
    {
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->pdo;
    }
}
