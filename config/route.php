<?php

use Tunacan\Bundle\Controller;

return [
    [
        'path' => '/',
        'redirect' => '/index/tuna'
    ],
    [
        'path' => '/index/:bbsUid',
        'controller' => Controller\IndexController::class,
        'method' => 'index'
    ],
    [
        'path' => '/trace/:bbsUid/:cardUid?:startPostUid/:endPostUid',
        'controller' => Controller\TraceController::class,
        'method' => 'index'
    ],
    [
        'path' => '/post',
        'controller' => Controller\WriteController::class,
        'method' => 'main'
    ],
    [
        'path' => '/list/:bbsUid/:page',
        'controller' => Controller\ListController::class,
        'method' => 'index'
    ]
];
