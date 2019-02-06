<?php
use Tunacan\Bundle\Controller;

return [
    [
        'path' => '/',
        'redirect' => '/index/tuna'
    ],
    [
        'path' => '/index/:bbsUID',
        'controller' => Controller\IndexController::class,
        'method' => 'index'
    ],
    [
        'path' => '/trace/:bbsUID/:cardUID?:startPostUID/:endPostUID',
        'controller' => Controller\TraceController::class,
        'method' => 'index'
    ],
    [
        'path' => '/post',
        'controller' => Controller\WriteController::class,
        'method' => 'main'
    ],
    [
        'path' => '/list/:bbsUID/:page',
        'controller' => Controller\ListController::class,
        'method' => 'index'
    ]
];
