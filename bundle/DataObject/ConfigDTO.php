<?php
namespace Tunacan\Bundle\DataObject;

class ConfigDTO
{
    private $configUID;
    private $key;
    private $value;

    public function getConfigUID(): int
    {
        return $this->configUID;
    }

    public function setConfigUID(int $configUID)
    {
        $this->configUID = $configUID;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function setKey(string $key)
    {
        $this->key = $key;
    }
    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value)
    {
        $this->value = $value;
    }
}
