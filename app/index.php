<?php

require_once __DIR__ . '/vendor/autoload.php';
$config = require __DIR__ . '/config/config.php';

$producer = new \Core\ForumoduaProducer($config['amqp']);
$parser = new \Core\ForumoduaParser($producer);
$forum = new \Core\ForumoduaSite($config, $parser);

$login_result = $forum->login();

var_dump($login_result);

$forum->parse();

$producer->close();