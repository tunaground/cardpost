<?php

namespace Tunacan\Route;

class RouteCollection implements RouteCollectionInterface
{
    private $routeBuilder;
    private $routeArrays;
    private $key;

    public function __construct(RouteBuilder $routeBuilder, array $routeArrays)
    {
        $this->routeBuilder = $routeBuilder;
        $this->routeArrays = $routeArrays;
        $this->rewind();
    }

    public function current(): RouteInterface
    {
        return $this->routeBuilder->build(
            $this->routeArrays[$this->key]
        );
    }

    public function key()
    {
        return $this->key;
    }

    public function next()
    {
        ++$this->key;
    }

    public function rewind()
    {
        $this->key = 0;
    }

    public function valid(): bool
    {
        return isset($this->routeArrays[$this->key]);
    }
}
