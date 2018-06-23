<?php
namespace Tunacan\Bundle\Service;

use Tunacan\Bundle\DataObject\CardDao;
use Tunacan\Bundle\DataObject\CardDto;
use Tunacan\Bundle\Util\DateTimeBuilder;

class WriteCardService
{
    /** @var DateTimeBuilder */
    private $dateTimeBuilder;
    /** @var CardDao */
    private $cardDao;

    public function __construct(DateTimeBuilder $dateTimeBuilder, CardDao $cardDao)
    {
        $this->dateTimeBuilder = $dateTimeBuilder;
        $this->cardDao = $cardDao;
    }

    /**
     * @param CardDto $cardDto
     * @return null|int
     * @throws \Exception
     */
    public function writeCard(CardDto $cardDto)
    {
        try {
            $this->validateData($cardDto);
            $cardDto->setPassword(hash('sha256', $cardDto->getPassword()));
            $dateTimeNow = $this->dateTimeBuilder->getCurrentUtcDateTime();
            $cardDto->setOpenDate($dateTimeNow);
            $cardDto->setRefreshDate($dateTimeNow);
            return $this->cardDao->insertCard($cardDto);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @throws \Exception
     */
    private function validateData(CardDto $cardDto)
    {
        if (mb_strlen($cardDto->getTitle(), 'utf-8') > 50) {
            throw new \Exception('Title is too long.');
        }
    }
}