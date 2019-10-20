<?php

return [
    'amqp' => [
        'host' => 'localhost',
        'port' => 5672,
        'user' => 'guest',
        'password' => 'guest',
    ],

    'sites' => [
        'forumodua' => [
            'login' => [
                'url' => 'https://forumodua.com/login.php?do=login',
                'username' => '', // set your username
                'password' => '', // set your password
            ],

            'topics' => [
                [
                    'url' => 'https://forumodua.com/showthread.php?t=61054',    // set URL of topic
                    'pages' => [1, 10]  // [first_page, last_page]
                ]
            ],
        ]
    ]
];