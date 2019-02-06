<?php
namespace Tunacan\Bundle\Component\Management;

use Tunacan\Bundle\DataObject\DenyDAO;
use Tunacan\Bundle\DataObject\DenyDTO;

class DenyCommand implements CommandInterface
{
    /** @var DenyDAO */
    private $denyDAO;
    private $cardUID;
    private $userID;
    private $createDate;

    public function __construct(DenyDAO $denyDAO, int $cardUID, string $userID, \DateTime $createDate)
    {
        $this->denyDAO = $denyDAO;
        $this->cardUID = $cardUID;
        $this->userID = $userID;
        $this->createDate = $createDate;
    }

    /**
     * @throws \Exception
     */
    public function execute()
    {
        try {
            $denyDTO = new DenyDTO();
            $denyDTO->setCardUid($this->cardUID);
            $denyDTO->setUserId($this->userID);
            $denyDTO->setCreateDate($this->createDate);
            $denyDTO->setStatus(1);
            $this->denyDAO->insertDeny($denyDTO);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}