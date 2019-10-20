<?php

require_once __DIR__ . '/vendor/autoload.php';
$config = require __DIR__ . '/config/config.php';

try {
    $db = \Core\Database::getInstance($config['pgsql']);
    \Core\Message::setDbConnection($db->getConnection());
    \Core\Message::prepareDatabase();
} catch (PDOException $e) {
    die('Подключение не удалось: ' . $e->getMessage());
}

$worker = new \Core\Worker($config['rabbitmq'], $db->getConnection());