<?php

namespace Tunacan\Route;

class RouteBuilder
{
    public function build($routeArray): RouteInterface
    {
        $route = new Route();
        $route->setPath($routeArray['path']);
        $route->setControllerFqn($routeArray['controller']);
        $route->setMethod($routeArray['method']);
        $route->setOptions($routeArray['option']);
        $route->setRedirect($routeArray['redirect']);
        return $route;
    }
}
