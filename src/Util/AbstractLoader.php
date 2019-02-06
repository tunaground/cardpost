<?php

namespace Tunacan\Util;

abstract class AbstractLoader implements LoaderInterface
{
    /**
     * @var ParserInterface
     */
    protected $parser;
    /**
     * @var ReaderInterface
     */
    protected $reader;

    public function __construct(ReaderInterface $reader, ParserInterface $parser = null)
    {
        $this->parser = $parser;
        $this->reader = $reader;
    }
}