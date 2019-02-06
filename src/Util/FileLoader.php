<?php
namespace Tunacan\Util;


class FileLoader extends AbstractLoader
{
    public function load($path)
    {
        return $this->reader->read($path);
    }
}
