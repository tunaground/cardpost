<?php
return [
    // Logger
    'log.common.handler' => \DI\get(\Monolog\Handler\StreamHandler::class),
    'log.common.path' => '',
    'log.common.name' => '',
    \Psr\Log\LoggerInterface::class => \DI\create(\Monolog\Logger::class)
        ->constructor(\DI\get('log.common.name'))
        ->method('pushHandler', \DI\get('log.common.handler')),

    // Database
    'database.mysql.type' => '',
    'database.mysql.host' => '',
    'database.mysql.port' => '',
    'database.mysql.dbname' => '',
    'database.mysql.user' => '',
    'database.mysql.password' => '',
    'database.mysql.option' => [\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'],
    \Tunacan\Database\DataSource::class => \DI\factory(function (\Psr\Container\ContainerInterface $c) {
        return new \Tunacan\Database\DataSource(
            $c->get('database.mysql.type'),
            $c->get('database.mysql.host'),
            $c->get('database.mysql.port'),
            $c->get('database.mysql.dbname'),
            $c->get('database.mysql.user'),
            $c->get('database.mysql.password'),
            $c->get('database.mysql.option')
        );
    }),

    // Storage
    'storage.s3.region' => '',
    'storage.s3.version' => '',
    'storage.s3.key' => '',
    'storage.s3.secret' => '',
    'storage.s3.bucket' => '',
    \Aws\S3\S3Client::class => \DI\create()->constructor([
        'region' => \DI\get('storage.s3.region'),
        'version' => \DI\get('storage.s3.version'),
        'credentials' => [
            'key' => \DI\get('storage.s3.key'),
            'secret' => \DI\get('storage.s3.secret')
        ]
    ]),
    \Tunacan\Bundle\Component\StorageInterface::class => \DI\create(\Tunacan\Bundle\Component\S3Storage::class)
        ->constructor(\DI\get(\Aws\S3\S3Client::class))
        ->method('setBucket', \DI\get('storage.s3.bucket')),

    // Services
    \Tunacan\Bundle\Service\CardServiceInterface::class => \DI\get(\Tunacan\Bundle\Service\CardService::class),
    \Tunacan\Bundle\Service\PostServiceInterface::class => \DI\get(\Tunacan\Bundle\Service\PostService::class),
    \Tunacan\Bundle\Service\WriteServiceInterface::class => \DI\get(\Tunacan\Bundle\Service\WriteService::class),
    \Tunacan\Bundle\Service\FileUploadServiceInterface::class => \DI\get(\Tunacan\Bundle\Service\FileUploadService::class)
];
