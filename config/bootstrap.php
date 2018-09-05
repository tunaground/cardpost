<?php
return [
    \Psr\Container\ContainerInterface::class => \DI\factory(function ($c) {
        return $c;
    }),

    'log.framework.handler' => \DI\get(\Monolog\Handler\StreamHandler::class),
    'log.framework.path' => '/var/log/tunacan/framework.log',
    'log.framework.name' => 'Framework',
    \Tunacan\Bundle\Util\FrameworkLoggerInterface::class => \DI\create(\Monolog\Logger::class)->constructor(\DI\get('log.framework.name'))->method('pushHandler', \DI\get('log.framework.handler')),

    'config.dir' => __DIR__,
    'config.route.file' => 'route.php',
    'config.route.loader' => \DI\factory(function () {
        return new \Tunacan\Util\FileLoader(new \Tunacan\Util\PHPFileReader());
    }),

    'date.format.common' => 'Y-m-d H:i:s',

    'database.query.path' => __DIR__ . '/../asset/query',
    'database.query.loader' => \DI\autowire(\Tunacan\Util\CommonLoader::class)
        ->constructor(new \Tunacan\Util\FileReader())
        ->method('setDirectory', \DI\get('database.query.path'))
        ->method('setExtension', 'sql'),

    'view.template.path' => __DIR__ . '/../asset/template',
    'view.template.loader' => \DI\autowire(\Tunacan\Util\CommonLoader::class)
        ->constructor(new \Tunacan\Util\FileReader())
        ->method('setDirectory', \DI\get('view.template.path'))
        ->method('setExtension', 'html'),

    'view.page.path' => __DIR__ . '/../asset/page',
    'view.page.loader' => \DI\autowire(\Tunacan\Util\CommonLoader::class)
        ->constructor(new \Tunacan\Util\FileReader())
        ->method('setDirectory', \DI\get('view.page.path'))
        ->method('setExtension', 'html'),

    'server.remote.addr' => $_SERVER['REMOTE_ADDR'],
    'server.timezone' => 'Asia/Seoul',

    \Monolog\Handler\StreamHandler::class => \DI\create()->constructor(\DI\get('log.common.path')),
    \Tunacan\Route\RouteCollection::class => \DI\factory(function (\Psr\Container\ContainerInterface $c) {
        $routeLoader = $c->get('config.route.loader');
        return new \Tunacan\Route\RouteCollection($c->get(\Tunacan\Route\RouteBuilder::class), $routeLoader->load($c->get('config.dir') . '/' . $c->get('config.route.file')));
    }),
    \Tunacan\Http\Request::class => \DI\create()->constructor($_SERVER, $_POST, $_GET, $_FILES),

    \Tunacan\Route\RouteCollectionInterface::class => \DI\get(\Tunacan\Route\RouteCollection::class),
    \Tunacan\Core\RequestHandlerInterface::class => \DI\get(\Tunacan\Http\Dispatcher::class),
    \Tunacan\Route\RouterInterface::class => \DI\get(\Tunacan\Route\Router::class),
];
