<?php

namespace Tunacan\Util;

class ContextParser
{
    private $format;
    private $prefixSize;
    private $subfixSize;

    public function __construct()
    {
        $this->setFormat('/\{[A-Za-z0-9\_\.]+\}/', 1, -1);
    }

    public function setFormat(string $format, int $prefixSize, int $subfixSize)
    {
        $this->format = $format;
        $this->prefixSize = $prefixSize;
        $this->subfixSize = $subfixSize;
    }

    public function parse(string $message, array $replace): string
    {
        return preg_replace_callback($this->format, function ($matches) use ($replace) {
            $match = substr($matches[0], $this->prefixSize, $this->subfixSize);
            return $replace[$match];
        }, $message);
    }
}
