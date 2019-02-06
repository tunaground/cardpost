<?php

namespace Tunacan\Bundle\Component;


class Console
{
    private $consoleString;
    private $consoleList;

    public function __construct(string $consoleString)
    {
        $this->consoleString = $consoleString;
        $this->consoleList = explode('.', $this->consoleString);
    }

    public function hasAaConsole()
    {
        return $this->checkConsoleList('aa');
    }

    public function hasManageConsole()
    {
        return $this->checkConsoleList('manage');
    }

    public function hasOffConsole()
    {
        return $this->checkConsoleList('off');
    }

    public function hasRelayConsole()
    {
        return $this->checkConsoleList('relay');
    }

    private function checkConsoleList(string $needle)
    {
        return (in_array($needle, $this->consoleList, true));
    }
}