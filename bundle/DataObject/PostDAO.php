<?php

namespace Tunacan\Bundle\DataObject;

use Tunacan\Database\DataSourceInterface;
use Tunacan\Bundle\Component\Content;
use Tunacan\Util\LoaderInterface;

class PostDAO
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

    public function getLastPostOrder(int $cardUID): int
    {
        $connection = $this->dataSource->getConnection();
        $stmt = $connection->prepare($this->queryLoader->load('getLastPostOrder'));
        $stmt->bindValue(':card_uid', $cardUID, \PDO::PARAM_INT);
        $stmt->execute();
        $connection = null;
        $error = $stmt->errorInfo();
        if ($error[0] !== '00000') {
            $error = "[{$error[0]}][{$error[1]}] {$error[2]}";
            throw new \PDOException($error);
        }
        if ($stmt->rowCount() > 0) {
            return ($stmt->fetchColumn()) ?: 0;
        }
        return 0;
    }

    public function getPostByCardUID(int $cardUID): array
    {
        $connection = $this->dataSource->getConnection();
        $stmt = $connection->prepare($this->queryLoader->load('getPostByCardUID'));
        $stmt->bindValue(':card_uid', $cardUID);
        $stmt->execute();
        $connection = null;
        $error = $stmt->errorInfo();
        if ($error[0] !== '00000') {
            $error = "[{$error[0]}][{$error[1]}] {$error[2]}";
            throw new \PDOException($error);
        }
        if ($stmt->rowCount() > 0) {
            return array_reduce($stmt->fetchAll(\PDO::FETCH_ASSOC), function (array $postDTOList, array $postData) {
                $postDTOList[] = $this->parseToDTO($postData);
                return $postDTOList;
            }, []);
        }
        return [];
    }

    public function getPostWithLimit(int $cardUID, int $start, int $count): array
    {
        $connection = $this->dataSource->getConnection();
        $stmt = $connection->prepare($this->queryLoader->load('getPostWithLimit'));
        $stmt->bindValue(':card_uid', $cardUID, \PDO::PARAM_INT);
        $stmt->bindValue(':start', $start, \PDO::PARAM_INT);
        $stmt->bindValue(':count', $count, \PDO::PARAM_INT);
        $stmt->execute();
        $connection = null;
        $error = $stmt->errorInfo();
        if ($error[0] !== '00000') {
            $error = "[{$error[0]}][{$error[1]}] {$error[2]}";
            throw new \PDOException($error);
        }
        if ($stmt->rowCount() > 0) {
            return array_reduce($stmt->fetchAll(\PDO::FETCH_ASSOC), function (array $postDTOList, array $postData) {
                $postDTOList[] = $this->parseToDTO($postData);
                return $postDTOList;
            }, []);
        }
        return [];
    }

    /**
     * @param int $cardUID
     * @param int $postOrder
     * @return PostDTO|null
     * @throws \Exception
     */
    public function getPostByPostOrder(int $cardUID, int $postOrder): PostDTO
    {
        $connection = $this->dataSource->getConnection();
        $stmt = $connection->prepare($this->queryLoader->load('getPostByPostOrder'));
        $stmt->bindValue(':card_uid', $cardUID, \PDO::PARAM_INT);
        $stmt->bindValue(':post_order', $postOrder, \PDO::PARAM_INT);
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
     * @param PostDTO $postDTO
     * @return null|string
     * @throws \Exception
     */
    public function InsertPost(PostDTO $postDTO)
    {
        $postUID = null;
        try {
            $connection = $this->dataSource->getConnection();
            $stmt = $connection->prepare($this->queryLoader->load('insertPost'));
            $stmt->bindValue(':card_uid', $postDTO->getCardUID(), \PDO::PARAM_INT);
            $stmt->bindValue(':bbs_uid', $postDTO->getBbsUID(), \PDO::PARAM_STR);
            $stmt->bindValue(':post_order', $postDTO->getOrder(), \PDO::PARAM_INT);
            $stmt->bindValue(':name', $postDTO->getName(), \PDO::PARAM_STR);
            $stmt->bindValue(':user_id', $postDTO->getUserID(), \PDO::PARAM_STR);
            $stmt->bindValue(':create_date', $postDTO->getCreateDate()->format('Y-m-d H:i:s'), \PDO::PARAM_STR);
            $stmt->bindValue(':content', $postDTO->getContent(), \PDO::PARAM_STR);
            $stmt->bindValue(':image', $postDTO->getImage(), \PDO::PARAM_STR);
            $stmt->bindValue(':ip', $postDTO->getIp(), \PDO::PARAM_STR);
            $stmt->bindValue(':status', 1, \PDO::PARAM_INT);
            $stmt->execute();
            $error = $stmt->errorInfo();
            if ($error[0] !== '00000') {
                $error = "[{$error[0]}][{$error[1]}] {$error[2]}";
                throw new \PDOException($error);
            }
            $postUID = $connection->lastInsertId();
        } catch (\Exception $e) {
            throw $e;
        } finally {
            $connection = null;
        }
        return $postUID;
    }

    /**
     * @param int $postUID
     * @param int $status
     * @throws \Exception
     */
    public function updatePostStatus(int $postUID, int $status)
    {
        $connection = $this->dataSource->getConnection();
        $stmt = $connection->prepare($this->queryLoader->load('updatePostStatus'));
        $stmt->bindValue(':post_uid', $postUID, \PDO::PARAM_INT);
        $stmt->bindValue(':status', $status, \PDO::PARAM_INT);
        $stmt->execute();
        $connection = null;
        $error = $stmt->errorInfo();
        if ($error[0] !== '00000') {
            $error = "[{$error[0]}][{$error[1]}] {$error[2]}";
            throw new \PDOException($error);
        }
    }

    private function parseToDTO(array $postData): PostDTO
    {
        $postDTO = new PostDTO();
        $postDTO->setPostUID($postData['post_uid']);
        $postDTO->setCardUID($postData['card_uid']);
        $postDTO->setBbsUID($postData['bbs_uid']);
        $postDTO->setOrder($postData['post_order']);
        $postDTO->setName($postData['name']);
        $postDTO->setUserID($postData['user_id']);
        $postDTO->setCreateDate(new \DateTime($postData['create_date']));
        $postDTO->setContent(new Content($postData['content']));
        $postDTO->setImage($postData['image']);
        $postDTO->setIp($postData['ip']);
        $postDTO->setStatus($postData['status']);
        return $postDTO;
    }
}
