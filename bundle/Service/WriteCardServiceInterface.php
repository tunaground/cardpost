<?php

namespace Tunacan\Bundle\Service;

use Tunacan\Bundle\DataObject\CardDTO;

interface WriteCardServiceInterface
{
    public function writeCard(CardDTO $cardDTO);
}