<?php

namespace Tunacan\Bundle\Component\Management;

interface CommandInterface
{
    /**
     * @throws \Exception
     */
    public function execute();
}