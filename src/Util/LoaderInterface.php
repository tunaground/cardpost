<?php
namespace Tunacan\Util;

interface LoaderInterface
{
    public function __construct(ReaderInterface $reader, ParserInterface $parser = null);
    public function load($path);
}