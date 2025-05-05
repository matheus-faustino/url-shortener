<?php

class Database
{
    private string $host;
    private string $database;
    private string $username;
    private string $password;

    public $connection = null;

    public function __construct()
    {
        $this->host = getenv('MYSQL_HOST') ?: "localhost";
        $this->database = getenv('MYSQL_DB') ?: "url_shortener";
        $this->username = getenv('MYSQL_USER') ?: "user";
        $this->password = getenv('MYSQL_PASSWORD') ?: "user";
    }

    public function getConnection()
    {
        try {
            $this->connection = new PDO("mysql:host=$this->host;dbname=$this->database", $this->username, $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection error: {$e->getMessage()}";
        }

        return $this->connection;
    }
}
