<?php
namespace Tunacan\Bundle\Service;

use Tunacan\Bundle\DataObject\CardDto;

interface CardServiceInterface
{
    public function getCardListByBbsUid(string $bbsUid): array;

    public function getCardListByCardUid(int $cardUid): CardDto;

    public function getCardSize(int $cardUid): int;
}
