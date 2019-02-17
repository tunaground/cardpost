<?php
namespace Tunacan\MVC;


use Tunacan\Util\ContextParser;
use Tunacan\Util\LoaderInterface;

abstract class AbstractComponent implements ComponentInterface
{
    protected $parser;
    protected $template;
    protected static $templateName;

    /**
     * @param LoaderInterface $loader
     * @param ContextParser $parser
     */
    public function __construct(LoaderInterface $loader, ContextParser $parser)
    {
        $this->parser = $parser;
        $this->template = $loader->load(static::$templateName);
    }
}