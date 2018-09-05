<?php
namespace Tunacan\Http;

use Tunacan\Util\ContextParser;

class Response
{
    /** @var ContextParser */
    private $parser;
    private $request;
    private $headerList;
    private $body;
    private $attribute;

    public function __construct(Request $request, ContextParser $parser)
    {
        $this->request = $request;
        $this->parser = $parser;
        $this->headerList = [];
        $this->attribute = [];
    }

    public function addHeader(string $headerString)
    {
        $this->headerList[] = $headerString;
    }

    public function setBody(string $body)
    {
        $this->body = $body;
    }

    public function addAttribute(string $key, string $value)
    {
        $this->attribute[$key] = $value;
    }

    public function getAttributeList(): array
    {
        return $this->attribute;
    }

    public function send()
    {
        $this->applyHeader();
//        $this->publishBody();
    }

    private function applyHeader()
    {
        foreach ($this->headerList as $header) {
            header($header);
        }
    }

    private function publishBody()
    {
        echo $this->parser->parse($this->body, $this->attribute);
    }
}
