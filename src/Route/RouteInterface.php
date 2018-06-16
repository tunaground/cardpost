<?php
namespace Tunacan\Route;

interface RouteInterface
{
    public function getControllerFqn(): string;
    public function getMethod(): string;
    public function setArguments(array $args );
    public function getArguments(): array;
    public function isRedirect(): bool;
    public function getRedirect(): string;
}
