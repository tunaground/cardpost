<?php
try {
    $builder = new \DI\ContainerBuilder(\Tunacan\Core\Application::class);
    $builder->addDefinitions(
        array_merge(
            include('../config/bootstrap.php'),
            include('../config/profile.php')
        )
    );
    $builder->useAutowiring(true);
    $builder->useAnnotations(true);
    return $builder->build();
} catch (Exception $e) {
    header('HTTP/1.1 500 Internal Server Error');
    die;
}