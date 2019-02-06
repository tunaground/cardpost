<?php
namespace Tunacan\Util;

class FileReader implements ReaderInterface
{
    public function read($path)
    {
        return file_get_contents($path);
    }
}