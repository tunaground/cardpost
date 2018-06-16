<?php
namespace Tunacan\Core;

use DI\Container;
use DI\Definition\Source\MutableDefinitionSource;
use DI\Proxy\ProxyFactory;
use Psr\Container\ContainerInterface;
use Tunacan\Http\Request;

class Application extends Container
{
    /**
     * @Inject
     * @var RequestHandlerInterface
     */
    private $handler;

    public function __construct(
        MutableDefinitionSource $definitionSource = null,
        ProxyFactory $proxyFactory = null,
        ContainerInterface $wrapperContainer = null
    ) {
        parent::__construct($definitionSource, $proxyFactory, $wrapperContainer);
        $this->handler = $this->get(RequestHandlerInterface::class);
    }

    public function run()
    {
        $this->handler->handle($this->get(Request::class));
    }
}
