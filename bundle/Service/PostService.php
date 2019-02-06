<?php

namespace Tunacan\Bundle\Service;

use Tunacan\Bundle\DataObject\PostDAO;
use Tunacan\Bundle\DataObject\PostDTO;

class PostService implements PostServiceInterface
{
    /** @var PostDAO */
    private $postDao;

    public function __construct(PostDAO $postDao)
    {
        $this->postDao = $postDao;
    }

    public function getLastPostOrder(int $cardUID): int
    {
        return $this->postDao->getLastPostOrder($cardUID);
    }

    public function getPostByCardUID(int $cardUID): array
    {
        return $this->postDao->getPostByCardUID($cardUID);
    }

    public function getPostWithLimit(int $cardUID, int $start, int $count): array
    {
        return $this->postDao->getPostWithLimit($cardUID, $start, $count);
    }

    /**
     * @param int $cardUID
     * @param int $postOrder
     * @return PostDTO
     * @throws \Exception
     */
    public function getPostByPostOrder(int $cardUID, int $postOrder): PostDTO
    {
        try {
            return $this->postDao->getPostByPostOrder($cardUID, $postOrder);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param PostDTO $postDTO
     * @return null|string
     * @throws \Exception
     */
    public function insertPost(PostDTO $postDTO)
    {
        try {
            return $this->postDao->InsertPost($postDTO);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
