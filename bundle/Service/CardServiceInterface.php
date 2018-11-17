<?php
namespace Tunacan\Bundle\Service;

use Tunacan\Bundle\DataObject\CardDto;

interface CardServiceInterface
{
    public function getCardListByBbsUid(string $bbsUid, int $page = 1, int $limitCount = 10): array;

    public function getCardByCardUid(int $cardUid): CardDto;

    public function getCardDataOnlyByCardUid(int $cardUid): CardDto;

    public function getCardSize(int $cardUid): int;
}
