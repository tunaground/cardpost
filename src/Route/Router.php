<?php
namespace Tunacan\Route;

use Tunacan\Http\Request;

class Router implements RouterInterface
{
    /**
     * @var RouteCollectionInterface
     */
    private $routes;

    public function __construct(RouteCollectionInterface $routes)
    {
        $this->routes = $routes;
    }

    /**
     * @param Request $request
     * @return RouteInterface
     * @throws \Exception
     */
    public function getRoute(Request $request): RouteInterface
    {
        try {
            return $this->findRoute($request);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param Request $request
     * @return RouteInterface
     * @throws \Exception
     */
    private function findRoute(Request $request): RouteInterface
    {
        /** @var Route $route */
        foreach ($this->routes as $route) {
            if ($route->match($request)) {
                return $route;
            }
        }
        throw new \Exception('Route not found.');
    }
}
