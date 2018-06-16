<?php
namespace Tunacan\Bundle\Service;

use Tunacan\Bundle\DataObject\CardDto;

interface WriteCardServiceInterface
{
    public function writeCard(CardDto $cardDto);
}