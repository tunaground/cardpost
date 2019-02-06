<?php
namespace Tunacan\Bundle\Service;

use Tunacan\Bundle\DataObject\CardDAO;
use Tunacan\Bundle\DataObject\CardDTO;

class CardService implements CardServiceInterface
{
    /** @var CardDAO */
    private $cardDao;

    public function __construct(CardDAO $cardDao)
    {
        $this->cardDao = $cardDao;
    }

    public function getCardListByBbsUID(string $bbsUID, int $page = 1, int $limitCount = 10): array
    {
        $page = ($page < 1)? 1 : $page;
        $startFrom = ($page - 1) * $limitCount;
        return $this->cardDao->getCardListByBbsUID($bbsUID, $startFrom, $limitCount);
    }

    public function getCardByCardUID(int $cardUID): CardDTO
    {
        return $this->cardDao->getCardByCardUID($cardUID);
    }

    public function getCardDataOnlyByCardUID(int $cardUID): CardDTO
    {
        return $this->cardDao->getCardDataOnlyByCardUID($cardUID);
    }

    /**
     * @param int $cardUID
     * @return int
     * @throws \Exception
     */
    public function getCardSize(int $cardUID): int
    {
        try {
            return $this->cardDao->getCardSize($cardUID);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
