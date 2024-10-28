<?php

namespace App\Models;

use \PDO;
use \Exception;

class DatabaseConnection
{
    protected $type;
    protected $host;
    protected $port;
    protected $database;
    protected $user;
    protected $password;
    protected $dsn;
    protected $connection;

    public function __construct(
        $type,
        $host,
        $port,
        $database,
        $user,
        $password
    )
    {
        $this->type = $type;
        $this->host = $host;
        $this->port = $port;
        $this->database = $database;
        $this->user = $user;
        $this->password = $password;

        $this->buildConnectionString();
    }

    public function getType()
    {
        return $this->type;
    }

    public function getHost()
    {
        return $this->host;
    }

    public function getPort()
    {
        return $this->port;
    }

    public function getDatabaseName()
    {
        return $this->database;
    }

    public function getUsername()
    {
        return $this->user;
    }

    public function getPassword()
    {
        return $this->password;
    }

    private function buildConnectionString()
    {
        // mysql:host=HOSTNAME;port=PORT_NUMBER;dbname=DATABASE_NAME
        $this->dsn = "{$this->getType()}:host={$this->getHost()};port={$this->getPort()};dbname={$this->getDatabaseName()}";
    }

    public function connect()
    {
        try {
            $this
            ->connection = new PDO($this->dsn, $this->user, $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exceptions for error reporting
            return $this->connection;
        } catch (Exception $e) {
            error_log("Connection error: " . $e->getMessage());
            echo "Failed to connect to the database."; // Display a user-friendly message
            exit; // Exit the script if connection fails
        }

        return null;
    }
}    