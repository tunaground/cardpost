<?php

namespace Tunacan\Util;

use Tunacan\Http\Response;

class PageResolver
{
    /** @var LoaderInterface $loader */
    private $loader;
    /** @var ContextParser $parser */
    private $parser;

    /**
     * @Inject({"loader" = "view.page.loader"})
     * @param LoaderInterface $loader
     * @param ContextParser $parser
     */
    public function __construct(LoaderInterface $loader, ContextParser $parser)
    {
        $this->loader = $loader;
        $this->parser = $parser;
    }

    public function resolve(string $page, Response $response)
    {
        return $this->parser->parse($this->loader->load($page), $response->getAttributeList());
    }
}