<?php
namespace Core;

class Database
{
    private static $instance = null;
    private $connection;

    private function __construct(array $config)
    {
        $this->connection = new \PDO("{$config['type']}:host={$config['host']};dbname={$config['dbname']}", $config['user'], $config['password']);
    }
    private function __clone() {}
    private function __wakeup() {}
    private function __sleep() {}

    static function getInstance(array $config)
    {
        if( !self::$instance ) {
            self::$instance = new Database($config);
        }

        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }
}