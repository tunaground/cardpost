<?php

namespace Tunacan\Bundle\Component;


interface StorageInterface
{
    public function get(string $key);

    public function put(string $key, $file);
}