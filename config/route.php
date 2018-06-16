<?php

use Tunacan\Bundle\Controller;

return [
    [
        'path' => '/',
        'redirect' => '/index/tuna'
    ],
    [
        'path' => '/index/{bbsUid}',
        'controller' => Controller\IndexController::class,
        'method' => 'index'
    ],
    [
        'path' => '/post',
        'controller' => Controller\WriteController::class,
        'method' => 'writePost'
    ]
];
