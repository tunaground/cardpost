<?php
namespace Tunacan\Bundle\DataObject;

use Tunacan\Database\DataSourceInterface;
use Tunacan\Util\LoaderInterface;

class CardDAO
{
    /**
     * @var DataSourceInterface
     */
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

    public function getCardListByBbsUID(string $bbsUID, int $startFrom, int $limitCount): array
    {
        $connection = $this->dataSource->getConnection();
        $stmt = $connection->prepare($this->queryLoader->load('getCardDataByBbsUID'));
        $stmt->bindValue(':bbs_uid', $bbsUID, \PDO::PARAM_STR);
        $stmt->bindValue(':start_from', $startFrom, \PDO::PARAM_INT);
        $stmt->bindValue(':limit_count', $limitCount, \PDO::PARAM_INT);
        $stmt->execute();
        $connection = null;
        $error = $stmt->errorInfo();
        if ($error[0] !== '00000') {
            $error = "[{$error[0]}][{$error[1]}] {$error[2]}";
            throw new \PDOException($error);
        }
        if ($stmt->rowCount() > 0) {
            return array_reduce($stmt->fetchAll(\PDO::FETCH_ASSOC), function (array $cardDTOList, array $cardData) {
                $cardDTOList[] = $this->parseToDTO($cardData);
                return $cardDTOList;
            }, []);
        }
        return [];
    }

    public function getCardByCardUID(int $cardUID): CardDTO
    {
        $connection = $this->dataSource->getConnection();
        $stmt = $connection->prepare($this->queryLoader->load('getCardDataByCardUID'));
        $stmt->bindValue(':card_uid', $cardUID, \PDO::PARAM_INT);
        $stmt->execute();
        $connection = null;
        $error = $stmt->errorInfo();
        if ($error[0] !== '00000') {
            $error = "[{$error[0]}][{$error[1]}] {$error[2]}";
            throw new \PDOException($error);
        }
        if ($stmt->rowCount() > 0) {
            $fetch = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $this->parseToDTO($fetch);
        }
        return null;
    }

    /**
     * @param int $cardUID
     * @return int
     * @throws \Exception
     */
    public function getCardSize(int $cardUID): int
    {
        try {
            $connection = $this->dataSource->getConnection();
            $stmt = $connection->prepare($this->queryLoader->load('getCardSize'));
            $stmt->bindValue(':card_uid', $cardUID, \PDO::PARAM_INT);
            $stmt->execute();
        } catch (\Exception $e) {
            throw $e;
        }
        $connection = null;
        $error = $stmt->errorInfo();
        if ($error[0] !== '00000') {
            $error = "[{$error[0]}][{$error[1]}] {$error[2]}";
            throw new \PDOException($error);
        }
        return $stmt->fetchColumn(0);
    }

    public function getCardDataOnlyByCardUID(int $cardUID): CardDTO
    {
        $connection = $this->dataSource->getConnection();
        $stmt = $connection->prepare($this->queryLoader->load('getCardDataOnlyByCardUID'));
        $stmt->bindValue(':card_uid', $cardUID, \PDO::PARAM_INT);
        $stmt->execute();
        $connection = null;
        $error = $stmt->errorInfo();
        if ($error[0] !== '00000') {
            $error = "[{$error[0]}][{$error[1]}] {$error[2]}";
            throw new \PDOException($error);
        }
        if ($stmt->rowCount() > 0) {
            $fetch = $stmt->fetch(\PDO::FETCH_ASSOC);
            $cardDTO = new CardDTO();
            $cardDTO->setCardUID((int) $fetch['card_uid']);
            $cardDTO->setBbsUID($fetch['bbs_uid']);
            $cardDTO->setTitle($fetch['title']);
            $cardDTO->setPassword($fetch['password']);
            $cardDTO->setOpenDate(new \DateTime($fetch['open_date']));
            $cardDTO->setRefreshDate(new \DateTime($fetch['refresh_date']));
            $cardDTO->setDead((bool) $fetch['dead']);
            $cardDTO->setOwnerOnly((bool) $fetch['owner_only']);
            $cardDTO->setStatus($fetch['status']);
            return $cardDTO;
        }
        return null;
    }

    /**
     * @param CardDTO $cardDTO
     * @return null|int
     * @throws \Exception
     */
    public function insertCard(CardDTO $cardDTO)
    {
        $cardUID = null;
        try {
            $connection = $this->dataSource->getConnection();
            $stmt = $connection->prepare($this->queryLoader->load('insertCard'));
            $stmt->bindValue(':bbs_uid', $cardDTO->getBbsUID(),\PDO::PARAM_STR);
            $stmt->bindValue(':title', $cardDTO->getTitle(),\PDO::PARAM_STR);
            $stmt->bindValue(':password', $cardDTO->getPassword(),\PDO::PARAM_STR);
            $stmt->bindValue(':open_date', $cardDTO->getOpenDate()->format('Y-m-d H:i:s'),\PDO::PARAM_STR);
            $stmt->bindValue(':refresh_date', $cardDTO->getRefreshDate()->format('Y-m-d H:i:s'),\PDO::PARAM_STR);
            $stmt->bindValue(':dead', 0, \PDO::PARAM_INT);
            $stmt->bindValue(':owner_only', 0,\PDO::PARAM_INT);
            $stmt->bindValue(':status', 1,\PDO::PARAM_INT);
            $stmt->execute();
            $error = $stmt->errorInfo();
            if ($error[0] !== '00000') {
                $error = "[{$error[0]}][{$error[1]}] {$error[2]}";
                throw new \PDOException($error);
            }
            $cardUID = $connection->lastInsertId();
        } catch (\Exception $e) {
            throw $e;
        } finally {
            $connection = null;
        }
        return $cardUID;
    }

    public function parseToDTO(array $cardData): CardDTO
    {
        $cardDTO = new CardDTO();
        $cardDTO->setCardUID((int) $cardData['card_uid']);
        $cardDTO->setBbsUID($cardData['bbs_uid']);
        $cardDTO->setTitle($cardData['title']);
        $cardDTO->setPassword($cardData['password']);
        $cardDTO->setOpenDate(new \DateTime($cardData['open_date']));
        $cardDTO->setRefreshDate(new \DateTime($cardData['refresh_date']));
        $cardDTO->setDead((bool) $cardData['dead']);
        $cardDTO->setOwnerOnly((bool) $cardData['owner_only']);
        $cardDTO->setStatus($cardData['status']);
        $cardDTO->setOwner($cardData['name']);
        $cardDTO->setSize($cardData['size']);
        return $cardDTO;
    }
}
