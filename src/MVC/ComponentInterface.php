<?php
namespace Tunacan\MVC;

use Tunacan\Util\ContextParser;
use Tunacan\Util\LoaderInterface;

interface ComponentInterface
{
    public function __construct(LoaderInterface $loader, ContextParser $parser);

    public function __toString();
}