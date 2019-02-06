<?php
namespace Tunacan\Bundle\DataObject;

use Tunacan\Database\DataSourceInterface;
use Tunacan\Util\LoaderInterface;

class DenyDAO
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

    /**
     * @param DenyDTO $denyDTO
     * @return int
     * @throws \Exception
     */
    public function insertDeny(DenyDTO $denyDTO): int
    {
        $denyUID = null;
        try {
            $connection = $this->dataSource->getConnection();
            $stmt = $connection->prepare($this->queryLoader->load('insertDeny'));
            $stmt->bindValue(':card_uid', $denyDTO->getCardUid(), \PDO::PARAM_INT);
            $stmt->bindValue(':user_id', $denyDTO->getUserId(), \PDO::PARAM_STR);
            $stmt->bindValue(':create_date', $denyDTO->getCreateDate()->format('Y-m-d H:i:s'), \PDO::PARAM_STR);
            $stmt->bindValue(':status', 1, \PDO::PARAM_INT);
            $stmt->execute();
            $error = $stmt->errorInfo();
            if ($error[0] !== '00000') {
                throw new \PDOException($error[0] . ':' . $error[1]);
            }
            $denyUID = $connection->lastInsertId();
        } catch (\Exception $e) {
            throw $e;
        } finally {
            $connection = null;
        }
        return $denyUID;
    }

    /**
     * @param string $cardUID
     * @param string $userID
     * @return bool
     * @throws \Exception
     */
    public function checkDeny(string $cardUID, string $userID): bool
    {
        try {
            $connection = $this->dataSource->getConnection();
            $stmt = $connection->prepare($this->queryLoader->load('checkDeny'));
            $stmt->bindValue(':card_uid', $cardUID, \PDO::PARAM_INT);
            $stmt->bindValue(':user_id', $userID, \PDO::PARAM_STR);
            $stmt->bindValue(':status', 1, \PDO::PARAM_INT);
            $stmt->execute();
            $error = $stmt->errorInfo();
            if ($error[0] !== '00000') {
                throw new \PDOException($error[0] . ':' . $error[1]);
            }
            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            throw $e;
        } finally {
            $connection = null;
        }
    }
}