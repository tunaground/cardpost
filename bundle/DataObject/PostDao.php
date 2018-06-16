<?php
namespace Tunacan\Bundle\DataObject;

use Tunacan\Database\DataSourceInterface;
use Tunacan\Bundle\Component\Content;
use Tunacan\Util\LoaderInterface;

class PostDao
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

    public function getLastPostOrder(int $cardUid): int
    {
        $connection = $this->dataSource->getConnection();
        $stmt = $connection->prepare($this->queryLoader->load('getLastPostOrder'));
        $stmt->bindValue(':card_uid', $cardUid, \PDO::PARAM_INT);
        $stmt->execute();
        $connection = null;
        $error = $stmt->errorInfo();
        if ($error[0] !== '00000') {
            $error = $error[0] . ':' . $error[1];
            throw new \PDOException($error);
        }
        if ($stmt->rowCount() > 0) {
            return $stmt->fetchColumn();
        }
        return 0;
    }

    public function getPostByCardUid(int $cardUid): array
    {
        $connection = $this->dataSource->getConnection();
        $stmt = $connection->prepare($this->queryLoader->load('getPostByCardUid'));
        $stmt->bindValue(':card_uid', $cardUid);
        $stmt->execute();
        $connection = null;
        $error = $stmt->errorInfo();
        if ($error[0] !== '00000') {
            $error = $error[0] . ':' . $error[1];
            throw new \PDOException($error);
        }
        if ($stmt->rowCount() > 0) {
            return array_reduce($stmt->fetchAll(\PDO::FETCH_ASSOC), function (array $postDtoList, array $postData) {
                $postDtoList[] = $this->parseToDto($postData);
                return $postDtoList;
            }, []);
        }
        return [];
    }

    public function getPostWithLimit(int $cardUid, int $start, int $count): array
    {
        $connection = $this->dataSource->getConnection();
        $stmt = $connection->prepare($this->queryLoader->load('getPostWithLimit'));
        $stmt->bindValue(':card_uid', $cardUid, \PDO::PARAM_INT);
        $stmt->bindValue(':start', $start, \PDO::PARAM_INT);
        $stmt->bindValue(':count', $count, \PDO::PARAM_INT);
        $stmt->execute();
        $connection = null;
        $error = $stmt->errorInfo();
        if ($error[0] !== '00000') {
            $error = $error[0] . ':' . $error[1];
            throw new \PDOException($error);
        }
        if ($stmt->rowCount() > 0) {
            return array_reduce($stmt->fetchAll(\PDO::FETCH_ASSOC), function (array $postDtoList, array $postData) {
                $postDtoList[] = $this->parseToDto($postData);
                return $postDtoList;
            }, []);
        }
        return [];
    }

    /**
     * @param int $cardUid
     * @param int $postOrder
     * @return PostDto|null
     * @throws \Exception
     */
    public function getPostByPostOrder(int $cardUid, int $postOrder): PostDto
    {
        $connection = $this->dataSource->getConnection();
        $stmt = $connection->prepare($this->queryLoader->load('getPostByPostOrder'));
        $stmt->bindValue(':card_uid', $cardUid, \PDO::PARAM_INT);
        $stmt->bindValue(':post_order', $postOrder, \PDO::PARAM_INT);
        $stmt->execute();
        $connection = null;
        $error = $stmt->errorInfo();
        if ($error[0] !== '00000') {
            $error = $error[0] . ':' . $error[1];
            throw new \Exception($error);
        }
        if ($stmt->rowCount() > 0) {
            $fetch = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $this->parseToDto($fetch);
        }
        return null;
    }

    /**
     * @param PostDto $postDto
     * @return null|string
     * @throws \Exception
     */
    public function InsertPost(PostDto $postDto)
    {
        $postUid = null;
        try {
            $connection = $this->dataSource->getConnection();
            $stmt = $connection->prepare($this->queryLoader->load('insertPost'));
            $stmt->bindValue(':card_uid', $postDto->getCardUid(), \PDO::PARAM_INT);
            $stmt->bindValue(':bbs_uid', $postDto->getBbsUid(), \PDO::PARAM_STR);
            $stmt->bindValue(':post_order', $postDto->getOrder(), \PDO::PARAM_INT);
            $stmt->bindValue(':name', $postDto->getName(), \PDO::PARAM_STR);
            $stmt->bindValue(':user_id', $postDto->getUserId(), \PDO::PARAM_STR);
            $stmt->bindValue(':create_date', $postDto->getCreatedate()->format('Y-m-d H:i:s'), \PDO::PARAM_STR);
            $stmt->bindValue(':content', $postDto->getContent(), \PDO::PARAM_STR);
            $stmt->bindValue(':image', $postDto->getImage(), \PDO::PARAM_STR);
            $stmt->bindValue(':ip', $postDto->getIp(), \PDO::PARAM_STR);
            $stmt->bindValue(':status', 1, \PDO::PARAM_INT);
            $stmt->execute();
            $error = $stmt->errorInfo();
            if ($error[0] !== '00000') {
                throw new \PDOException($error[0] . ':' . $error[1]);
            }
            $postUid = $connection->lastInsertId();
        } catch (\Exception $e) {
            throw $e;
        } finally {
            $connection = null;
        }
        return $postUid;
    }

    private function parseToDto(array $postData): PostDTO
    {
        $postDto = new PostDto();
        $postDto->setPostUid($postData['post_uid']);
        $postDto->setCardUid($postData['card_uid']);
        $postDto->setBbsUid($postData['bbs_uid']);
        $postDto->setOrder($postData['post_order']);
        $postDto->setName($postData['name']);
        $postDto->setUserId($postData['user_id']);
        $postDto->setCreatedate(new \DateTime($postData['create_date']));
        $postDto->setContent(new Content($postData['content']));
        $postDto->setImage($postData['image']);
        $postDto->setIp($postData['ip']);
        $postDto->setStatus($postData['status']);
        return $postDto;
    }
}
