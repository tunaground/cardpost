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
        'path' => [
            '/trace/{bbsUid}/{cardUid}',
            '/trace/{bbsUid}/{cardUid}/recent',
            '/trace/{bbsUid}/{cardUid}/{startPostUid}',
            '/trace/{bbsUid}/{cardUid}/{startPostUid}/{endPostUid}'
        ],
        'controller' => Controller\TraceController::class,
        'method' => 'index'
    ],
    [
        'path' => '/post',
        'controller' => Controller\WriteController::class,
        'method' => 'main'
    ]
];
