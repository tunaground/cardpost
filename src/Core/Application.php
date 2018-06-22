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
        try {
            $this->handler = $this->get(RequestHandlerInterface::class);
        } catch (\Exception $e) {
            header('HTTP/1.1 500 Internal Server Error');
            echo "에러 발생({$e->getMessage()})";
        }
    }

    public function run()
    {
        try {
            $this->handler->handle($this->get(Request::class));
        } catch (\Exception $e) {
            header('HTTP/1.1 500 Internal Server Error');
            echo "에러 발생({$e->getMessage()})";
        }
    }
}
