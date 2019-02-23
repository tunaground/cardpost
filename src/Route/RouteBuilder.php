<?php
namespace Tunacan\Route;

class RouteBuilder
{
    public function build($routeArray): RouteInterface
    {
        $route = new Route();
        $route->setPath($routeArray['path']);
        if (array_key_exists('controller', $routeArray)) {
            $route->setControllerFqn($routeArray['controller']);
        }
        if (array_key_exists('method', $routeArray)) {
            $route->setMethod($routeArray['method']);
        }
        if (array_key_exists('option', $routeArray)) {
            $route->setOptions($routeArray['option']);
        }
        if (array_key_exists('redirect', $routeArray)) {
            $route->setRedirect($routeArray['redirect']);
        }
        if (array_key_exists('interceptor', $routeArray)) {
            $route->setInterceptor($routeArray['interceptor']);
        }
        return $route;
    }
}
