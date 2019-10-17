<?php

require_once __DIR__ . '/vendor/autoload.php';
$config = require __DIR__ . '/config/config.php';

$forum = new \App\ForumoduaSite($config, new \App\ForumoduaParser());

$login_result = $forum->login();

echo($login_result);