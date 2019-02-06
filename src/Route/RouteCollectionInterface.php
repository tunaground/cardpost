<?php
namespace Tunacan\Route;

interface RouteCollectionInterface extends \Iterator
{
    public function __construct(RouteBuilder $routeBuilder, array $routeArray);
}
