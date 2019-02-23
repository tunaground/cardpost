<?php
namespace Tunacan\Route;

use Psr\Container\ContainerInterface;
use Tunacan\Core\InterceptorInterface;
use Tunacan\MVC\ControllerInterface;

interface RouteInterface
{
    public function getController(ContainerInterface $c): ControllerInterface;

    public function setArguments(array $args);

    public function getArguments(): array;

    public function isRedirect(): bool;

    public function getRedirect(): string;

    public function hasInterceptor(): bool;

    public function getInterceptor(ContainerInterface $c): InterceptorInterface;
}
