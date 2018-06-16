<?php
namespace Tunacan\Util;

class CommonLoader extends AbstractLoader
{
    private $directory;
    private $extension;

    public function setDirectory(string $directory)
    {
        $this->directory = $directory;
    }

    public function setExtension(string $extension)
    {
        $this->extension = $extension;
    }

    public function load($path)
    {
        $includePath = sprintf('%s/%s.%s', $this->directory, $path, $this->extension);
        if (is_file($includePath)) {
            return $this->reader->read($includePath);
        } else {
            throw new \Exception(sprintf('Invalid Path: %s', $includePath));
        }
    }
}
