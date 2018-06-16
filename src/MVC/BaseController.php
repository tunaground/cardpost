<?php
namespace Tunacan\MVC;

use Psr\Container\ContainerInterface;
use Tunacan\Http\Request;
use Tunacan\Http\Response;

class BaseController implements ControllerInterface
{
    protected $app;
    /** @var Request */
    protected $request;
    /** @var Response */
    protected $response;

    public function __construct(ContainerInterface $container, Request $request, Response $response)
    {
        $this->app = $container;
        $this->request = $request;
        $this->response = $response;
    }
}
