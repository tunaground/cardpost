<?php
namespace Tunacan\Http;

class Request
{
    private $server;
    private $postParamList;
    private $queryParamList;
    private $fileList;
    private $uriArguments;

    public function __construct(array $server, array $postParamList, array $queryParamList, array $fileList)
    {
        $this->server = $server;
        $this->postParamList = $postParamList;
        $this->queryParamList = $queryParamList;
        $this->fileList = $fileList;
        $this->uriArguments = [];
    }

    public function getServerInfo(string $key)
    {
        return $this->server[$key];
    }

    public function getPostParam(string $key)
    {
        return $this->postParamList[$key];
    }

    public function getQueryParam(string $key)
    {
        return $this->queryParamList[$key];
    }

    public function getFile(string $key)
    {
        return $this->fileList[$key];
    }

    public function addUriArgumentsList(array $uriArguments)
    {
        $this->uriArguments = array_merge($this->uriArguments, $uriArguments);
    }

    public function addUriArguments(string $key, string $value)
    {
        $this->uriArguments[$key] = $value;
    }

    public function getUriArguments(string $key): string
    {
        return $this->uriArguments[$key];
    }
}
