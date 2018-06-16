<?php
namespace Tunacan\MVC;


use Tunacan\Util\ContextParser;
use Tunacan\Util\LoaderInterface;

abstract class AbstractComponent implements ComponentInterface
{
    protected $htmlTemplateName;
    protected $htmlTemplate;
    protected $parser;

    /**
     * @Inject({"loader" = "view.template.loader"})
     * @param LoaderInterface $loader
     * @param ContextParser $parser
     */
    public function __construct(LoaderInterface $loader, ContextParser $parser)
    {
        $this->parser = $parser;
        $this->htmlTemplate = $loader->load($this->htmlTemplateName);
    }
}