<?php

namespace Tunacan\Bundle\Service;

use Tunacan\Bundle\DataObject\CardDTO;

interface CardServiceInterface
{
    public function getCardListByBbsUID(string $bbsUID, int $page = 1, int $limitCount = 10): array;

    public function getCardByCardUID(int $cardUID): CardDTO;

    public function getCardDataOnlyByCardUID(int $cardUID): CardDTO;

    public function getCardSize(int $cardUID): int;
}
