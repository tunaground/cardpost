<?php
namespace Tunacan\Http;

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Tunacan\Core\RequestHandlerInterface;
use Tunacan\Route\RouterInterface;

class Dispatcher implements RequestHandlerInterface
{
    /**
     * @var ContainerInterface
     */
    private $delegateContainer;
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(ContainerInterface $container, RouterInterface $router, LoggerInterface $logger = null)
    {
        $this->delegateContainer = $container;
        $this->router = $router;
        $this->logger = $logger;
    }

    public function handle(Request $request)
    {
        $route = $this->router->getRoute($request);
        if ($route->isRedirect()) {
            header("Location: {$route->getRedirect()}");
        } else {
            $controller = $this->delegateContainer->get($route->getControllerFqn());
            $method = $route->getMethod();
            $request->addUriArgumentsList($route->getArguments());
            /** @var Response $response */
            $response = $controller->$method();
            $response->send();
        }
    }
}
