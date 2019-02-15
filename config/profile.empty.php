<?php
return [
    // Logger
    'log.common.handler' => \DI\get(\Monolog\Handler\StreamHandler::class),
    'log.common.path' => __DIR__ . '/../logs/common.log',
    'log.common.name' => 'Common',
    \Psr\Log\LoggerInterface::class => \DI\create(\Monolog\Logger::class)
        ->constructor(\DI\get('log.common.name'))
        ->method('pushHandler', \DI\get('log.common.handler')),

    // Database
    'database.mysql.type' => 'mysql',
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
    \Tunacan\Database\DataSourceInterface::class => \DI\get(\Tunacan\Database\DataSource::class),

    // Storage
    'storage.s3.bucket' => '',
    \Aws\S3\S3Client::class => \DI\factory(function (\Psr\Container\ContainerInterface $c) {
        /** @var \Tunacan\Bundle\DataObject\ConfigDAO $config */
        $config = $c->get('\Tunacan\Bundle\DataObject\ConfigDAO');
        return new \Aws\S3\S3Client([
            'region' => $config->getConfigByKey('storage.s3.region'),
            'version' => $config->getConfigByKey('storage.s3.version'),
            'credentials' => [
                'key' => $config->getConfigByKey('storage.s3.key'),
                'secret' => $config->getConfigByKey('storage.s3.secret')
            ]
        ]);
    }),

    'tmp.domain.image' => '',
    \Tunacan\Bundle\Component\StorageInterface::class => \DI\get(\Tunacan\Bundle\Component\DiscStorage::class),

    // Services
    \Tunacan\Bundle\Service\CardServiceInterface::class => \DI\get(\Tunacan\Bundle\Service\CardService::class),
    \Tunacan\Bundle\Service\PostServiceInterface::class => \DI\get(\Tunacan\Bundle\Service\PostService::class),
    \Tunacan\Bundle\Service\WriteCardServiceInterface::class => \DI\get(\Tunacan\Bundle\Service\WriteCardService::class),
    \Tunacan\Bundle\Service\WritePostServiceInterface::class => \DI\get(\Tunacan\Bundle\Service\WritePostService::class),
    \Tunacan\Bundle\Service\FileUploadServiceInterface::class => \DI\get(\Tunacan\Bundle\Service\FileUploadService::class),
    \Tunacan\Bundle\Service\ManagementServiceInterface::class => \DI\get(\Tunacan\Bundle\Service\ManagementService::class),
];

