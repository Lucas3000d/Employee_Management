<?php
/**
 * Database connection class.
 * This file creates a simple PDO connection for the project.
 */
class Database
{
    private $host = 'localhost';
    private $dbName = 'employeemanagement';
    private $username = 'root';
    private $password = '';
    private $conn;

    public function connect()
    {
        if ($this->conn === null) {
            try {
                $dsn = "mysql:host={$this->host};dbname={$this->dbName};charset=utf8mb4";
                $this->conn = new PDO($dsn, $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }

        return $this->conn;
    }
}
?>
