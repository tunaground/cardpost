<?php

namespace Tunacan\Bundle\Service;

use Tunacan\Bundle\DataObject\CardDAO;
use Tunacan\Bundle\DataObject\CardDTO;
use Tunacan\Bundle\Util\DateTimeBuilder;

class WriteCardService
{
    /** @var DateTimeBuilder */
    private $dateTimeBuilder;
    /** @var CardDAO */
    private $cardDao;

    public function __construct(DateTimeBuilder $dateTimeBuilder, CardDAO $cardDao)
    {
        $this->dateTimeBuilder = $dateTimeBuilder;
        $this->cardDao = $cardDao;
    }

    /**
     * @param CardDTO $cardDTO
     * @return null|int
     * @throws \Exception
     */
    public function writeCard(CardDTO $cardDTO)
    {
        try {
            $this->validateData($cardDTO);
            $cardDTO->setPassword(hash('sha256', $cardDTO->getPassword()));
            $dateTimeNow = $this->dateTimeBuilder->getCurrentUtcDateTime();
            $cardDTO->setOpenDate($dateTimeNow);
            $cardDTO->setRefreshDate($dateTimeNow);
            return $this->cardDao->insertCard($cardDTO);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @throws \Exception
     */
    private function validateData(CardDTO $cardDTO)
    {
        if (mb_strlen($cardDTO->getTitle(), 'utf-8') > 50) {
            throw new \Exception('Title is too long.');
        }
    }
}