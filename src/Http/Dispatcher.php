<?php
namespace Tunacan\Http;

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Tunacan\Core\RequestHandlerInterface;
use Tunacan\Route\RouterInterface;
use Tunacan\Util\PageResolver;

class Dispatcher implements RequestHandlerInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /** @var PageResolver $resolver */
    private $resolver;

    public function __construct(
        ContainerInterface $container,
        RouterInterface $router,
        PageResolver $resolver,
        LoggerInterface $logger = null
    ) {
        $this->container = $container;
        $this->router = $router;
        $this->resolver = $resolver;
        $this->logger = $logger;
    }

    public function handle(Request $request, Response $response)
    {
        $route = $this->router->getRoute($request);
        if ($route->isRedirect()) {
            header("Location: {$route->getRedirect()}");
            exit;
        } else {
            if ($route->hasInterceptor()) {
                $interceptor = $route->getInterceptor($this->container);
                $interceptor->handle();
            }
            $controller = $route->getController($this->container);
            $request->addUriArgumentsList($route->getArguments());
            $page = $controller->run();
            $response->send();
            echo $this->resolver->resolve($page, $response);
        }
    }
}
