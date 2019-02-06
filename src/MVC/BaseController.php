<?php
namespace Tunacan\MVC;

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Tunacan\Http\Request;
use Tunacan\Http\Response;

class BaseController implements ControllerInterface
{
    protected $app;
    /** @var Request */
    protected $request;
    /** @var Response */
    protected $response;
    /** @var LoggerInterface */
    protected $logger;

    public function __construct(
        ContainerInterface $container,
        Request $request,
        Response $response,
        LoggerInterface $logger
    ) {
        $this->app = $container;
        $this->request = $request;
        $this->response = $response;
        $this->logger = $logger;
    }
}
