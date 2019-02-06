<?php

namespace Tunacan\Bundle\Service;

interface ManagementServiceInterface
{
    public function apply(int $cardUID, string $data);
}