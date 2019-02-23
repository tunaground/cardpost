<?php
namespace Tunacan\Route;

use Psr\Container\ContainerInterface;
use Tunacan\Core\InterceptorInterface;

interface RouteInterface
{
    public function getControllerFqn(): string;

    public function getMethod(): string;

    public function setArguments(array $args);

    public function getArguments(): array;

    public function isRedirect(): bool;

    public function getRedirect(): string;

    public function hasInterceptor(): bool;

    public function getInterceptor(ContainerInterface $c): InterceptorInterface;
}
