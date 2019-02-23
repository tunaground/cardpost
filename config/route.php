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
    ],
    [
        'path' => '/trace/:bbsUID/:cardUID?:startPostUID/:endPostUID',
        'controller' => Controller\TraceController::class,
    ],
    [
        'path' => '/post',
        'controller' => Controller\WriteController::class,
    ],
    [
        'path' => '/list/:bbsUID/:page',
        'controller' => Controller\ListController::class,
    ]
];
