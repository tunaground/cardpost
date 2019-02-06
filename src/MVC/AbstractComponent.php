<?php

namespace Tunacan\MVC;


use Tunacan\Util\ContextParser;
use Tunacan\Util\LoaderInterface;

abstract class AbstractComponent implements ComponentInterface
{
    protected $htmlTemplateName;
    protected $htmlTemplate;
    protected $loader;
    protected $parser;

    /**
     * @Inject({"loader" = "view.template.loader"})
     * @param LoaderInterface $loader
     * @param ContextParser $parser
     */
    public function __construct(LoaderInterface $loader, ContextParser $parser)
    {
        $this->loader = $loader;
        $this->parser = $parser;
    }
}