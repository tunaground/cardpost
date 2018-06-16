<?php
namespace Tunacan\Bundle\DataObject;

use Tunacan\Database\DataSourceInterface;
use Tunacan\Util\LoaderInterface;

class CardDao
{
    private $dataSource;
    /**
     * @Inject("database.query.loader")
     * @var LoaderInterface
     */
    private $queryLoader;

    public function __construct(DataSourceInterface $dataSource)
    {
        $this->dataSource = $dataSource;
    }

    public function getCardListByBbsUid(string $bbsUid): array
    {
        $connection = $this->dataSource->getConnection();
        $stmt = $connection->prepare($this->queryLoader->load('getCardDataByBbsUid'));
        $stmt->bindValue(':bbs_uid', $bbsUid, \PDO::PARAM_STR);
        $stmt->execute();
        $connection = null;
        $error = $stmt->errorInfo();
        if ($error[0] !== '00000') {
            $error = "[{$error[0]}][{$error[1]}] {$error[2]}";
            throw new \PDOException($error);
        }
        if ($stmt->rowCount() > 0) {
            return array_reduce($stmt->fetchAll(\PDO::FETCH_ASSOC), function (array $cardDtoList, array $cardData) {
                $cardDtoList[] = $this->parseToDto($cardData);
                return $cardDtoList;
            }, []);
        }
        return [];
    }

    public function getCardByCardUid(int $cardUid): CardDto
    {
        $connection = $this->dataSource->getConnection();
        $stmt = $connection->prepare($this->queryLoader->load('getCardDataByCardUid'));
        $stmt->bindValue(':card_uid', $cardUid, \PDO::PARAM_INT);
        $stmt->execute();
        $connection = null;
        $error = $stmt->errorInfo();
        if ($error[0] !== '00000') {
            $error = $error[0] . ':' . $error[1];
            throw new \PDOException($error);
        }
        if ($stmt->rowCount() > 0) {
            $fetch = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $this->parseToDto($fetch);
        }
        return null;
    }

    /**
     * @param int $cardUid
     * @return int
     * @throws \Exception
     */
    public function getCardSize(int $cardUid): int
    {
        try {
            $connection = $this->dataSource->getConnection();
            $stmt = $connection->prepare($this->queryLoader->load('getCardSize'));
            $stmt->bindValue(':card_uid', $cardUid, \PDO::PARAM_INT);
            $stmt->execute();
        } catch (\Exception $e) {
            throw $e;
        }
        $connection = null;
        $error = $stmt->errorInfo();
        if ($error[0] !== '00000') {
            throw new \PDOException($error[0] . ':' . $error[1]);
        }
        return $stmt->fetchColumn(0);
    }

    public function parseToDto(array $cardData): CardDto
    {
        $cardDto = new CardDto();
        $cardDto->setCardUid((int) $cardData['card_uid']);
        $cardDto->setBbsUid($cardData['bbs_uid']);
        $cardDto->setTitle($cardData['title']);
        $cardDto->setPassword($cardData['password']);
        $cardDto->setOpenDate(new \DateTime($cardData['open_date']));
        $cardDto->setRefreshDate(new \DateTime($cardData['refresh_date']));
        $cardDto->setDead((bool) $cardData['dead']);
        $cardDto->setOwnerOnly((bool) $cardData['owner_only']);
        $cardDto->setStatus($cardData['status']);
        $cardDto->setOwner($cardData['name']);
        $cardDto->setSize($cardData['size']);
        return $cardDto;
    }
}
