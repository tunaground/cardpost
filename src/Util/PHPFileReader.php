<?php

namespace Tunacan\Util;

class PHPFileReader implements ReaderInterface
{
    public function read($path)
    {
        return include($path);
    }
}